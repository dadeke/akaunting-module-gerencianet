<?php

namespace Modules\Gerencianet\Traits;

use App\Traits\DateTime as TraitDateTime;
use Gerencianet\Gerencianet as GerencianetVendor;
use Gerencianet\Endpoints as GerencianetEndpoints;
use Illuminate\Support\Str;

trait Gerencianet
{
    use TraitDateTime;

    private GerencianetEndpoints $api;

    private bool $sandbox;

    private string $certificate_path;

    // Constant for testing purposes
    private string $fake_chave = '54c87108-bcfa-4c70-8c5e-0204ac4b1b93';

    private function deleteTemporaryFiles(): void
    {
        // Delete temporary files
        unlink($this->certificate_path);
    }

    private function newInstance(array $options = []): void
    {
        $setting = setting('gerencianet');

        // Create temporary certificate file for API authentication
        $temp_path = ini_get('upload_tmp_dir') ?
            ini_get('upload_tmp_dir') : sys_get_temp_dir();
        $this->certificate_path = tempnam($temp_path, '.certificate');
        file_put_contents($this->certificate_path, $setting['pix_cert']);

        $this->sandbox = $setting['mode'] == 'sandbox' ? true : false;

        $current_options = [
            'client_id' => $setting['client_id'],
            'client_secret' => $setting['client_secret'],
            'pix_cert' => $this->certificate_path,
            'sandbox' => $this->sandbox,
            'debug' => false,
            'timeout' => 30,
        ];
        $current_options = array_merge($current_options, $options);

        $this->api = GerencianetVendor::getInstance($current_options);
    }

    public function pixCreateDueCharge(array $body): array
    {
        $txid = Str::uuid()->toString();
        $txid = str_replace('-', '', $txid);

        $params = [
            'txid' => $txid
        ];

        $modifiedBody = $body;

        $this->newInstance();
        if($this->sandbox) {
            // Constant for testing purposes
            $modifiedBody['chave'] = '54c87108-bcfa-4c70-8c5e-0204ac4b1b93';
        }
        else {
            $keys = $this->api->pixListEvp([], []);
            $modifiedBody['chave'] = $keys['chaves'][0];
        }

        $pix = $this->api->pixCreateDueCharge($params, $modifiedBody);
        $this->deleteTemporaryFiles();

        return $pix;
    }

    public function pixUpdateDueCharge(string $txid, array $body): void
    {
        $params = [
            'txid' => $txid
        ];

        $modifiedBody = $body;

        $this->newInstance();
        if($this->sandbox) {
            $modifiedBody['chave'] = $this->fake_chave;
        }
        else {
            $keys = $this->api->pixListEvp([], []);
            $modifiedBody['chave'] = $keys['chaves'][0];
        }

        $this->api->pixUpdateDueCharge($params, $modifiedBody);
        $this->deleteTemporaryFiles();
    }

    public function pixGenerateQRCode($location_id): array
    {
        $params = [
            'id' => $location_id
        ];

        $this->newInstance();
        $qrcode = $this->api->pixGenerateQRCode($params);
        $this->deleteTemporaryFiles();

        return $qrcode;
    }

    public function pixCancelDueCharge(string $txid): void
    {
        $params = [
            'txid' => $txid
        ];

        $body = [
            'status' => 'REMOVIDA_PELO_USUARIO_RECEBEDOR'
        ];

        $this->newInstance();
        $this->api->pixUpdateDueCharge($params, $body);
        $this->deleteTemporaryFiles();
    }

    public function pixConfigWebhook(): void
    {
        $app_setting = setting();
        $pix_cert = $app_setting->get('gerencianet.pix_cert');

        if($pix_cert == '-----FAKE CERTIFICATE-----') {
            return;
        }

        $webhook_secret = $app_setting->get('gerencianet.webhook_secret');
        if(empty($webhook_secret)) {
            $webhook_secret = Str::uuid()->toString();

            $app_setting->set('gerencianet.webhook_secret', $webhook_secret);
            $app_setting->save();
        }

        $webhook_registered = null;
        $webhook = url(company_id() . '/gerencianet/webhook/' . $webhook_secret);
        // $webhook = 'https://test.com/' . company_id() . '/gerencianet/webhook/' . $webhook_secret;

        $options = [
            'headers' => [
                'x-skip-mtls-checking' => 'true'
            ]
        ];
        $this->newInstance($options);

        $params = [];
        if($this->sandbox) {
            $params['chave'] = $this->fake_chave;
        }
        else {
            $keys = $this->api->pixListEvp([], []);
            $params['chave'] = $keys['chaves'][0];
        }

        try {
            $response = $this->api->pixDetailWebhook($params);

            if(array_key_exists('webhookUrl', $response)) {
                $webhook_registered = $response['webhookUrl'];
            }
        }
        catch(\Exception $e) {
            if(
                ! property_exists($e, 'error') ||
                $e->error != 'webhook_nao_encontrado'
            ) {
                throw $e;
            }
        }

        if(empty($webhook_registered) || $webhook_registered != $webhook) {
            $body = [
                'webhookUrl' => $webhook
            ];
            $this->api->pixConfigWebhook($params, $body);
        }

        $this->deleteTemporaryFiles();
    }

    public function getCertExpiry() {
        $certExpiry = null;
        $certTimestamp = setting('gerencianet.cert_timestamp');
        if ($certTimestamp !== null) {
            $origin = new \DateTime();
            $origin->setTimestamp($certTimestamp);
            $target = new \DateTime();
            $interval = $target->diff($origin);
            $howManyMonths = ($interval->y * 12) + $interval->m;
            if($interval->invert === 1) {
                $howManyMonths = $howManyMonths * -1;
            }

            if($howManyMonths <= 4) {
                $alertColor = 'orange';
                $transKey = 'gerencianet::general.cert_expiry_warning';

                $replace = [
                    'date' => date(
                        $this->getCompanyDateFormat(),
                        $certTimestamp
                    ),
                    'url_setting' => route('settings.module.edit', ['gerencianet'])
                ];

                if($howManyMonths <= 2) {
                    $alertColor = 'red';
                    $transKey = 'gerencianet::general.caution_cert_expiry';
                }

                $certExpiry = [
                    'color' => $alertColor,
                    'message' => trans($transKey, $replace)
                ];
            }
        }

        return $certExpiry;
    }
}
