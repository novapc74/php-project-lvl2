<?php

namespace Differ\Ast;

function compareIter(object $beginObject, object $endObject): array
{
    $keysBeginObject = array_keys(get_object_vars($beginObject));
    $keysEndObject = array_keys(get_object_vars($endObject));
    $listForReduce = array_unique(array_merge($keysBeginObject, $keysEndObject));
    sort($listForReduce, SORT_STRING);
    $ast = array_map(function ($key) use ($beginObject, $endObject): array {
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
    }, $listForReduce);
    return $ast;
}
