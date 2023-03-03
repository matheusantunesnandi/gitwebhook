<?php

namespace matheusanandi\gitwebhook;

use Exception;
use matheusanandi\gitwebhook\Repositories\Interfaces\Repository;
use matheusanandi\gitwebhook\Repositories\RepositoryFactory;
use matheusanandi\gitwebhook\RepositoryHostingServices\Interfaces\RepositoryHostingService;
use matheusanandi\gitwebhook\RepositoryHostingServices\RepositoryHostingServicesFactory;

class WebhookService
{
    private Repository $repository;
    private RepositoryHostingService $repositoryHostingService;
    private $repositorySettings;

    public function __construct()
    {
        try {
            $repositoryName = filter_input(INPUT_GET, 'repositoryName');

            $this->repositorySettings = RepositorySetting::getRepositorySettingsByName($repositoryName);

            $this->repository = RepositoryFactory::createByRepositorySetting($this->repositorySettings);
            $this->repositoryHostingService = RepositoryHostingServicesFactory::createByRepositorySetting($this->repositorySettings);
        } catch (Exception $e) {
            self::sendResponse(500, false, $e->getMessage());
        }
    }

    private function validate()
    {
        $this->compareRepositories();
        $this->checkAuthentication();
    }

    private function compareRepositories()
    {
        $command = 'git remote get-url origin';

        $localOriginURL = trim($this->repository->executeCommand($command));

        if (!filter_var($localOriginURL, FILTER_VALIDATE_URL)) {
            self::sendResponse(500, false, 'O repositório local não está com o origin URL configurado corretamente.');
        }

        extract(parse_url($localOriginURL));

        $localOriginURL = "{$scheme}://{$host}{$path}";
        $requestingOriginURL = $this->repositoryHostingService->getURL() . '.git';

        if ($localOriginURL !== $requestingOriginURL) {
            $message = 'Requisição inválida. O repositório repositório local é mesmo que o remoto. ';
            self::sendResponse(400, false, $message);
        }
    }

    private function checkAuthentication()
    {
        $token = filter_input(INPUT_GET, 'token');

        if ($token !== $this->repositorySettings->token) {
            self::sendResponse(401, false, 'Token inválido.');
        }

        // TODO Não deixar exceção genérica. Mudar após implementar o checkAuthentication internamente:
        try {
            $this->repositoryHostingService->checkAuthentication();
        } catch (Exception $e) {
            self::sendResponse(401, false, $e->getMessage());
        }
    }

    public function execute()
    {
        $this->validate();

        $commands = $this->repositorySettings->commands;

        $outputs = [];

        foreach ($commands as $command) {
            $outputs[$command] = $this->repository->executeCommand($command);
        }

        self::sendResponse(200, true, 'Comandos executados.', $outputs);
    }

    public static function sendResponse(int $httpResponseCode, bool $status, string $message, mixed $data = null)
    {
        http_response_code($httpResponseCode);

        $response = [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];

        die(json_encode($response));
    }
}
