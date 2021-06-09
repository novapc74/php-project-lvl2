<?php

namespace Project\Package\Viewer;

function toString($value)
{
     return trim(var_export($value, true), "'");
}

function getViewFlat(object $firstFile, object $secondFile): string
{
    $listKey =  array_merge(array_keys(get_object_vars($firstFile)), array_keys(get_object_vars($secondFile)));
    $listKey =  array_unique($listKey);
    $listKey =  array_values($listKey);
    sort($listKey);
    $mainOutSts = array_reduce($listKey, function ($acc, $item) use ($firstFile, $secondFile) {
            $oldValue = $firstFile->$item ?? null;
            $newValue = $secondFile->$item ?? null;
            $oldValue = toString($oldValue);
            $newValue = toString($newValue);
        switch ($item) {
            case (property_exists($firstFile, $item) && property_exists($secondFile, $item) && $oldValue === $newValue):
                $acc .= "\n" . " " . $item . ": " . $oldValue;
                break;
            case (property_exists($firstFile, $item) && property_exists($secondFile, $item)):
                $acc .= "\n" . "-" . $item . ": " . $oldValue . "\n" . "+" . $item . ": " . $newValue;
                break;
            case (property_exists($firstFile, $item) && !property_exists($secondFile, $item)):
                $acc .= "\n" . "-" . $item . ": " . $oldValue;
                break;
            case (!property_exists($firstFile, $item) && property_exists($secondFile, $item)):
                $acc .= "\n" . "+" . $item . ": " . $newValue;
                break;
        }
        return $acc;
    }, '');
    return ("{" . $mainOutSts . "\n" . "}" . "\n");
}

function stringify($value, int $spacesCount = 1): string
{
    $iter = function ($currentValue, $depth) use (&$iter, $spacesCount) {
        if (!is_array($currentValue)) {
            return toString($currentValue);
        }

        $indentSize = $depth * $spacesCount;
        $currentIndent = str_repeat(' ', $indentSize);
        $bracketIndent = str_repeat(' ', $indentSize - $spacesCount);
        $lines = array_map(
            fn($key, $val) => "{$currentIndent}{$key}: {$iter($val, $depth + 1)}",
            array_keys($currentValue),
            $currentValue
        );
        $result = ['{', ...$lines, "{$bracketIndent}}"];
        return implode("\n", $result);
    };
    return $iter($value, 1);
}
