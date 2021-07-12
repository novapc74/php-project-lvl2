<?php

namespace Differ\Formatters\Json;

use Symfony\Component\Yaml\Yaml;

function makeString(array $arr): string
{
    $key = $arr['key'];
    $type = $arr['type'];
    $oldValue = $arr['oldValue'];
    $newValue = $arr['newValue'];

    if (is_object($oldValue)) {
        $oldValue = get_object_vars($oldValue);
        $oldValue = json_encode($oldValue);
        is_string($newValue) ? $newValue = '"' . $newValue . '"' : '';
    } elseif (is_object($newValue)) {
        $newValue = get_object_vars($newValue);
        $newValue = json_encode($newValue);
        is_string($oldValue) ? $oldValue = '"' . $oldValue . '"' : '';
    } else {
        is_string($oldValue) ? $oldValue = '"' . $oldValue . '"' : '';
        is_string($newValue) ? $newValue = '"' . $newValue . '"' : '';
    }

    $oldValue = is_null($oldValue) ? 'null' : trim(var_export($oldValue, true), "'");
    $newValue = is_null($newValue) ? 'null' : trim(var_export($newValue, true), "'");

    switch ($type) {
        case 'replace':
            $result = '"oldValue":' . $oldValue . ',"newValue":' . $newValue;
            break;
        case 'added':
            $result = '"value":' . $newValue;
            break;
        case 'removed':
            $result = '"value":' . $oldValue;
            break;
        case 'unchanged':
            $result = '"value":' . $oldValue;
            break;
        default:
            throw new Error('Unknown order state: in \Formatters\Json\makeString => $type = {$type}!');
            break;
    }
    return $result;
}

function iter(array $arr): string
{
    $listForeReduce = array_keys($arr);
    $lines = array_reduce($listForeReduce, function ($acc, $item) use ($arr) {
        if ($arr[$item]['type'] === 'nested') {
            $acc[] = '{"key":"' . $arr[$item]['key'] . '","type":"' .
                $arr[$item]['type'] . '","children":' . iter($arr[$item]['children']) . '}';
        } else {
            $acc[] = '{"key":"' . $arr[$item]['key'] . '","type":"' .
                $arr[$item]['type'] . '",' . makeString($arr[$item]) . '}';
        }
        return $acc;
    }, []);
    return implode(['[', ...$lines, ']']);
}

function displayJson(array $arr): string
{
    return '{"type":"root","children":' . str_replace('}{', '},{', iter($arr)) . '}';
}
