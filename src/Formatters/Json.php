<?php

namespace Differ\Formatters\Json;

use Symfony\Component\Yaml\Yaml;

function makeString(array $arr): string
{
    $str = Yaml::dump($arr);
    $strToArr = explode(PHP_EOL, $str);
    $filterData = array_filter($strToArr, fn ($item): bool => $item !== 'children: {  }' && $item !== '');
    $dataForOutput = array_map(fn ($item) => explode(': ', $item), $filterData);
    $outputArr = array_map(function ($item): string {
        $test = '"' . $item[0] . '": ';
        $test2 = str_replace("'", '', $item[1]);
        if ($test2 == 'false' || $test2 == 'true' || $test2 == 'null' || ctype_digit($test2)) {
            return "{$test}{$test2}";
        } else {
            $test3 = '"' . str_replace("'", '', $item[1]) . '"';
            return "{$test}{$test3}";
        }
    }, $dataForOutput);

    // if (is_object($arr['oldValue'])) {
    //     $oldValue = Yaml::dump($arr['oldValue'], 1, 1, Yaml::DUMP_OBJECT_AS_MAP);
    //     $oldValue = Yaml::parse($oldValue);
    // }
    // if (is_object($arr['newValue'])) {
    //     $newValue = Yaml::dump($arr['newValue'], 1, 1, Yaml::DUMP_OBJECT_AS_MAP);
    //     $newValue = Yaml::parse($newValue);
    // }
    return "{$outputArr[0]},{$outputArr[1]},{$outputArr[2]},{$outputArr[3]}";
}

function iter(array $arr): string
{
    $listForMap = array_keys($arr);
    $lines = array_map(function ($item) use ($arr): string {
        if ($arr[$item]['type'] === 'nested') {
            return '{"key":"' . $arr[$item]['key'] . '","type":"' .
                $arr[$item]['type'] . '","children":' . iter($arr[$item]['children']) . '}';
        } else {
            return '{' . makeString($arr[$item]) . '}';
        }
    }, $listForMap);
    return implode('', ["[", ...$lines, "]"]);
}

function displayJson(array $arr): string
{
    return '{"type":"root","children":' . str_replace('}{', '},{', iter($arr)) . '}';
}
