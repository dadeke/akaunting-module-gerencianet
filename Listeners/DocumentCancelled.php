<?php

namespace Modules\Gerencianet\Listeners;

use App\Events\Document\DocumentCancelled as Event;
use App\Models\Document\Document;
use Illuminate\Support\Facades\Log as FacadeLog;
use Modules\Gerencianet\Models\Log;
use Modules\Gerencianet\Models\Transaction;
use Modules\Gerencianet\Traits\Gerencianet;

class DocumentCancelled
{
    use Gerencianet;

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
        $setting = setting('gerencianet');

        if($setting === null || !array_key_exists('client_id', $setting)) {
            return;
        }

        $types = [
            Document::INVOICE_TYPE,
            Document::INVOICE_RECURRING_TYPE
        ];

        $transaction = Transaction::where(
            'document_id',
            $document->id
        )->first();

        if(in_array($document->type, $types) && $transaction !== null)
        {
            try {
                $this->pixCancelDueCharge($transaction->txid);

                if($setting['logs'] == '1') {
                    Log::create([
                        'company_id' => $document->company_id,
                        'document_id' => $document->id,
                        'action' => 'cancel',
                        'error' => false,
                        'message' => json_encode([
                            'txid' => $transaction->txid
                        ])
                    ]);
                }
                else {
                    FacadeLog::info('module=Gerencianet'
                        . ' action=Cancel'
                        . ' document_id=' . $document->id
                        . ' txid=' . $transaction->txid
                    );
                }
            }
            catch(\Exception $e) {
                if($setting['logs'] == '1') {
                    Log::create([
                        'company_id' => $document->company_id,
                        'document_id' => $document->id,
                        'action' => 'cancel',
                        'error' => true,
                        'message' => $e->getMessage()
                    ]);
                }
                else {
                    FacadeLog::error('module=Gerencianet'
                        . ' action=Cancel'
                        . ' document_id=' . $document->id
                        . ' txid=' . $transaction->txid
                        . ' message=' . $e->getMessage()
                    );
                }
            }
        }
    }
}
