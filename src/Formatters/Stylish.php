<?php

namespace Differ\Formatters\Stylish;

function stringifyObject(array $tree, int $spacesCount): string
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
    return $iter($tree, $depth = 1);
}

function stringifyTree(array $tree): string
{
    $key = $tree['key'];
    $type = $tree['type'];
    $newValue = is_null($tree['newValue']) ? 'null' : trim(var_export($tree['newValue'], true), "'");
    $oldValue = is_null($tree['oldValue']) ? 'null' : trim(var_export($tree['oldValue'], true), "'");

    switch ($type) {
        case 'replace':
            return "- {$key}: {$oldValue}" . "delmiter" . "+ {$key}: {$newValue}";
        case 'added':
            return "+ {$key}: {$newValue}";
        case 'removed':
            return  "- {$key}: {$oldValue}";
        case 'unchanged':
            return "  {$key}: {$oldValue}";
        default:
            throw new \Exception("src\Differ\Formatters\Stylish Unknown property");
    }
}

function iter(array $tree, int $depth = 1): string
{
    $indentSize = $depth * 4;
    $currentIndent = str_repeat(' ', $indentSize);
    $nextIndent = str_repeat(' ', $indentSize - 2);
    $bracketIndent = str_repeat(' ', $indentSize - 4);

    $listForMap = array_keys($tree);
    $lines = array_map(function ($item) use ($tree, $currentIndent, $nextIndent, $depth): string {
        $key = $tree[$item]['key'];
        $newTree = [];
        $value = iter($tree[$item]['children'], $depth + 1);
        if ($tree[$item]['type'] === 'nested') {
            return "{$currentIndent}{$key}: {$value}";
        }
        if (is_object($tree[$item]['oldValue'])) {
            $oldValue = stringifyObject(get_object_vars($tree[$item]['oldValue']), strlen($nextIndent) + 2);
            $string = stringifyTree([
                'key' => $tree[$item]['key'],
                'type' => $tree[$item]['type'],
                'newValue' => $tree[$item]['newValue'],
                'oldValue' => $oldValue
            ]);
            return $nextIndent . implode(PHP_EOL . $nextIndent, explode('delmiter', $string));
        }
        if (is_object($tree[$item]['newValue'])) {
            $newValue = stringifyObject(get_object_vars($tree[$item]['newValue']), strlen($nextIndent) + 2);
            $string = stringifyTree([
                'key' => $tree[$item]['key'],
                'type' => $tree[$item]['type'],
                'newValue' => $newValue,
                'oldValue' => $tree[$item]['oldValue']
            ]);
            return $nextIndent . implode(PHP_EOL . $nextIndent, explode('delmiter', $string));
        }
        return $nextIndent . implode(PHP_EOL . $nextIndent, explode('delmiter', stringifyTree($tree[$item])));
    }, $listForMap);
    return implode(PHP_EOL, ['{', ...$lines, "{$bracketIndent}}"]);
}

function render(array $tree): string
{
    return iter($tree);
}
