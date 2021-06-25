<?php

namespace Differ\Differ;

use function Differ\Formatters\Stylish\displayStylish;
use function Differ\Formatters\Plain\displayPlain;
use function Differ\Formatters\Json\displayJson;

function genDiff(array $ast, string $style): string
{
    if ($style === 'json') {
        return displayJson($ast);
    } elseif ($style === 'plain') {
        return displayPlain($ast);
    }
    return displayStylish($ast);
}
