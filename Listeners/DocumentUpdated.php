<?php

namespace Modules\Gerencianet\Listeners;

use App\Events\Document\DocumentUpdated as Event;

class DocumentUpdated
{
    /**
     * Handle the event.
     *
     * @param  Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        $document = $event->document;

        $statuses = [
            'draft',
            'sent',
            'viewed'
        ];

        if(in_array($document->status, $statuses))
        {
            $listenCreate = new DocumentCreated();
            $listenCreate->execute($document);
        }
    }
}
