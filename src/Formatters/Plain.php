<?php

namespace Differ\Formatters\Plain;

function makeString(array $arr, string $node = null): string
{
    $key = $arr['key'];
    $type = $arr['type'];
    if (isset($node)) {
        $delimiter = $node . '.';
    } else {
        $delimiter = null;
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
            return null;
            // throw new Error('Unknown order state: in \Formatters\Plain\makeString => $type = {$type}!');
    }
}
function displayPlain(array $arr, string $node = null): string
{
    $listForReduce = array_keys($arr);
    $lines = array_reduce($listForReduce, function ($acc, $item) use ($arr, $node) {
        if ($arr[$item]['type'] === 'nested' && !isset($node)) {
            $node = $arr[$item]['key'];
            $acc[] = displayPlain($arr[$item]['children'], $node);
        } elseif ($arr[$item]['type'] === 'nested') {
            $node .= '.' . $arr[$item]['key'];
            $acc[] = displayPlain($arr[$item]['children'], $node);
        } elseif ($arr[$item]['type'] !== 'unchanged') {
            $acc[] = makeString($arr[$item], $node);
        }
        return $acc;
    }, []);
    return implode(PHP_EOL, [...$lines]);
}
