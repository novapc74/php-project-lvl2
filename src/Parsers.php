<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parserFile(string $path, string $typeFile): object
{
    $fileContent = file_get_contents($path);
    return Yaml::parse($fileContent, Yaml::PARSE_OBJECT_FOR_MAP);
}
