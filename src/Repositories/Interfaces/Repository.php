<?php

namespace matheusanandi\gitwebhook\Repositories\Interfaces;

interface Repository
{
    public function executeCommand(string $command): string;
}
