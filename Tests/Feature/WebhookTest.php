<?php

namespace Modules\Gerencianet\Tests\Feature;

use Tests\Feature\FeatureTestCase;

class WebhookTest extends FeatureTestCase
{
    protected $setting = [
        'webhook_secret' => '8de940d8-9f75-4ea2-85f8-6a4bacce574c'
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $setting = setting();
        $setting->setExtraColumns(['company_id' => $this->company->id]);
        $setting->set('gerencianet', $this->setting);
        $setting->save();
    }

    public function testItShouldWebhookResponseOk()
    {
        $parameters = [
            'company_id' => $this->company->id,
            'webhook_secret' => $this->setting['webhook_secret']
        ];

        $data = [
            'status' => '200'
        ];

        $this->post(route('gerencianet.invoices.webhook', $parameters), $data)
            ->assertStatus(200);
    }

	public function testItShouldWebhookResponseError()
    {
        $parameters = [
            'company_id' => $this->company->id,
            'webhook_secret' => '6e42125b-3744-456b-b629-6b431f3795e0'
        ];

        $data = [
            'status' => '200'
        ];

        $this->post(route('gerencianet.invoices.webhook', $parameters), $data)
            ->assertStatus(401);
    }
}
