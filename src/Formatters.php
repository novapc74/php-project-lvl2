<?php

namespace Differ\Differ\Formatters;

use function Differ\Differ\Ast\compareIter;
use function Differ\Differ\Parsers\parserFile;
use function Differ\Differ\Formatters\Stylish\displayStylish;
use function Differ\Differ\Formatters\Plain\displayPlain;
use function Differ\Differ\Formatters\Json\displayJson;

function selectFormat(string $path1, string $path2, string $style): string
{
    $parserPath1 = parserFile($path1);
    $parserPath2 = parserFile($path2);
    if ($style === 'json') {
        return displayJson(compareIter($parserPath1, $parserPath2));
    } elseif ($style === 'plain') {
        return displayPlain(compareIter($parserPath1, $parserPath2));
    }
    return displayStylish(compareIter($parserPath1, $parserPath2));
}
