<?php

namespace Modules\Gerencianet\Http\Controllers;

use App\Http\Controllers\Settings\Modules;
use App\Http\Requests\Setting\Module as Request;
use App\Models\Banking\Account;
use App\Models\Setting\Setting;
use Illuminate\Http\Response;
use Modules\Gerencianet\Traits\Gerencianet;

class Settings extends Modules
{
    use Gerencianet;

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit($alias)
    {
        $accounts = Account::enabled()->orderBy('name')->pluck('name', 'id');

        $setting = Setting::prefix($alias)->get()->transform(function ($s)
				use ($alias) {
            $s->key = str_replace($alias . '.', '', $s->key);
            return $s;
        })->pluck('value', 'key');

        $module = module($alias);
        $fields = $module->get('settings');

        // Translate values from selectGroup named "mode"
        $translated_values = array();
        $index_key = array_search('mode', array_column($fields, 'name'));
        foreach($fields[$index_key]['values'] as $key => $value) {
            $translated_values[$key] = trans($value);
        }
        $fields[$index_key]['values'] = $translated_values;

        return view(
            'gerencianet::settings.edit',
            compact('setting', 'module', 'accounts', 'fields')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function update($alias, Request $request)
    {
        $response = parent::update($alias, $request);

        $this->pixConfigWebhook();

        return $response;
    }
}
