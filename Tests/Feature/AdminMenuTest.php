<?php

namespace Modules\Gerencianet\Tests\Feature;

use App\Traits\Permissions;
use Tests\Feature\FeatureTestCase;

class AdminMenuTest extends FeatureTestCase
{
    use Permissions;

    public function testItShouldSeeAdminTransactionsMenuItem()
    {
        $this->loginAs()
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee(route('gerencianet.transactions.index'))
            ->assertSee(trans_choice('gerencianet::general.transactions', 2));
    }

    public function testItShouldNotSeeAdminTransactionsMenuItem()
    {
        $this->detachPermissionsFromAdminRoles([
            'sales-invoices' => 'r',
        ]);

        $this->loginAs()
            ->get(route('dashboard'))
            ->assertOk()
            ->assertDontSee(
                '<a id="menu-gerencianet-transactions" class="flex items-center text-purple" href="'
                . route('gerencianet.transactions.index') . '" >',
                false
            );
    }
}
