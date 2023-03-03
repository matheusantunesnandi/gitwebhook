<?php

namespace matheusanandi\gitwebhook;

use ReflectionClass;
use stdClass;
use UnexpectedValueException;

class RepositorySetting
{
    // TODO Pegar do ENV.
    private const REPOSITORY_SETTINGS_PATH = __DIR__ . "/../config/repositories_settings.json";

    private static function getRepositoriesSettings(): array
    {
        if (!is_file(self::REPOSITORY_SETTINGS_PATH)) {
            throw new UnexpectedValueException("Arquivo não encontrado: '{self::REPOSITORY_SETTINGS_PATH}'");
        }

        $json = file_get_contents(self::REPOSITORY_SETTINGS_PATH);
        $decoded = json_decode($json);

        if (!empty($json) && empty($decoded)) {
            throw new UnexpectedValueException("Não é um JSON válido: '{self::REPOSITORY_SETTINGS_PATH}'");
        }

        return $decoded;
    }

    public static function getRepositorySettingsByName(string $repositoryName): stdClass
    {
        $nestedProperties = [
            'name',
            'token',
            'path',
            'repository_hosting_service',
            'url',
            'uuid',
            'host',
            'host_username',
            'host_password',
            'commands'
        ];

        foreach (self::getRepositoriesSettings() as $repositorySetting) {
            $loadedProperties = array_keys(get_object_vars($repositorySetting));
            $diff = array_diff($nestedProperties, $loadedProperties);

            if (!empty($diff)) {
                $campos = implode(', ', $diff);
                $message = "Configurações internas inválidas para '{$repositoryName}'. Campos faltando: '{$campos}'";
                throw new UnexpectedValueException($message);
            }

            if ($repositorySetting->name === $repositoryName) {
                return $repositorySetting;
            }
        }
    }
}
