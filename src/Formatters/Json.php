<?php

namespace Differ\Formatters\Json;

function stringifyValue(array $arr): string
{
    $key = $arr['key'];
    $type = $arr['type'];
    $oldValue = $arr['oldValue'];
    $newValue = $arr['newValue'];

    if (is_object($oldValue)) {
        $oldValue = get_object_vars($oldValue);
        $oldValue = json_encode($oldValue);
    } elseif (is_object($newValue)) {
        $newValue = get_object_vars($newValue);
        $newValue = json_encode($newValue);
    }
    $oldValue = is_null($oldValue) ? 'null' : trim(var_export($oldValue, true), "'");
    $newValue = is_null($newValue) ? 'null' : trim(var_export($newValue, true), "'");

    switch ($type) {
        case 'replace':
            $result = '"oldValue":"' . $oldValue . '","newValue":"' . $newValue . '"';
            break;
        case 'added':
            $result = '"newValue":"' . $newValue . '"';
            break;
        case 'removed':
            $result = '"oldValue":"' . $oldValue . '"';
            break;
        case 'unchanged':
            $result = '"value":"' . $oldValue . '"';
            break;
        default:
            throw new Error('Unknown order state: in \Stylish\stringifyValue => $type = {$type}!');
            break;
    }
    return $result;
}

function iter(array $arr): string
{

    $listForeReduce = array_keys($arr);
    $lines = array_reduce($listForeReduce, function ($acc, $item) use ($arr) {
        $key = $arr[$item]['key'];
        $type = $arr[$item]['type'];
        $children = $arr[$item]['children'];
        $newValue = $arr[$item]['newValue'];
        $value = iter($arr[$item]['children']);
        if ($arr[$item]['type'] === 'nested') {
            $acc[] = '{"key":"' . $key . '","type":"' . $type . '","children":' . $value . '}';
        } else {
            if (is_object($newValue)) {
                $newValue = json_encode($newValue);
            }
            $acc[] = '{"key":"' . $key . '","type":"' . $type . ',' . stringifyValue($arr[$item]) . '}';
        }
        return $acc;
    }, []);
    return implode(['[', ...$lines, ']']);
}

function displayJson(array $arr): string
{
    $result = str_replace('}{', '},{', iter($arr));
    return '{"type":"root","children":' . $result . '}';
}
