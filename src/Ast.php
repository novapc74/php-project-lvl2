<?php

namespace Differ\AstFormatter;

function compareIter(object $firstObject, object $secondObject): array
{
    $keysBeginObject = array_keys(get_object_vars($firstObject));
    $keysEndObject = array_keys(get_object_vars($secondObject));
    $listForMap = array_unique(array_merge($keysBeginObject, $keysEndObject));
    $collection = collect($listForMap);
    $sortKey = $collection->sort();
    $key = $sortKey->values()->all();
    $ast = array_map(function (string $key) use ($firstObject, $secondObject): array {
        $oldValue = $firstObject->$key ?? null;
        $newValue = $secondObject->$key ?? null;
        if (is_object($oldValue) && is_object($newValue)) {
                $type = 'nested';
                $children = compareIter($oldValue, $newValue);
        } elseif (!property_exists($firstObject, $key)) {
                $type = 'added';
        } elseif (!property_exists($secondObject, $key)) {
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
