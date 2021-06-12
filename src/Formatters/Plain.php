<?php

namespace Project\Package\Formatters\Plain;

use function Project\Package\Formatters\Stylish\toString;

function makeString(array $arr, string $parent): string
{
    $key = $arr['key'];
    if (is_object($arr['oldValue'])) {
        $newValue = "'" . toString($arr['newValue']) . "'";
        $oldValue = '[complex value]';
    } elseif (is_object($arr['newValue'])) {
        $newValue = '[complex value]';
        $oldValue = "'" . toString($arr['oldValue']) . "'";
    } else {
        $oldValue = toString($arr['oldValue']);
        $newValue = toString($arr['newValue']);
    }

    switch ($arr['type']) {
        case 'removed':
            $string = "'{$parent}{$key}' was removed";
            break;
        case 'added':
            $string = "'{$parent}{$key}' was added with value: {$newValue}";
            break;
        case 'replace':
            $string = "'{$parent}{$key}' was updated. From {$oldValue} to {$newValue}";
            break;
    }
    return $string;
}

function displayPlain($arr, $parent = '')
{
    $listForReduce = array_keys($arr);
    $lines = array_reduce($listForReduce, function ($acc, $item) use ($arr, $parent) {
        $parent === '' ? $key = $arr[$item]['key'] : $key = $parent . '.' . $arr[$item]['key'];
        $value = displayPlain($arr[$item]['children'], $key);
        if ($arr[$item]['type'] === 'nested') {
            $acc[] = $value;
        } elseif ($arr[$item]['type'] !== 'unchanged') {
            $parent !== '' ? $parent .= "." : '';
            $acc[] = 'Property ' . makeString($arr[$item], $parent);
        }
        return $acc;
    }, []);
    $result = [...$lines];
    return implode(PHP_EOL, $result);
}
