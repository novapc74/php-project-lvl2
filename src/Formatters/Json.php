<?php

namespace Differ\Formatters\Json;

function render(array $tree): string
{
    return json_encode($tree);
}

function iter(array $astFormat): string
{
    $listForMap = array_keys($astFormat);
    $lines = array_map(function ($item) use ($astFormat): string {
        if ($astFormat[$item]['type'] === 'nested') {
            return '{"key":"' . $astFormat[$item]['key'] . '","type":"' .
                $astFormat[$item]['type'] . '","children":' . iter($astFormat[$item]['children']) . '}';
        } else {
            return render($astFormat[$item]);
        }
    }, $listForMap);
    return implode('', ["[", ...$lines, "]"]);
}

function displayJson(array $astFormat): string
{
    return '{"type":"root","children":' . str_replace('}{', '},{', iter($astFormat)) . '}';
}
