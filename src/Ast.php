<?php

namespace Differ\Ast;

function compareIter(object $beginObject, object $endObject): array
{
    $keysBeginObject = array_keys(get_object_vars($beginObject));
    $keysEndObject = array_keys(get_object_vars($endObject));
    $listForReduce = array_unique(array_merge($keysBeginObject, $keysEndObject));

    sort($listForReduce, SORT_STRING);
    $begin = $beginObject;
    $end = $endObject;

    $ast = array_reduce($listForReduce, function ($acc, $key) use ($begin, $end) {

        $oldValue = $begin->$key ?? null;
        $newValue = $end->$key ?? null;

        if (is_object($oldValue) && is_object($newValue)) {
            $acc[] = [
                'key' => $key,
                'type' => 'nested',
                'oldValue' => null,
                'newValue' => null,
                'children' => compareIter($oldValue, $newValue)
            ];
        } elseif (!property_exists($begin, $key)) {
            $acc[] = [
                'key' => $key,
                'type' => 'added',
                'oldValue' => $oldValue,
                'newValue' => $newValue,
                'children' => []
            ];
        } elseif (!property_exists($end, $key)) {
            $acc[] = [
                'key' => $key,
                'type' => 'removed',
                'oldValue' => $oldValue,
                'newValue' => $newValue,
                'children' => []
            ];
        } elseif ($oldValue === $newValue) {
            $acc[] = [
                'key' => $key,
                'type' => 'unchanged',
                'oldValue' => $oldValue,
                'newValue' => $newValue,
                'children' => []
            ];
        } else {
            $acc[] = [
                'key' => $key,
                'type' => 'replace',
                'oldValue' => $oldValue,
                'newValue' => $newValue,
                'children' => []
            ];
        }
        return $acc;
    }, []);
    return $ast;
}
