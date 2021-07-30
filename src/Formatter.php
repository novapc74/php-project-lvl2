<?php

namespace Differ\Formatter;

use function Differ\Formatters\Stylish\displayStylish;
use function Differ\Formatters\Plain\displayPlain;
use function Differ\Formatters\Json\displayJson;

function chooseFormat(array $ast, string $style): string
{
    if ($style == 'json') {
        return displayJson($ast);
    }
    if ($style == 'plain') {
        return displayPlain($ast);
    }
    return displayStylish($ast);
}
