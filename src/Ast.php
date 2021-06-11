<?php

namespace Project\Package\Ast;

function makeStructure(array $values): array
{
    return [
        'key' => $values[0],
        'type' => $values[1],
        'oldValue' => $values[2],
        'newValue' => $values[3],
        'children' => $values[4]
    ];
}

function compareIter(object $beginObject = null, object $endObject = null): array
{
    $keysBeginObject = array_keys(get_object_vars($beginObject));
    $keysEndObject = array_keys(get_object_vars($endObject));
    $listForReduce = array_values(array_unique(array_merge($keysBeginObject, $keysEndObject)));

    sort($listForReduce);

    $ast = array_reduce($listForReduce, function ($acc, $key) use ($beginObject, $endObject) {

        $oldValue = $beginObject->$key ?? null;
        $newValue = $endObject->$key ?? null;

        if (is_object($oldValue) && is_object($newValue)) {
            $acc[] = makeStructure([$key, 'nested', null, null, compareIter($oldValue, $newValue)]);
        } elseif (!property_exists($beginObject, $key)) {
                $acc[] = makeStructure([$key, 'added', $oldValue, $newValue, []]);
        } elseif (!property_exists($endObject, $key)) {
                $acc[] = makeStructure([$key, 'removed', $oldValue, $newValue, []]);
        } elseif ($oldValue === $newValue) {
            $acc[] = makeStructure([$key, 'unchanged', $oldValue, $newValue, []]);
        } else {
            $acc[] = makeStructure([$key, 'replace', $oldValue, $newValue, []]);
        }
        return $acc;
    }, []);
    return $ast;
}
