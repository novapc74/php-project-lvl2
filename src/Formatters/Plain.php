<?php

namespace Differ\Formatters\Plain;

use PHPUnit\Framework\TestCase\Exeption;

function render(array $astFormat, string $node = null): string
{
    $key = $astFormat['key'];
    $type = $astFormat['type'];
    if (isset($node)) {
        $delimiter = $node . '.';
    } else {
        $delimiter = '';
    }
    if (is_object($astFormat['oldValue'])) {
        $oldValue = '[complex value]';
        if (is_null($astFormat['newValue'])) {
            $newValue = 'null';
        } else {
            $newValue = trim(var_export($astFormat['newValue'], true), '"');
        }
    } elseif (is_object($astFormat['newValue'])) {
        $newValue = '[complex value]';
        if (is_null($astFormat['oldValue'])) {
            $oldValue = 'null';
        } else {
            $oldValue = trim(var_export($astFormat['oldValue'], true), '"');
        }
    } else {
        if (is_null($astFormat['oldValue'])) {
            $oldValue = 'null';
        } else {
            $oldValue = trim(var_export($astFormat['oldValue'], true), '"');
        }
        if (is_null($astFormat['newValue'])) {
            $newValue = 'null';
        } else {
            $newValue = trim(var_export($astFormat['newValue'], true), '"');
        }
    }
    switch ($type) {
        case 'removed':
            return "Property '{$delimiter}{$key}' was removed";
        case 'added':
            return "Property '{$delimiter}{$key}' was added with value: {$newValue}";
        case 'replace':
            return "Property '{$delimiter}{$key}' was updated. From {$oldValue} to {$newValue}";
        default:
            throw new \Exception("src\Differ\Formatters\Plain Unknown property");
    }
}

function displayPlain(array $astFormat, string $node = null): string
{
    $listForMap = array_keys($astFormat);
    $lines = array_map(function ($item) use ($astFormat, $node): string {
        if ($astFormat[$item]['type'] === 'nested' && !isset($node)) {
            $node1 = $astFormat[$item]['key'];
            return displayPlain($astFormat[$item]['children'], $node1);
        }
        if ($astFormat[$item]['type'] === 'nested') {
            $node2 = $node . '.' . $astFormat[$item]['key'];
            return displayPlain($astFormat[$item]['children'], $node2);
        }
        if ($astFormat[$item]['type'] !== 'unchanged') {
            return render($astFormat[$item], $node);
        }
        return '';
    }, $listForMap);
    $filterData = array_filter($lines, fn ($item): bool => $item !== '');
    return implode(PHP_EOL, [...$filterData]);
}
