<?php

namespace matheusanandi\gitwebhook\Repositories;

use matheusanandi\gitwebhook\Repositories\Implementations\LocalRepository;
use matheusanandi\gitwebhook\Repositories\Implementations\SSHRepository;
use matheusanandi\gitwebhook\Repositories\Interfaces\Repository;
use matheusanandi\gitwebhook\RepositorySetting;
use stdClass;

class RepositoryFactory
{
    public static function createByRepositorySetting(stdClass $repositorySettings): Repository
    {

        $repository = null;

        if (empty($repositorySettings->host)) {
            $repository = new LocalRepository($repositorySettings);
        } else {
            $repository = new SSHRepository($repositorySettings);
        }

        return $repository;
    }
}
