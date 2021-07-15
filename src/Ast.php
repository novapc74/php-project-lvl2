<?php

namespace Differ\Ast;

function quickSort($array): array
{
    if (count($array) < 2) {
        return $array;
    }
    $left = [];
    $right = [];
    $pivotKey = key($array);
    $pivot = array_shift($array);
    $q = 0;
    $result = array_map(function ($item) use (&$left, &$right, &$q, $pivot): array {
        if ($item < $pivot) {
            $left[$q] = $item;
            $q++;
            return $left;
        } else {
            $right[$q] = $item;
            $q++;
            return $right;
        }
    }, $array);
    return array_merge(quickSort($left), array($pivotKey => $pivot), quickSort($right));
}

function compareIter(object $beginObject, object $endObject): array
{
    $keysBeginObject = array_keys(get_object_vars($beginObject));
    $keysEndObject = array_keys(get_object_vars($endObject));
    $listForMap = array_unique(array_merge($keysBeginObject, $keysEndObject));
    $key = quickSort($listForMap);
    $ast = array_map(function (string $key) use ($beginObject, $endObject): array {
        $oldValue = $beginObject->$key ?? null;
        $newValue = $endObject->$key ?? null;
        if (is_object($oldValue) && is_object($newValue)) {
                $type = 'nested';
                $children = compareIter($oldValue, $newValue);
        } elseif (!property_exists($beginObject, $key)) {
                $type = 'added';
        } elseif (!property_exists($endObject, $key)) {
                $type = 'removed';
        } elseif ($oldValue === $newValue) {
                $type = 'unchanged';
        } else {
                $type = 'replace';
        }
        return [
            'key' => $key,
            'type' => $type,
            'oldValue' => $oldValue,
            'newValue' => $newValue,
            'children' => $children ?? []
        ];
    }, $key);
    return $ast;
}
