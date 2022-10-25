<?php

namespace Modules\Gerencianet\Listeners;

use App\Events\Menu\AdminCreated as Event;
use App\Models\Module\Module;

class AddToAdminMenu
{
    /**
     * Handle the event.
     *
     * @param  Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        $user = user();
        $can_read = $user->can('read-sales-invoices');

        if(! $can_read) {
            return;
        }

        $module = Module::enabled()->where('alias', 'gerencianet')->first();

        if(! empty($module) && $module->enable !== false) {
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
}
