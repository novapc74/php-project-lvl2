<?php

namespace Differ\Differ;

use function Differ\Formatters\Stylish\displayStylish;
use function Differ\Formatters\Plain\displayPlain;
use function Differ\Formatters\Json\displayJson;
use function Differ\Parsers\parserFile;
use function Differ\AstFotmatter\compareIter;

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
    $object1 = parserFile($beginFilePath);
    $object2 = parserFile($endFilePath);
    $ast = compareIter($object1, $object2);
    return getDiff($ast, $styleOutput);
}
