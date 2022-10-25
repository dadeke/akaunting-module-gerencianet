<?php

return [
  'name'          => 'Gerencianet',
  'description'   => 'Enables payments with Pix by Gerencianet for invoices.',
  'create_name'   => 'Invoice',
  'unavailable'   => 'This payment method is unavailable.',

  'form' => [
    'mode'              => 'Mode',
    'live'              => 'Live',
    'sandbox'           => 'Sandbox',
    'client_id'         => 'Client ID',
    'client_secret'     => 'Client Secret',
    'pix_cert'          => 'Pix certificate by Gerencianet (.pem)',
    'pix_cert_tooltip'  => 'Open the file with a simple text editor, copy and paste all content here.',
    'fine'              => 'Fine after due date (Percentage per month)',
    'fee'               => 'Daily interest after due date (Percentage per month)',
    'vendor'            => 'Vendor',
    'vendor_tooltip'    => 'Optional you can create the vendor "Gerencianet S.A." and assign it to make your reports more detailed.',
    'email_attachment'  => 'Attach the Pix QR Code image to email notifications',
    'order'             => 'Order',
    'field_validations' => 'Enable field validations on the Customers screen',
    'customer'          => 'Show to Customer'
  ],

  'transactions' => 'Gerencianet',
  'information' => 'Information',
  'empty' => [
    'transactions' => 'No transaction has been created yet.'
  ],

  'portal' => [
    'pix_info' => 'Use the camera of your phone to read the <br /><b>QR Code or copy the code</b> below to pay with your bank app.',
    'copied' => 'Copied!',
    'view_pdf' => 'View PDF'
  ]
];
