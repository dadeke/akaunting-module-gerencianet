<?php

namespace Modules\Gerencianet\Listeners;

use App\Events\Document\DocumentCancelled as Event;
use App\Models\Document\Document;
use Illuminate\Support\Facades\Log;
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
                $this->pixCancelChargeWithDueDate($transaction->txid);

                Log::info('module=Gerencianet'
                    . ' action=Cancel'
                    . ' document_id=' . $document->id
                    . ' txid=' . $transaction->txid
                );
            }
            catch(\Exception $e) {
                $message = $e->getMessage();

                Log::error('module=Gerencianet'
                    . ' action=Cancel'
                    . ' document_id=' . $document->id
                    . ' txid=' . $transaction->txid
                    . ' error=' . $message
                );
            }
        }
    }
}
