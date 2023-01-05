<?php

namespace Modules\Gerencianet\Notifications;

use App\Abstracts\Notification;
use App\Models\Setting\EmailTemplate;
use App\Models\Document\Document;
use App\Traits\Documents;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log as FacadeLog;
use Modules\Gerencianet\Models\Log;
use Modules\Gerencianet\Models\Transaction as ModelsTransaction;
use Modules\Gerencianet\Traits\Gerencianet;

class Transaction extends Notification
{
    use Documents, Gerencianet;

    /**
     * The invoice model.
     *
     * @var object
     */
    public $invoice;

    /**
     * The email template.
     *
     * @var EmailTemplate
     */
    public $template;

    /**
     * Should embed image or not.
     *
     * @var bool
     */
    public $embed_image;

    private function embedMessageBody(string $body, string $embed): string {
        $updatedBody = null;

        if(str_contains($body, '{invoice_pix_qrcode}')) {
            $updatedBody = str_replace('{invoice_pix_qrcode}', $embed, $body);
        }
        else {
            $position = strpos($body, 'table');
            $position --;
            $header = substr($body, 0, $position);
            $footer = substr($body, $position);

            $updatedBody = $header . $embed . $footer;
        }

        return $updatedBody;
    }

    /**
     * Create a notification instance.
     */
    public function __construct(
        Document $invoice = null,
        string $template_alias = null,
        bool $embed_image = false,
        array $custom_mail = []
    )
    {
        parent::__construct();

        $this->invoice = $invoice;
        $this->template = EmailTemplate::alias($template_alias)->first();
        $this->embed_image = $embed_image;
        $this->custom_mail = $custom_mail;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toMail($notifiable): MailMessage
    {
        $transaction = null;

        if (!empty($this->custom_mail['to'])) {
            $notifiable->email = $this->custom_mail['to'];
        }

        $message = $this->initMailMessage();

        // Embed image into mail body
        if ($this->embed_image) {
            $transaction = ModelsTransaction::where(
                    'document_id', $this->invoice->id
                )->first();
        }

        if($transaction !== null) {
            $payment = null;

            try {
                $payment = $this->pixGenerateQRCode($transaction->location_id);
            }
            catch(\Exception $e) {
                if(setting('gerencianet.logs') == '1') {
                    Log::create([
                        'company_id' => company_id(),
                        'document_id' => $this->invoice->id,
                        'action' => 'show',
                        'error' => true,
                        'message' => $e->getMessage()
                    ]);
                }
                else {
                    FacadeLog::error('module=Gerencianet'
                        . ' action=Show'
                        . ' document_id=' . $this->invoice->id
                        . ' txid=' . $transaction->txid
                        . ' message=' . $e->getMessage()
                    );
                }
            }

            if($payment !== null) {
                $filename = 'pix_qrcode.png';

                $email = [
                    'embed_image' => 'cid:' . $filename,
                    'pix_code' => $payment['qrcode']
                ];

                $html_pix = view('gerencianet::email', compact('email'))
                    ->render();

                $message->viewData['body'] = $this->embedMessageBody(
                    $message->viewData['body'],
                    $html_pix
                );

                $positionImage = strpos($payment['imagemQrcode'], ',');
                $positionImage ++;
                $png = substr($payment['imagemQrcode'], $positionImage);

                $message->attachData(base64_decode($png), $filename, [
                    'mime' => 'image/png'
                ]);
            }
        }

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toArray($notifiable): array
    {
        $this->initArrayMessage();

        return [
            'template_alias' => $this->template->alias,
            'title' => trans(
                'notifications.menu.' . $this->template->alias . '.title'
            ),
            'description' => trans(
                'notifications.menu.' . $this->template->alias . '.description',
                $this->getTagsBinding()
            ),
            'invoice_id' => $this->invoice->id,
            'invoice_number' => $this->invoice->document_number,
            'customer_name' => $this->invoice->contact_name,
            'amount' => $this->invoice->amount,
            'invoiced_date' => company_date($this->invoice->issued_at),
            'invoice_due_date' => company_date($this->invoice->due_at),
            'status' => $this->invoice->status,
        ];
    }

    public function getTags(): array
    {
        return [
            '{invoice_number}',
            '{invoice_total}',
            '{invoice_amount_due}',
            '{invoiced_date}',
            '{invoice_due_date}',
            '{invoice_guest_link}',
            '{invoice_admin_link}',
            '{invoice_portal_link}',
            '{customer_name}',
            '{company_name}',
            '{company_email}',
            '{company_tax_number}',
            '{company_phone}',
            '{company_address}',
        ];
    }

    public function getTagsReplacement(): array
    {
        return [
            $this->invoice->document_number,
            money(
                $this->invoice->amount,
                $this->invoice->currency_code,
                true
            ),
            money(
                $this->invoice->amount_due,
                $this->invoice->currency_code,
                true
            ),
            company_date($this->invoice->issued_at),
            company_date($this->invoice->due_at),
            URL::signedRoute('signed.invoices.show', [$this->invoice->id]),
            route('invoices.show', $this->invoice->id),
            route('portal.invoices.show', $this->invoice->id),
            $this->invoice->contact_name,
            $this->invoice->company->name,
            $this->invoice->company->email,
            $this->invoice->company->tax_number,
            $this->invoice->company->phone,
            nl2br(trim($this->invoice->company->address)),
        ];
    }
}
