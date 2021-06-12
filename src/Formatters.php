<?php

namespace Project\Package\Formatters;

use function Project\Package\Parsers\parserFile;
use function Project\Package\Ast\compareIter;
use function Project\Package\Formatters\Stylish\displayStylish;
use function Project\Package\Formatters\Plain\displayPlain;

function selectFormat(string $path1, string $path2, string $style): string
{
    if ($style === 'stylish') {
        return displayStylish(compareIter(parserFile($path1), parserFile($path2)));
    } else {
        return displayPlain(compareIter(parserFile($path1), parserFile($path2)));
    }
}
