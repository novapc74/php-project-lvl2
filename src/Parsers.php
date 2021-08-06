<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parserFile(string $fileContent, string $extension): object
{
    switch ($extension) {
        case 'json':
            return json_decode($fileContent);
        case 'yml':
        case 'yaml':
            return Yaml::parse($fileContent, Yaml::PARSE_OBJECT_FOR_MAP);
        default:
            throw new \Exception("src\Differ\Parsers non-parsed file extension");
    }
}
