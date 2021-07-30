<?php

namespace Differ\Parsers;

use Differ\Exeption;
use Symfony\Component\Yaml\Yaml;

function parserFile(string $string, string $extension = 'yaml'): object
{
    switch ($extension) {
        case 'json':
            return json_decode($string);
        case 'yaml':
            return Yaml::parse($string, Yaml::PARSE_OBJECT_FOR_MAP);
        case 'yml':
            return Yaml::parse($string, Yaml::PARSE_OBJECT_FOR_MAP);
        default:
            throw new Exception("src\Differ\Parsers non-parsed file extension");
    }
}
