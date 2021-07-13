<?php

namespace Differ\Formatters\Stylish;

use Symfony\Component\Yaml\Yaml;

function stringify(array $value, int $spacesCount = 1): string
{
    $iter = function ($currentValue, $depth) use (&$iter, $spacesCount) {
        if (!is_array($currentValue)) {
            return $currentValue;
        }
        $indentSize = $depth * $spacesCount;
        $currentIndent = str_repeat(' ', $indentSize + 4);
        $bracketIndent = str_repeat(' ', $indentSize);
        $lines = array_map(function ($key, $val) use ($currentIndent, $iter, $depth): string {
            $item = $val;
            if (is_object($val)) {
                $item = get_object_vars($val);
            }
            return $result = "{$currentIndent}{$key}: {$iter($item, $depth + 1)}";
        }, array_keys($currentValue), $currentValue);
        return implode(PHP_EOL, ["{", ...$lines, "{$bracketIndent}}"]);
    };
    return $iter($value, $depth = 1);
}
function makeString(array $arr, string $nextIndent): string
{
    $key = $arr['key'];
    $type = $arr['type'];
    if (is_object($arr['oldValue'])) {
        $oldValue = stringify(get_object_vars($arr['oldValue']), strlen($nextIndent) + 2);
        if (is_null($arr['newValue'])) {
            $newValue = 'null';
        } else {
            $newValue = trim(var_export($arr['newValue'], true), "'");
        }
    } elseif (is_object($arr['newValue'])) {
        $newValue = stringify(get_object_vars($arr['newValue']), strlen($nextIndent) + 2);
        if (is_null($arr['oldValue'])) {
            $oldValue = 'null';
        } else {
            $oldValue = trim(var_export($arr['oldValue'], true), "'");
        }
    } else {
        if (is_null($arr['newValue'])) {
            $newValue = 'null';
        } else {
            $newValue = trim(var_export($arr['newValue'], true), "'");
        }
        if (is_null($arr['oldValue'])) {
            $oldValue = 'null';
        } else {
            $oldValue = trim(var_export($arr['oldValue'], true), "'");
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
            return '';
    }
}
function displayStylish(array $arr, int $depth = 1): string
{
    $indentSize = $depth * 4;
    $currentIndent = str_repeat(' ', $indentSize);
    $nextIndent = str_repeat(' ', $indentSize - 2);
    $bracketIndent = str_repeat(' ', $indentSize - 4);

    $listForReduce = array_keys($arr);
    $lines = array_reduce($listForReduce, function ($acc, $item) use ($arr, $currentIndent, $nextIndent, $depth) {
        $key = $arr[$item]['key'];
        $value = displayStylish($arr[$item]['children'], $depth + 1);
        if ($arr[$item]['type'] === 'nested') {
            $acc[] = "{$currentIndent}{$key}: {$value}";
        } else {
            $acc[] = $nextIndent . makeString($arr[$item], $nextIndent);
        }
        return $acc;
    }, []);
    return implode(PHP_EOL, ['{', ...$lines, "{$bracketIndent}}"]);
}
