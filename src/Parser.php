<?php

namespace Project\Package\Parsers;

use Symfony\Component\Yaml\Yaml;

function parserFileYaml(string $contentFile)
{
    return (array)Yaml::parse($contentFile, Yaml::PARSE_OBJECT_FOR_MAP);
}
