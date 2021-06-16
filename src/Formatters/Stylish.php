<?php

namespace Project\Package\Formatters\Stylish;

function stringify(array $value, int $spacesCount = 1): string
{
    $iter = function ($currentValue, $depth) use (&$iter, $spacesCount) {
        if (!is_array($currentValue)) {
            return $currentValue;
        }
        $indentSize = $depth * $spacesCount;
        $currentIndent = str_repeat(' ', $indentSize + 4);
        $bracketIndent = str_repeat(' ', $indentSize);
        $lines = array_map(function ($key, $val) use ($currentIndent, $iter, $depth) {
            is_object($val) ? $val = get_object_vars($val) : '';
            return $result = "{$currentIndent}{$key}: {$iter($val, $depth + 1)}";
        }, array_keys($currentValue), $currentValue);
        return implode(PHP_EOL, ['{', ...$lines, "{$bracketIndent}}"]);
    };
    return $iter($value, 1);
}

function stringifyValue(array $arr, string $nextIndent): string
{
    $key = $arr['key'];
    $type = $arr['type'];
    $oldValue = $arr['oldValue'];
    $newValue = $arr['newValue'];

    if (is_object($oldValue)) {
        $oldValue = get_object_vars($oldValue);
        $oldValue = stringify($oldValue, strlen($nextIndent) + 2) . $nextIndent;
    } elseif (is_object($newValue)) {
        $newValue = get_object_vars($newValue);
        $newValue = stringify($newValue, strlen($nextIndent) + 2) . $nextIndent;
    }

    $oldValue = is_null($oldValue) ? 'null' : trim(var_export($oldValue, true), "'");
    $newValue = is_null($newValue) ? 'null' : trim(var_export($newValue, true), "'");

    switch ($type) {
        case 'replace':
            $result = "- {$key}: {$oldValue}" . PHP_EOL;
            $result .= "{$nextIndent}+ {$key}: {$newValue}";
            break;
        case 'added':
            $result = "+ {$key}: {$newValue}";
            break;
        case 'removed':
            $result = "- {$key}: {$oldValue}";
            break;
        case 'unchanged':
            $result = "  {$key}: {$oldValue}";
            break;
        default:
            throw new Error('Unknown order state: in \Stylish\stringifyValue => $type = {$type}!');
            break;
    }
    return $result;
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
            $acc[] = $nextIndent . stringifyValue($arr[$item], $nextIndent);
        }
        return $acc;
    }, []);
    return implode(PHP_EOL, ['{', ...$lines, "{$bracketIndent}}"]);
}
