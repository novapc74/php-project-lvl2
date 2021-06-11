<?php

namespace Project\Package\Stylish;

function toString($value): string
{
    if (is_null($value)) {
        return 'null';
    }
     return trim(var_export($value, true), "'");
}

function convertObject($object)
{
    if (is_object($object)) {
        return array_map(__FUNCTION__, get_object_vars($object));
    } elseif (is_array($object)) {
        return array_map(__FUNCTION__, $object);
    } else {
        return $object;
    }
}

function stringify(array $value, int $spacesCount = 1): string
{
    $iter = function ($currentValue, $depth) use (&$iter, $spacesCount) {
        if (!is_array($currentValue)) {
            return $currentValue;
        }
        $indentSize = $depth * $spacesCount;
        $currentIndent = str_repeat(' ', $indentSize + 4);
        $bracketIndent = str_repeat(' ', $indentSize);
        $lines = array_map(
            fn($key, $val) => "{$currentIndent}{$key}: {$iter($val, $depth + 1)}",
            array_keys($currentValue),
            $currentValue
        );
        $result = ['{', ...$lines, "{$bracketIndent}}"];
        return implode(PHP_EOL, $result);
    };
    return $iter($value, 1);
}

function stringifyValue(array $arr, string $nextIndent): string
{
    $key = $arr['key'];
    if (is_object($arr['oldValue'])) {
        $old = stringify(convertObject($arr['oldValue']), strlen($nextIndent) + 2);
        return '- ' . $key . ': ' . $old . $nextIndent;
    } elseif (is_object($arr['newValue'])) {
        $new = stringify(convertObject($arr['newValue']), strlen($nextIndent) + 2);
        return '+ ' . $key . ': ' . $new . $nextIndent;
    }
    $oldValue = toString($arr['oldValue']);
    $newValue = toString($arr['newValue']);
    switch ($arr['type']) {
        case 'replace':
            $result = '- ' . $key . ': ' . $oldValue . PHP_EOL;
            $result .= $nextIndent . '+ ' . $key . ': ' . $newValue;
            break;
        case 'added':
            $result = '+ ' . $key . ': ' . $newValue ;
            break;
        case 'removed':
            $result = '- ' . $key . ': ' . $oldValue ;
            break;
        case 'unchanged':
            $result = '  ' . $key . ': ' . $oldValue ;
            break;
    }
    return $result;
}

function displayResult(array $arr, int $depth = 1, int $spacesCount = 4): string
{
    $indentSize = $depth * $spacesCount;
    $currentIndent = str_repeat(' ', $indentSize);
    $nextIndent = str_repeat(' ', $indentSize - 2);
    $bracketIndent = str_repeat(' ', $indentSize - $spacesCount);
    $listForReduce = array_keys($arr);
    $lines = array_reduce($listForReduce, function ($acc, $item) use ($arr, $currentIndent, $nextIndent, $depth) {
        $key = $arr[$item]['key'];
        $value = displayResult($arr[$item]['children'], $depth + 1);
        if ($arr[$item]['type'] === 'nested') {
            $acc[] = $currentIndent . $key . ': ' . $value;
        } else {
            $acc[] = $nextIndent . stringifyValue($arr[$item], $nextIndent);
        }
        return $acc;
    }, []);
    $result = ['{', ...$lines, "{$bracketIndent}}"];
    return implode(PHP_EOL, $result);
}
