<?php

namespace Differ\Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function isFileYaml(string $nameFile): string
{
    $expansion = explode('.', $nameFile)[1];
    return $expansion == 'yaml' | $expansion == 'yml';
}

function parserFile(string $path): object
{
    if (isFileYaml($path)) {
        return Yaml::parse(file_get_contents($path), Yaml::PARSE_OBJECT_FOR_MAP);
    }
    return json_decode(file_get_contents($path));
}
