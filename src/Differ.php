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
            break;
        case 'plain':
            return displayPlain($ast);
            break;
        default:
            return displayStylish($ast);
            break;
    }
}

function genDiff(string $beginFilePath, string $endFilePath, string $styleOutput = ''): string
{
    $listSupportExtensions = ['yml', 'json', 'yaml'];
    try {
        $fileExtensions = isFileExtension($beginFilePath, $endFilePath, $listSupportExtensions);
    } catch (\Exception $e) {
        echo $e;
        exit;
    }

    $object1 = parserFile($beginFilePath, $fileExtensions);
    $object2 = parserFile($endFilePath, $fileExtensions);

        $ast = compareIter($object1, $object2);
    try {
        return getDiff($ast, $styleOutput) . PHP_EOL;
    } catch (\Exception $e) {
        echo $e;
        exit;
    }
}
