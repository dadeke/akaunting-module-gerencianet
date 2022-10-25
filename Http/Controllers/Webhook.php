<?php

namespace Modules\Gerencianet\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Events\Document\PaymentReceived;
use App\Jobs\Banking\CreateTransaction;
use App\Jobs\Document\CancelDocument;
use App\Models\Banking\Transaction as BankingTransaction;
use App\Models\Document\Document;
use App\Traits\Transactions as TransactionsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Gerencianet\Models\Transaction;

class Webhook extends Controller
{
    use TransactionsTrait;

    public $alias = 'gerencianet';

    public function __construct()
    {
        // This is to clear the middlewares.
    }

    public function index(Request $request)
    {
        $event = $request->all();

        if(count($event) === 0) {
            return response()->json([ 'status' => '400' ], 400);
        }

        company($request->company_id)->makeCurrent();

        $setting = setting($this->alias);

        if (empty($setting['webhook_secret']) ||
            $request->webhook_secret != $setting['webhook_secret'])
        {
            return response()->json([ 'status' => '401' ], 401);
        }

        if (! empty($event['pix'])) {
            $pix = $event['pix'][0];
            $document = null;
            $transaction = Transaction::where('txid', $pix['txid'])
                ->first();

            if ($transaction !== null) {
                $document = Document::find($transaction->document_id);
            }

            if (
                $document !== null &&
                $document->status !== 'paid' &&
                empty($pix['devolucoes'])
            )
            {
                $paid_at = explode('T', $pix['horario']);
                $paid_at = $paid_at[0];

                $request = [
                    'type' => 'income',
                    'payment_method' => $this->alias,
                    'paid_at' => $paid_at,
                    'amount' => $pix['valor'],
                    'account_id' => $setting['account_id'],
                    'description' => $pix['infoPagador']
                ];

                event(new PaymentReceived($document, $request));

                if(
                    ! empty($setting['vendor_id']) &&
                    ! empty($pix['gnExtras'])
                )
                {
                    $this->dispatch(new CreateTransaction([
                        'company_id' => company_id(),
                        'type' => BankingTransaction::EXPENSE_TYPE,
                        'number' => $this->getNextTransactionNumber(),
                        'account_id' => $setting['account_id'],
                        'paid_at' => $paid_at,
                        'amount' => $pix['gnExtras']['tarifa'],
                        'currency_code' => 'BRL',
                        'currency_rate' => 1,
                        'contact_id' => $setting['vendor_id'],
                        'description' => 'Gerencianet - Tarifa',
                        'category_id' => 1,
                        'payment_method' => 'offline-payments.bank_transfer.2',
                        'reference' => $document->document_number,
                        'created_from' => 'webhook'
                    ]));
                }

                Log::info('module=Gerencianet'
                    . ' action=Webhook'
                    . ' type=' . BankingTransaction::INCOME_TYPE
                    . ' document_id=' . $document->id
                    . ' response=' . json_encode($event)
                );
            }

            if(! empty($pix['devolucoes']))
            {
                foreach($pix['devolucoes'] as $refund) {
                    if($refund['status'] != 'DEVOLVIDO') {
                        continue;
                    }

                    $refund_at = explode('T', $refund['horario']['solicitacao']);
                    $refund_at = $refund_at[0];

                    // If full refund
                    if($document->amount === floatval($refund['valor'])) {
                        $transaction->delete();

                        $this->dispatch(new CancelDocument($document));
                    }
                    else {
                        $this->dispatch(new CreateTransaction([
                            'company_id' => company_id(),
                            'type' => BankingTransaction::EXPENSE_TYPE,
                            'number' => $this->getNextTransactionNumber(),
                            'account_id' => $setting['account_id'],
                            'paid_at' => $refund_at,
                            'amount' => $refund['valor'],
                            'currency_code' => 'BRL',
                            'currency_rate' => 1,
                            'contact_id' => $document->contact_id,
                            'description' => 'Gerencianet - Devolução parcial',
                            'category_id' => 1,
                            'payment_method' => 'offline-payments.bank_transfer.2',
                            'reference' => $document->document_number,
                            'created_from' => 'webhook'
                        ]));
                    }
                }

                Log::info('module=Gerencianet'
                    . ' action=Webhook'
                    . ' type=' . BankingTransaction::EXPENSE_TYPE
                    . ' document_id=' . $document->id
                    . ' response=' . json_encode($event)
                );
            }
        }

        return response()->json(['status' => '200']);
    }
}
