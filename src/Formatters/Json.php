<?php

namespace Differ\Formatters\Json;

function displayJson(array $arr): string
{
    $listForReduce = array_keys($arr);
    $list = array_reduce($listForReduce, function ($acc, $item) use ($arr) {
        $acc[] = json_encode($arr[$item], JSON_PRETTY_PRINT);
        return $acc;
    }, []);
    return '[' . PHP_EOL . implode(PHP_EOL, [...$list]) . PHP_EOL . ']';
}
