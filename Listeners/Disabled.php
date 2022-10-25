<?php

namespace Modules\Gerencianet\Listeners;

use App\Events\Module\Disabled as Event;
use App\Models\Auth\UserCompany;

class Disabled
{
    /**
     * Handle the event.
     *
     * @param  Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        // Remove all favorites menu if exists
        $setting_key = 'favorites.menu.';
        $searched_value = [
            "title" => "Gerencianet",
            "icon" => "description",
            "route" => "gerencianet.transactions.index",
            "url" => ""
        ];
        $setting = setting();

        $company_users = UserCompany::withTrashed()->where(
            'company_id',
            $event->company_id
        )->get();

        foreach ($company_users as $company_user) {
            $setting_key_id = $setting_key . $company_user->user_id;
            $favorites = $setting->get($setting_key_id, null);

            if ($favorites === null) {
                continue;
            }

            $favorites = json_decode($favorites, true);
            if (
                count($favorites) > 0 &&
                ($key = array_search($searched_value, $favorites)) !== false
            ) {
                unset($favorites[$key]);
                $favorites = array_values($favorites);
                $setting->set($setting_key_id, json_encode($favorites));
                $setting->save();
            }
        }
    }
}
