<?php

return [
  'name'          => 'Efí',
  'description'   => 'Enables payments with Pix by Efí for invoices.',
  'create_name'   => 'Invoice',
  'unavailable'   => 'This payment method is unavailable.',

  'form' => [
    'mode'              => 'Mode',
    'live'              => 'Live',
    'sandbox'           => 'Sandbox',
    'client_id'         => 'Client ID',
    'client_secret'     => 'Client Secret',
    'pix_cert'          => 'Pix certificate by Efí (.pem)',
    'pix_cert_tooltip'  => 'Open the file with a simple text editor, copy and paste all content here.',
    'fine'              => 'Fine after due date (Percentage per month)',
    'fee'               => 'Daily interest after due date (Percentage per month)',
    'vendor'            => 'Vendor',
    'vendor_tooltip'    => 'Optional you can create the vendor "Efí S.A." and assign it to make your reports more detailed.',
    'email_attachment'  => 'Attach the Pix QR Code image to email notifications',
    'order'             => 'Order',
    'field_validations' => 'Enable field validations on the Customers screen',
    'logs'              => 'Enable logs',
    'customer'          => 'Show to Customer'
  ],

  'transactions' => 'Efí',
  'transactions_tab' => 'Transactions',
  'logs_tab' => 'Logs',
  'empty' => [
    'transactions' => 'No transaction has been created yet.',
    'logs' => 'No log has been created yet.'
  ],
  'log' => [
    'execution_date' => 'Execution Date',
    'action' => 'Action',
    'message' => 'Message',
    'success' => 'Success',
    'error' => 'Error',
    'enable' => 'Enable',
    'disable' => 'Disable',
    'create' => 'Create',
    'update' => 'Update',
    'cancel' => 'Cancel',
    'webhook' => 'Webhook',
    'show' => 'Show'
  ],
  'cert_expiry_warning' => 'Warning: Your certificate will expire at :date and you will need to create the new certificate on <a href="https://app.sejaefi.com.br/api/meus-certificados" target="_blank"><strong>https://app.sejaefi.com.br/api/meus-certificados</strong></a> and replace the old one in the <a href=":url_setting"><strong>settings</strong></a>.',
  'caution_cert_expiry' => 'Caution: Your certificate is expiring at :date. Create the new certificate on <a href="https://app.sejaefi.com.br/api/meus-certificados" target="_blank"><strong>https://app.sejaefi.com.br/api/meus-certificados</strong></a> and replace the old one in the <a href=":url_setting"><strong>settings</strong></a> so that the integration does not stop.',

  'portal' => [
    'pix_info' => 'Use the camera of your phone to read the <br /><b>QR Code or copy the code</b> below to pay with your bank app.',
    'copied' => 'Copied!'
  ],

  'email' => [
    'pix_image' => 'You can use your phone camera to read the <strong>QR Code</strong>:',
    'pix_code' => '<strong>Or copy the code</strong> and pay in your bank app:'
  ]
];
