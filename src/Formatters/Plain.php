<?php

namespace Project\Package\Formatters\Plain;

use function Project\Package\Formatters\Stylish\toString;

function makeString(array $arr, string $spaceCount): string
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
            $string = "'{$spaceCount}{$key}' was removed";
            break;
        case 'added':
            $string = "'{$spaceCount}{$key}' was added with value: {$newValue}";
            break;
        case 'replace':
            $string = "'{$spaceCount}{$key}' was updated. From {$oldValue} to {$newValue}";
            break;
    }
    return $string;
}

function displayPlain($arr, $spaceCount = '')
{
    $listForReduce = array_keys($arr);
    $lines = array_reduce($listForReduce, function ($acc, $item) use ($arr, $spaceCount) {
        $spaceCount === '' ? $key = $arr[$item]['key'] : $key = $spaceCount . '.' . $arr[$item]['key'];
        if ($arr[$item]['type'] === 'nested') {
            $acc[] = displayPlain($arr[$item]['children'], $key);
        } elseif ($arr[$item]['type'] !== 'unchanged') {
            $spaceCount !== '' ? $spaceCount .= "." : '';
            $acc[] = 'Property ' . makeString($arr[$item], $spaceCount);
        }
        return $acc;
    }, []);
    return implode(PHP_EOL, [...$lines]);
}
