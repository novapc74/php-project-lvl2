<?php

namespace Differ\Ast;

function compareIter(object $beginObject, object $endObject): array
{
    $keysBeginObject = array_keys(get_object_vars($beginObject));
    $keysEndObject = array_keys(get_object_vars($endObject));
    $listForReduce = array_unique(array_merge($keysBeginObject, $keysEndObject));

    // sort($listForReduce, SORT_STRING);

    uksort($listForReduce, function ($a, $b) {
        if (is_int($a) && is_int($b)) {
            return $a - $b;
        }
        if (is_int($a) && !is_int($b)) {
            return 1;
        }
        if (!is_int($a) && is_int($b)) {
            return -1;
        }
        return strnatcasecmp($a, $b);
    });

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
