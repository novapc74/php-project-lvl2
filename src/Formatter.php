<?php

namespace Differ\Formatter;

use PHPUnit\Framework\TestCase;

use function Differ\Formatters\Stylish\displayStylish;
use function Differ\Formatters\Plain\displayPlain;
use function Differ\Formatters\Json\displayJson;

function chooseFormat(array $ast, string $style): string
{
    switch ($style) {
        case 'json':
            return displayJson($ast);
        case 'plain':
            return displayPlain($ast);
        case 'stylish':
            return displayStylish($ast);
        default:
            throw new Exception("src\Differ\Formatter Can`t find format", 1);
    }
}
