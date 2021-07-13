<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parserFile(string $path, string $typeFile): object
{
    return Yaml::parse(file_get_contents($path), Yaml::PARSE_OBJECT_FOR_MAP);
}
