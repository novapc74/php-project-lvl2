<?php

namespace Differ\Parsers;

// use PHPUnit\Framework\TestCase\Exeption;
use Symfony\Component\Yaml\Yaml;

function parserFile(string $stringData, string $extension = 'yaml'): object
{
    switch ($extension) {
        case 'json':
            return json_decode($stringData);
        case 'yaml':
            return Yaml::parse($stringData, Yaml::PARSE_OBJECT_FOR_MAP);
        case 'yml':
            return Yaml::parse($stringData, Yaml::PARSE_OBJECT_FOR_MAP);
        default:
            throw new \Exception("src\Differ\Parsers non-parsed file extension");
    }
}
