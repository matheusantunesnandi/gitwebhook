<?php

namespace matheusanandi\gitwebhook\Repositories\Implementations;

use matheusanandi\gitwebhook\Repositories\Interfaces\Repository;
use stdClass;

class LocalRepository implements Repository
{
    private stdClass $repositorySettings;

    public function __construct(stdClass $repositorySettings)
    {
        $this->repositorySettings = $repositorySettings;
    }

    public function executeCommand(string $command): string
    {
        chdir($this->repositorySettings->path);

        return shell_exec("{$command}") ?: '';
    }
}
