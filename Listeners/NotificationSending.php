<?php

namespace Modules\Gerencianet\Listeners;

use App\Notifications\Sale\Invoice;
use App\Models\Module\Module;
use Illuminate\Notifications\Events\NotificationSending as Event;
use Illuminate\Support\Facades\Notification;
use Modules\Gerencianet\Notifications\Transaction;

class NotificationSending
{
    /**
     * Handle the event.
     *
     * @param Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        if($event->channel === 'mail' && $event->notification instanceof Invoice)
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

            $statuses = [
                'draft',
                'sent',
                'viewed'
            ];

            if (
                $setting['email_attachment'] === '1' &&
                in_array($event->notification->invoice->status, $statuses) &&
                $event->notification->template->alias != 'invoice_view_admin'
            )
            {
                $transaction = new Transaction(
                    $event->notification->invoice,
                    $event->notification->template->alias,
                    true
                );

                Notification::send($event->notifiable, $transaction);

                return false;
            }
        }
    }
}
