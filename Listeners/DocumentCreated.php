<?php

namespace Modules\Gerencianet\Listeners;

use App\Events\Document\DocumentCreated as Event;
use App\Models\Document\Document;
use App\Models\Module\Module;
use Illuminate\Support\Facades\Log;
use Modules\Gerencianet\Models\Transaction;
use Modules\Gerencianet\Traits\Gerencianet;

class DocumentCreated
{
    use Gerencianet;

    private function getOnlyNumbers($string) {
        return preg_replace('/[^0-9]/', '', $string);
    }

    private function getFormatedAddress($full_address)
    {
        $address = '';

        if(trim($full_address) == '') {
            return $address;
        }

        $lines = explode("\r\n", $full_address);

        $count_lines = count($lines);
        for($count = 0; $count < $count_lines; $count += 1) {
            $address .= $lines[$count];

            if(($count >= 0) && ($count + 1) < $count_lines) {
                $address .= ', ';
            }
        }

        return $address;
    }

    /**
     * Handle the event.
     *
     * @param  Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        $this->execute($event->document);
    }

    public function execute(Document $document)
    {
        $module = Module::enabled()->where('alias', 'gerencianet')->first();
        $setting = setting('gerencianet');

        if(empty($module) ||
            (! empty($module) && $module->enable === false) ||
            $setting === null ||
            ! array_key_exists('client_id', $setting))
        {
            return;
        }

        $types = [
            Document::INVOICE_TYPE,
            Document::INVOICE_RECURRING_TYPE
        ];

        if(in_array($document->type, $types) && $document->currency_code === 'BRL')
        {
            $action = 'Create';

            try {
                $date_now = date('Y-m-d');
                $due_at = explode(' ', $document->due_at);
                $due_at = $due_at[0];
                // Dates in the past are not allowed.
                if(strtotime($due_at) < strtotime($date_now)) {
                    $due_at = $date_now;
                }

                $address = $this->getFormatedAddress($document->contact_address);
                $city = $document->contact_city;
                $state = $document->contact_state;
                $contact_zip_code = $this->getOnlyNumbers(
                    $document->contact_zip_code
                );

                if(empty($address)) {
                    $address = 'Logradouro não informado';
                }

                if(empty($city)) {
                    $city = 'Cidade não informada';
                }

                if(empty($state)) {
                    $state = 'XX';
                }

                if(empty($contact_zip_code)) {
                    $contact_zip_code = '00000000';
                }

                $body = [
                    'calendario' => [
                        'dataDeVencimento' => $due_at,
                        'validadeAposVencimento' => 60
                    ],
                    'devedor' => [
                        'nome' => $document->contact_name,
                        'email' => $document->contact_email,
                        'logradouro' => substr($address, 0, 200),
                        'cidade' => substr($city, 0, 200),
                        'uf' => substr($state, 0, 2),
                        'cep' => substr($contact_zip_code, 0, 8)
                    ],
                    'valor' => [
                        'original' => number_format($document->amount, 2, '.', '')
                    ],
                    'solicitacaoPagador' => strval($document->id)
                ];

                $contact_tax_number = $this->getOnlyNumbers(
                    $document->contact_tax_number
                );
                $key_tax_number = strlen($contact_tax_number) === 11 ? 'cpf' : 'cnpj';
                $body['devedor'][$key_tax_number] = $contact_tax_number;

                if(
                    array_key_exists('fine', $setting) &&
                    ($setting['fine'] !== '') &&
                    ($setting['fine'] !== '0')
                ) {
                    $fine = floatval($setting['fine']);
                    $fine = number_format($fine / 100, 2, '.', '');

                    $body['valor']['multa'] = [
                        'modalidade' => 2,
                        'valorPerc' => $fine
                    ];
                }

                if(
                    array_key_exists('fee', $setting) &&
                    ($setting['fee'] !== '') &&
                    ($setting['fee'] !== '0')
                ) {
                    $fee = floatval($setting['fee']);
                    $fee = number_format($fee / 100, 2, '.', '');

                    $body['valor']['juros'] = [
                        'modalidade' => 2,
                        'valorPerc' => $fee
                    ];
                }

                $txid = null;

                $transaction = Transaction::where(
                    'document_id',
                    $document->id
                )->first();

                if($transaction === null) {
                    $pix = $this->pixCreateChargeWithDueDate($body);
                    Transaction::create([
                        'company_id'  => $document->company_id,
                        'document_id' => $document->id,
                        'location_id' => $pix['loc']['id'],
                        'txid' => $pix['txid']
                    ]);
                    $txid = $pix['txid'];
                }
                else {
                    $action = 'Update';
                    $this->pixUpdateChargeWithDueDate($transaction->txid, $body);
                    $txid = $transaction->txid;
                }

                Log::info('module=Gerencianet'
                    . ' action=' . $action
                    . ' document_id=' . $document->id
                    . ' txid=' . $txid
                );
            }
            catch(\Exception $e) {
                $message = null;

                if(property_exists($e, 'error') &&
                    property_exists($e, 'errorDescription')) {
                        $message = $e->error . ' ' . json_encode($e->errorDescription);
                }
                else {
                    $message = $e->getMessage();
                }

                Log::error('module=Gerencianet'
                    . ' action=' . $action
                    . ' document_id=' . $document->id
                    . ' error=' . $message
                );
            }
        }
    }
}
