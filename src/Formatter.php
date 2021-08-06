<?php

namespace Differ\Formatter;

use function Differ\Formatters\Stylish\render as renderStylishFormat;
use function Differ\Formatters\Plain\render as renderPlainFormat;
use function Differ\Formatters\Json\render as renderJsonFormat;

function chooseFormat(array $tree, string $style = 'stylish'): string
{
    switch ($style) {
        case 'json':
            return renderJsonFormat($tree);
        case 'plain':
            return renderPlainFormat($tree);
        case 'stylish':
            return renderStylishFormat($tree);
        default:
            throw new \Exception("src\Differ\Formatter unknown format");
    }
}
