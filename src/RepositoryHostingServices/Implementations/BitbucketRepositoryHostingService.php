<?php

namespace matheusanandi\gitwebhook\RepositoryHostingServices\Implementations;

use matheusanandi\gitwebhook\RepositoryHostingServices\Interfaces\RepositoryHostingService;
use UnexpectedValueException;

class BitbucketRepositoryHostingService implements RepositoryHostingService
{
    private object $requestPayload;

    public function getToken(): string
    {
        return $_SERVER['HTTP_X_HUB_SIGNATURE'] ?? '';
    }

    public function parseRequestPayload(): object
    {
        $input = file_get_contents('php://input');

        if (empty($input)) {
            throw new UnexpectedValueException("O payload da requisição está vazio.");
        }

        $inputDecoded = json_decode($input);

        if (!empty($input) && empty($inputDecoded)) {
            throw new UnexpectedValueException("Payload da solicitação é inválido.");
        }

        return $inputDecoded;
    }

    public function getRequestPayload(): object
    {
        if (empty($this->requestPayload)) {
            $this->requestPayload = $this->parseRequestPayload();
        }

        return $this->requestPayload;
    }

    public function getURL(): string
    {
        return $this->getRequestPayload()->repository->links->html->href;
    }

    public function checkAuthentication()
    {
        // TODO Bitbucket authentication além do token.
    }
}
