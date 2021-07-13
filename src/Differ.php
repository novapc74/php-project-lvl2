<?php

namespace Differ\Differ;

use function Differ\Formatters\Stylish\displayStylish;
use function Differ\Formatters\Plain\displayPlain;
use function Differ\Formatters\Json\displayJson;
use function Differ\Extension\isFileExtension;
use function Differ\Parsers\parserFile;
use function Differ\Ast\compareIter;

function getDiff(array $ast, string $style): string
{
    switch ($style) {
        case 'json':
            return displayJson($ast);
        case 'plain':
            return displayPlain($ast);
        default:
            return displayStylish($ast);
    }
}

function genDiff(string $beginFilePath, string $endFilePath, string $styleOutput = ''): string
{
    $listSupportExtensions = ['yml', 'json', 'yaml'];
    try {
        $fileExtensions = isFileExtension($beginFilePath, $endFilePath, $listSupportExtensions);
    } catch (\Exception $e) {
        echo $e;
    }
    $object1 = parserFile($beginFilePath, $fileExtensions);
    $object2 = parserFile($endFilePath, $fileExtensions);
    $ast = compareIter($object1, $object2);
    try {
        return getDiff($ast, $styleOutput);
    } catch (\Exception $e) {
        echo $e;
    }
}
