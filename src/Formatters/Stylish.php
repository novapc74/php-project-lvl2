<?php

namespace Differ\Formatters\Stylish;

use PHPUnit\Framework\TestCase;

function stringify(array $value, int $spacesCount = 1): string
{
    $iter = function ($currentValue, $depth) use (&$iter, $spacesCount): string {
        if (!is_array($currentValue)) {
            return $currentValue;
        }
        $indentSize = $depth * $spacesCount;
        $currentIndent = str_repeat(' ', $indentSize + 4);
        $bracketIndent = str_repeat(' ', $indentSize);
        $lines = array_map(function ($key, $item) use ($currentIndent, $iter, $depth): string {
            if (is_object($item)) {
                $line = get_object_vars($item);
            } else {
                $line = $item;
            }
            return "{$currentIndent}{$key}: {$iter($line, $depth + 1)}";
        }, array_keys($currentValue), $currentValue);
        return implode(PHP_EOL, ["{", ...$lines, "{$bracketIndent}}"]);
    };
    return $iter($value, $depth = 1);
}

function render(array $astFormat, string $nextIndent): string
{
    $key = $astFormat['key'];
    $type = $astFormat['type'];
    if (is_object($astFormat['oldValue'])) {
        $oldValue = stringify(get_object_vars($astFormat['oldValue']), strlen($nextIndent) + 2);
        if (is_null($astFormat['newValue'])) {
            $newValue = 'null';
        } else {
            $newValue = trim(var_export($astFormat['newValue'], true), "'");
        }
    } elseif (is_object($astFormat['newValue'])) {
        $newValue = stringify(get_object_vars($astFormat['newValue']), strlen($nextIndent) + 2);
        if (is_null($astFormat['oldValue'])) {
            $oldValue = 'null';
        } else {
            $oldValue = trim(var_export($astFormat['oldValue'], true), "'");
        }
    } else {
        if (is_null($astFormat['newValue'])) {
            $newValue = 'null';
        } else {
            $newValue = trim(var_export($astFormat['newValue'], true), "'");
        }
        if (is_null($astFormat['oldValue'])) {
            $oldValue = 'null';
        } else {
            $oldValue = trim(var_export($astFormat['oldValue'], true), "'");
        }
    }
    switch ($type) {
        case 'replace':
            return "- {$key}: {$oldValue}" . PHP_EOL . "{$nextIndent}+ {$key}: {$newValue}";
        case 'added':
            return "+ {$key}: {$newValue}";
        case 'removed':
            return  "- {$key}: {$oldValue}";
        case 'unchanged':
            return "  {$key}: {$oldValue}";
        default:
            throw new Exception("src\Differ\Formatters\Stylish Unknown property");
    }
}
function displayStylish(array $astFormat, int $depth = 1): string
{
    $indentSize = $depth * 4;
    $currentIndent = str_repeat(' ', $indentSize);
    $nextIndent = str_repeat(' ', $indentSize - 2);
    $bracketIndent = str_repeat(' ', $indentSize - 4);

    $listForMap = array_keys($astFormat);
    $lines = array_map(function ($item) use ($astFormat, $currentIndent, $nextIndent, $depth): string {
        $key = $astFormat[$item]['key'];
        $value = displayStylish($astFormat[$item]['children'], $depth + 1);
        if ($astFormat[$item]['type'] === 'nested') {
            return "{$currentIndent}{$key}: {$value}";
        } else {
            return $nextIndent . render($astFormat[$item], $nextIndent);
        }
    }, $listForMap);
    return implode(PHP_EOL, ['{', ...$lines, "{$bracketIndent}}"]);
}
