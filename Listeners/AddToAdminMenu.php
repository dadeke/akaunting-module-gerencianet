<?php

namespace Modules\Gerencianet\Listeners;

use App\Events\Menu\AdminCreated as Event;
use App\Traits\Modules;

class AddToAdminMenu
{
    use Modules;

    /**
     * Handle the event.
     *
     * @param  Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        if($this->moduleIsDisabled('gerencianet') || user()->cannot('read-sales-invoices')) {
            return;
        }

        $item = $event->menu->whereTitle(trans_choice('general.sales', 2));
        $item->route(
            'gerencianet.transactions.index',
            trans('gerencianet::general.transactions'),
            [],
            12,
            ['icon' => '']
        );
    }
}
