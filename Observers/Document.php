<?php

namespace Modules\Gerencianet\Observers;

use App\Abstracts\Observer;
use App\Models\Document\Document as Model;
use Modules\Gerencianet\Listeners\DocumentCancelled;

class Document extends Observer
{
    /**
     * Listen to the deleted event.
     *
     * @param  Model $document
     *
     * @return void
     */
    public function deleted(Model $document)
    {
        $listen_cancel = new DocumentCancelled();
        $listen_cancel->execute($document);
    }
}
