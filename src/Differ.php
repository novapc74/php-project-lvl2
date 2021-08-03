<?php

namespace Differ\Differ;

use function Differ\Parsers\parserFile;
use function Differ\Formatter\chooseFormat;

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

function genDiff(string $firstFilePath, string $secondFilePath, string $styleString = 'stylish'): string
{
        $firstFileContent = (string)(file_get_contents($firstFilePath));
        $extensionFirstFile = pathinfo($firstFilePath, PATHINFO_EXTENSION);
        $secondFileContent = (string)(file_get_contents($secondFilePath));
        $extensionSecondFile = pathinfo($secondFilePath, PATHINFO_EXTENSION);
        $firstObject = parserFile($firstFileContent, $extensionFirstFile);
        $secondObject = parserFile($secondFileContent, $extensionSecondFile);
        $astFormat = compareIter($firstObject, $secondObject);
        return chooseFormat($astFormat, $styleString);
}
