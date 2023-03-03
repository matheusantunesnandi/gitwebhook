<?php
require_once(__DIR__ . '/../vendor/autoload.php');

// TODO Implementar o DotEnv e adicionar uma senha para o TOKEN. Usar de comparação simples com GET ou para checar o JWT.

use matheusanandi\gitwebhook\WebhookService;

$webhook = new WebhookService();
$webhook->execute();
