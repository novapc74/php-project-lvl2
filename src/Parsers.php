<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parserFile(string $stringData, string $extension): object
{
    switch ($extension) {
        case 'json':
            return json_decode($stringData);
        case ('yaml' || 'yml'):
            return Yaml::parse($stringData, Yaml::PARSE_OBJECT_FOR_MAP);
        default:
            throw new \Exception("src\Differ\Parsers non-parsed file extension");
    }
}
