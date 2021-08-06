<?php

namespace Differ\Formatters\Plain;

use PHPUnit\Framework\TestCase\Exeption;

function stringify(array $tree): string
{
    $key = $tree['key'];
    $type = $tree['type'];
    if (is_object($tree['oldValue'])) {
        $oldValue = '[complex value]';
        $newValue = is_null($tree['newValue']) ? 'null' : trim(var_export($tree['newValue'], true), '"');
    } elseif (is_object($tree['newValue'])) {
        $newValue = '[complex value]';
        $oldValue = is_null($tree['oldValue']) ? 'null' : trim(var_export($tree['oldValue'], true), '"');
    } else {
        $newValue = is_null($tree['newValue']) ? 'null' : trim(var_export($tree['newValue'], true), '"');
        $oldValue = is_null($tree['oldValue']) ? 'null' : trim(var_export($tree['oldValue'], true), '"');
    }
    switch ($type) {
        case 'removed':
            return "{$key}' was removed";
        case 'added':
            return "{$key}' was added with value: {$newValue}";
        case 'replace':
            return "{$key}' was updated. From {$oldValue} to {$newValue}";
        default:
            throw new \Exception("src\Differ\Formatters\Plain Unknown property");
    }
}

function iter(array $tree, string $node = null): string
{
    $listForMap = array_keys($tree);
    $lines = array_map(function ($item) use ($tree, $node): string {
        if ($tree[$item]['type'] === 'nested' && !isset($node)) {
            $node1 = $tree[$item]['key'];
            return iter($tree[$item]['children'], $node1);
        }
        if ($tree[$item]['type'] === 'nested') {
            $node2 = $node . '.' . $tree[$item]['key'];
            return iter($tree[$item]['children'], $node2);
        }
        if ($tree[$item]['type'] !== 'unchanged') {
            if ($node === null) {
                return "Property '" . stringify($tree[$item]);
            }
            return "Property '{$node}." . stringify($tree[$item]);
        }
        return '';
    }, $listForMap);
    $filterData = array_filter($lines, fn ($item): bool => $item !== '');
    return implode(PHP_EOL, [...$filterData]);
}

function render(array $tree): string
{
    return iter($tree);
}
