<?php

namespace Modules\Gerencianet\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Traits\DateTime as TraitDateTime;
use Modules\Gerencianet\Models\Log;
use Modules\Gerencianet\Traits\Gerencianet;

class Logs extends Controller
{
    use TraitDateTime, Gerencianet;

    public function __construct()
    {
        // This is to clear the middlewares.
    }

    /**
     * Index
     */
    public function index()
    {
        $logs = Log::select(
                'id',
                'action',
                'error',
                'message',
                'created_at'
            )
            ->collect(['created_at' => 'desc']);

        $datetime_format = $this->getCompanyDateFormat() . ' ' . 'H:i:s';

        return $this->response('gerencianet::admin.index', [
            'records' => $logs,
            'tabActive' => 'logs',
            'withoutTabs' => ! setting('gerencianet.logs') == '1',
            'datetime_format' => $datetime_format,
            'certExpiry' => $this->getCertExpiry()
        ]);
    }
}
