<?php

namespace Differ\Formatters\Plain;

function makeString(array $arr, string $node = null): string
{
    $key = $arr['key'];
    $type = $arr['type'];
    if (isset($node)) {
        $delimiter = $node . '.';
    } else {
        $delimiter = '';
    }
    if (is_object($arr['oldValue'])) {
        $oldValue = '[complex value]';
        if (is_null($arr['newValue'])) {
            $newValue = 'null';
        } else {
            $newValue = trim(var_export($arr['newValue'], true), '"');
        }
    } elseif (is_object($arr['newValue'])) {
        $newValue = '[complex value]';
        if (is_null($arr['oldValue'])) {
            $oldValue = 'null';
        } else {
            $oldValue = trim(var_export($arr['oldValue'], true), '"');
        }
    } else {
        if (is_null($arr['oldValue'])) {
            $oldValue = 'null';
        } else {
            $oldValue = trim(var_export($arr['oldValue'], true), '"');
        }
        if (is_null($arr['newValue'])) {
            $newValue = 'null';
        } else {
            $newValue = trim(var_export($arr['newValue'], true), '"');
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
            return 'error in type';
    }
}

function displayPlain(array $arr, string $node = null): string
{
    $listForMap = array_keys($arr);
    $lines = array_map(function ($item) use ($arr, $node): string {
        if ($arr[$item]['type'] === 'nested' && !isset($node)) {
            $node1 = $arr[$item]['key'];
            return displayPlain($arr[$item]['children'], $node1);
        } elseif ($arr[$item]['type'] === 'nested') {
            $node2 = $node . '.' . $arr[$item]['key'];
            return displayPlain($arr[$item]['children'], $node2);
        } elseif ($arr[$item]['type'] !== 'unchanged') {
            return makeString($arr[$item], $node);
        }
        return '';
    }, $listForMap);
    $filterData = array_filter($lines, fn ($item): bool => $item !== '');
    return implode(PHP_EOL, [...$filterData]);
}
