<?php

namespace Project\Package\Formatters;

use function Project\Package\Ast\compareIter;
use function Project\Package\Parsers\parserFile;
use function Project\Package\Formatters\Stylish\displayStylish;
use function Project\Package\Formatters\Plain\displayPlain;
use function Project\Package\Formatters\Json\displayJson;

function selectFormat(string $path1, string $path2, string $style): string
{
    if ($style === 'json') {
        return displayJson(compareIter(parserFile($path1), parserFile($path2)));
    } elseif ($style === 'plain') {
        return displayPlain(compareIter(parserFile($path1), parserFile($path2)));
    }
    return displayStylish(compareIter(parserFile($path1), parserFile($path2)));
}
