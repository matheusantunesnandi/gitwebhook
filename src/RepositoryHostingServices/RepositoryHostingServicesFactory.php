<?php

namespace matheusanandi\gitwebhook\RepositoryHostingServices;

use matheusanandi\gitwebhook\RepositoryHostingServices\Interfaces\RepositoryHostingService;
use matheusanandi\gitwebhook\RepositorySetting;
use stdClass;

class RepositoryHostingServicesFactory
{
    public static function createByRepositorySetting(stdClass $repositorySettings): RepositoryHostingService
    {
        $repositoryHostingServiceClass = "{$repositorySettings->repository_hosting_service}RepositoryHostingService";

        $namespace = "\\matheusanandi\\gitwebhook\\RepositoryHostingServices\\Implementations\\";

        return new ($namespace . $repositoryHostingServiceClass);
    }
}
