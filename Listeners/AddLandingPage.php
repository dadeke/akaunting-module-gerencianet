<?php

namespace Modules\Gerencianet\Listeners;

use App\Events\Auth\LandingPageShowing as Event;

class AddLandingPage
{
    /**
     * Handle the event.
     *
     * @param Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        $event->user->landing_pages['gerencianet.settings.edit'] = trans('gerencianet::general.name');
    }
}
