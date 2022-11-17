<?php

namespace Modules\Gerencianet\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Traits\DateTime as TraitDateTime;
use DateTime;
use Modules\Gerencianet\Models\Transaction;

class Transactions extends Controller
{
    use TraitDateTime;

    public function __construct()
    {
        // This is to clear the middlewares.
    }

    /**
     * Index
     */
    public function index()
    {
        $emptyPageButtons = [
            [
                'url' => route('invoices.create'),
                'permission' => 'create-sales-invoices',
                'text' => trans(
                    'general.title.new',
                    ['type' => trans('gerencianet::general.create_name')]
                ),
                'description' => trans(
                    'general.empty.actions.new',
                    ['type' => trans('gerencianet::general.create_name')]
                ),
                'active_badge' => true
            ]
        ];

        $statuses = [
            'draft',
            'sent',
            'viewed',
            'partial',
            'paid'
        ];

        $transactions = Transaction::select(
                'id',
                'document_id'
            )
            ->whereHas('document',
                fn($query) => $query->select('id')
                    ->whereIn('status', $statuses)
            )
            ->collect(['document.issued_at' => 'desc']);

        // Eager loading
        $transactions->load(
            'document.contact',
            'document.items',
            'document.transactions'
        );

        // Show certificate expiration alert
        $certExpiry = null;
        $setting = setting('gerencianet');
        $certificate = openssl_x509_parse($setting['pix_cert']);
        $origin = new DateTime();
        $origin->setTimestamp($certificate['validTo_time_t']);
        $target = new DateTime();
        $interval = $origin->diff($target);
        $how_many_months = ($interval->y * 12) + $interval->m;
        if($how_many_months <= 4) {
            $alert_color = 'orange';
            $trans_key = 'gerencianet::general.warning_expiry';

            $replace = [
                'date' => date(
                    $this->getCompanyDateFormat(),
                    $certificate['validTo_time_t']
                ),
                'url_setting' => route('settings.module.edit', ['gerencianet'])
            ];

            if($how_many_months <= 2) {
                $alert_color = 'red';
                $trans_key = 'gerencianet::general.caution_expiry';
            }

            $certExpiry = [
                'color' => $alert_color,
                'message' => trans($trans_key, $replace)
            ];
        }

        return $this->response('gerencianet::admin.index', [
            'transactions' => $transactions,
            'emptyPageButtons' => $emptyPageButtons,
            'certExpiry' => $certExpiry
        ]);
    }
}
