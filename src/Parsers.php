<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parserFile(string $path): object
{
    return Yaml::parseFile($path, Yaml::PARSE_OBJECT_FOR_MAP);
}
