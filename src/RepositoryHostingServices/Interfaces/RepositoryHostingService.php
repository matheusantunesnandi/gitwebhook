<?php

namespace matheusanandi\gitwebhook\RepositoryHostingServices\Interfaces;

interface RepositoryHostingService
{
    public function getToken(): string;

    public function parseRequestPayload(): object;

    public function getRequestPayload(): object;

    public function getURL(): string;

    public function checkAuthentication();
}
