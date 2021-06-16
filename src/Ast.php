<?php

namespace Project\Package\Ast;

function compareIter(object $beginObject, object $endObject): array
{
    $keysBeginObject = array_keys(get_object_vars($beginObject));
    $keysEndObject = array_keys(get_object_vars($endObject));
    $listForReduce = array_values(array_unique(array_merge($keysBeginObject, $keysEndObject)));

    sort($listForReduce);

    $ast = array_reduce($listForReduce, function ($acc, $key) use ($beginObject, $endObject) {

        $oldValue = $beginObject->$key ?? null;
        $newValue = $endObject->$key ?? null;

        if (is_object($oldValue) && is_object($newValue)) {
            $acc[] = [
                'key' => $key,
                'type' => 'nested',
                'oldValue' => null,
                'newValue' => null,
                'children' => compareIter($oldValue, $newValue)
            ];
        } elseif (!property_exists($beginObject, $key)) {
            $acc[] = [
                'key' => $key,
                'type' => 'added',
                'oldValue' => $oldValue,
                'newValue' => $newValue,
                'children' => []
            ];
        } elseif (!property_exists($endObject, $key)) {
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
