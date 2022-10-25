<?php

namespace Modules\Gerencianet\Listeners;

use App\Events\Module\PaymentMethodShowing as Event;

class ShowAsPaymentMethod
{
    /**
     * Handle the event.
     *
     * @param  Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        $method = setting('gerencianet');

        $method['code'] = 'gerencianet';

        $event->modules->payment_methods[] = $method;
    }
}
