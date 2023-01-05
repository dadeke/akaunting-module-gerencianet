<?php

namespace Modules\Gerencianet\Tests\Feature;

use Tests\Feature\FeatureTestCase;

class SettingsTest extends FeatureTestCase
{
    public function testItShouldSeeGerencianetInSettingsListPage()
    {
        $this->loginAs()
            ->get(route('dashboard'))
            ->assertStatus(200)
            ->assertSeeText(trans('gerencianet::general.name'));
    }

    public function testItShouldSeeGerencianetSettingsUpdatePage()
    {
        $this->loginAs()
            ->get(route('settings.module.edit', ['alias' => 'gerencianet']))
            ->assertStatus(200);
    }

    public function testItShouldUpdateGerencianetSettings()
    {
        $this->loginAs()
            ->patch(
                route('settings.module.edit', ['alias' => 'gerencianet']),
                $this->getRequest())
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function getRequest()
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->Text,
            'account_id' => '1',
            'mode' => 'sandbox',
            'client_id' => 'Client_Id_6ef5e5c493f22ef42d1c052e069af5df3060c090',
            'client_secret' => 'Client_Secret_cfeb3e01f0d7d2217fc5f522f73c67ea56e5a669',
            'pix_cert' => '-----FAKE CERTIFICATE-----',
            'fine' => '',
            'fee' => '',
            'vendor_id' => '',
            'email_attachment' => '1',
            'order' => '1',
            'field_validations' => '1',
            'logs' => '1',
            'customer' => '1'
        ];
    }
}
