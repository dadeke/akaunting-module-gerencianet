<?php

return [
  'name'          => 'Gerencianet',
  'description'   => 'Habilita pagamentos com Pix da Gerencianet para as faturas.',
  'create_name'   => 'Fatura',
  'unavailable'   => 'Esse método de pagamento está indisponível.',

  'form' => [
    'mode'              => 'Modo',
    'live'              => 'Produção',
    'sandbox'           => 'Ambiente de teste',
    'client_id'         => 'Client ID',
    'client_secret'     => 'Client Secret',
    'pix_cert'          => 'Certificado fornecido pela Gerencianet (.pem)',
    'pix_cert_tooltip'  => 'Abra o arquivo com um editor de texto simples, copie e cole todo conteúdo aqui.',
    'fine'              => 'Multa após o vencimento (Percentual ao mês)',
    'fee'               => 'Juros diários após o vencimento (Percentual ao mês)',
    'vendor'            => 'Fornecedor',
    'vendor_tooltip'    => 'Opcionalmente pode ser criado o fornecedor "Gerencianet S.A." e atribuí-lo para tornar seus relatórios mais detalhados.',
    'email_attachment'  => 'Anexar a imagem do QR Code do Pix às notificações de e-mail',
    'order'             => 'Ordem',
    'field_validations' => 'Ativar validações de campos na tela Clientes',
    'logs'              => 'Ativar logs',
    'customer'          => 'Mostrar ao Cliente'
  ],

  'transactions' => 'Gerencianet',
  'transactions_tab' => 'Transações',
  'logs_tab' => 'Logs',
  'empty' => [
    'transactions' => 'Ainda não foi criada nenhuma transação.',
    'logs' => 'Ainda não foi criado nenhum log.'
  ],
  'log' => [
    'execution_date' => 'Data da Execução',
    'action' => 'Ação',
    'message' => 'Mensagem',
    'success' => 'Sucesso',
    'error' => 'Erro',
    'enable' => 'Ativa',
    'disable' => 'Desativa',
    'create' => 'Cria',
    'update' => 'Atualiza',
    'cancel' => 'Cancela',
    'webhook' => 'Webhook',
    'show' => 'Exibe'
  ],
  'cert_expiry_warning' => 'Alerta: Seu certificado vai expirar no dia :date e será preciso criar o novo certificado em <a href="https://app.gerencianet.com.br/api/meus-certificados" target="_blank"><strong>https://app.gerencianet.com.br/api/meus-certificados</strong></a> e substituir o antigo nas <a href=":url_setting"><strong>configurações</strong></a>.',
  'caution_cert_expiry' => 'Cuidado: Seu certificado está expirando no dia :date. Crie o novo certificado em <a href="https://app.gerencianet.com.br/api/meus-certificados" target="_blank"><strong>https://app.gerencianet.com.br/api/meus-certificados</strong></a> e substitua o antigo nas <a href=":url_setting"><strong>configurações</strong></a> para que a integração não seja interrompida.',

  'portal' => [
    'pix_info' => 'Utilize a câmera do seu celular para ler o <br /><b>QR Code ou copie o código</b> abaixo para pagar com o app do seu banco.',
    'copied' => 'Copiado!'
  ],

  'email' => [
    'pix_image' => 'Use a câmera do seu celular para ler o <strong>QR Code</strong>:',
    'pix_code' => '<strong>Ou copie o código</strong> e pague no app do seu banco:'
  ]
];
