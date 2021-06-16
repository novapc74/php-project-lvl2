<?php

namespace Project\Package\Formatters;

use function Project\Package\Ast\compareIter;
use function Project\Package\Parsers\parserFile;
use function Project\Package\Formatters\Stylish\displayStylish;
use function Project\Package\Formatters\Plain\displayPlain;
use function Project\Package\Formatters\Json\displayJson;

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
