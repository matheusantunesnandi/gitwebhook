<?php

namespace matheusanandi\gitwebhook\Repositories\Implementations;

use matheusanandi\gitwebhook\Repositories\Interfaces\Repository;
use stdClass;

class SSHRepository implements Repository
{
    private stdClass $repositorySettings;

    public function __construct(stdClass $repositorySettings)
    {
        $this->repositorySettings = $repositorySettings;
    }

    public function executeCommand(string $command): string
    {
        // TODO Executar commando via SSH e obter o retorno do stdout e stderr.
    }
}
