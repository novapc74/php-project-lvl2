<?php

namespace Project\Package\Parsers;

use Symfony\Component\Yaml\Yaml;

function isFilesYaml(string $nameFile): string
{
    $fileType = explode('.', $nameFile)[1];
    return $fileType == 'yaml' || $fileType == 'yml' ? true : false;
}

function parserFile(string $pathToFile): object
{
    if (isFilesYaml($pathToFile)) {
        $contaetnFileObject = Yaml::parse(file_get_contents($pathToFile), Yaml::PARSE_OBJECT_FOR_MAP);
    } else {
        $contaetnFileObject = json_decode(file_get_contents($pathToFile));
    }
    return $contaetnFileObject;
}
