<?php

namespace Differ\Formatters\Plain;

function makeString(array $arr, string $node = null): string
{
    $key = $arr['key'];
    $type = $arr['type'];
    $oldValue = $arr['oldValue'];
    $newValue = $arr['newValue'];
    !$node ?: $node .= '.';

    if (is_object($oldValue) && is_object($newValue)) {
        $oldValue = '[complex value]';
        $newValue = '[complex value]';
    } elseif (is_object($oldValue)) {
        $oldValue = '[complex value]';
        $newValue = is_null($newValue) ? 'null' : trim(var_export($newValue, true), '"');
    } elseif (is_object($newValue)) {
        $newValue = '[complex value]';
        $oldValue = is_null($oldValue) ? 'null' : trim(var_export($oldValue, true), '"');
    } else {
        $oldValue = is_null($oldValue) ? 'null' : trim(var_export($oldValue, true), '"');
        $newValue = is_null($newValue) ? 'null' : trim(var_export($newValue, true), '"');
    }

    switch ($type) {
        case 'removed':
            $result = "Property '{$node}{$key}' was removed";
            break;
        case 'added':
            $result = "Property '{$node}{$key}' was added with value: {$newValue}";
            break;
        case 'replace':
            $result = "Property '{$node}{$key}' was updated. From {$oldValue} to {$newValue}";
            break;
        default:
            throw new Error('Unknown order state: in \Formatters\makeString => $type = {$type}!');
            break;
    }
    return $result;
}

function displayPlain(array $arr, string $node = null): string
{
    $listForReduce = array_keys($arr);
    $lines = array_reduce($listForReduce, function ($acc, $item) use ($arr, $node) {
        if ($arr[$item]['type'] === 'nested' && !$node) {
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
