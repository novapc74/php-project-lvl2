<?php

namespace Differ\Formatters\Json;

use Symfony\Component\Yaml\Yaml;

function makeString(array $arr): string
{
    $key = $arr['key'];
    $type = $arr['type'];
    if (is_object($arr['oldValue'])) {
        $oldValue = json_encode(get_object_vars($arr['oldValue']));
        if (is_string($arr['newValue'])) {
            $newValue = $newValue = '"' . $arr['newValue'] . '"';
        }
    } elseif (is_object($arr['newValue'])) {
        $newValue = json_encode(get_object_vars($arr['newValue']));
        if (is_string($arr['oldValue'])) {
            $oldValue = '"' . $arr['oldValue'] . '"';
        }
    } else {
        if (is_null($arr['oldValue'])) {
            $oldValue = 'null';
        } else {
            $oldValue = trim(var_export($arr['oldValue'], true), "'");
        }
        if (is_null($arr['newValue'])) {
            $newValue = 'null';
        } else {
            $newValue = trim(var_export($arr['newValue'], true), "'");
        }
        if (is_string($arr['oldValue'])) {
            $oldValue = '"' . $arr['oldValue'] . '"';
        }
        if (is_string($arr['newValue'])) {
            $newValue = $newValue = '"' . $arr['newValue'] . '"';
        }
    }
    switch ($type) {
        case 'replace':
            return '"oldValue":' . $oldValue . ',"newValue":' . $newValue;
        case 'added':
            return '"value":' . $newValue;
        case 'removed':
            return '"value":' . $oldValue;
        case 'unchanged':
            return '"value":' . $oldValue;
        default:
            return '';
    }
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
