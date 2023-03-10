<?php

namespace Modules\Gerencianet\Tests\Feature;

use Tests\Feature\FeatureTestCase;

class TransactionTest extends FeatureTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $certificate_path = dirname(__FILE__) . '/../certificate.pem';
        $values = [
            'pix_cert' => file_get_contents($certificate_path)
        ];

        $setting = setting();
        $setting->setExtraColumns(['company_id' => $this->company->id]);
        $setting->set('gerencianet', $values);
        $setting->save();
    }

    public function testItShouldSeeTransactionListPage()
    {
        $this->loginAs()
            ->get(route('gerencianet.transactions.index'))
            ->assertStatus(200)
            ->assertSeeText(trans('gerencianet::general.transactions'));
    }

    public function testItShouldNotSeeTransactionTab()
    {
        $this->loginAs()
            ->get(route('gerencianet.transactions.index'))
            ->assertStatus(200)
            ->assertDontSee('tab-transactions');
    }

    public function testItShouldSeeTransactionTab()
    {
        $setting = setting();
        $setting->setExtraColumns(['company_id' => $this->company->id]);
        $setting->set('gerencianet', ['logs' => '1']);
        $setting->save();

        $this->loginAs()
            ->get(route('gerencianet.transactions.index'))
            ->assertStatus(200)
            ->assertSee('tab-transactions');
    }
}
