<?php

namespace Differ\Extension;

function isFileExtension(string $beginFilePath, string $endFilePath, array $listSupportExtension): string
{
    $beginFileExtension = pathinfo($beginFilePath, PATHINFO_EXTENSION);
    $endFileExtension = pathinfo($endFilePath, PATHINFO_EXTENSION);

    if ($beginFileExtension === $endFileExtension && in_array($beginFileExtension, $listSupportExtension)) {
        return $beginFileExtension;
    } else {
        throw new \Exception(PHP_EOL . "Error! File extensions don't match! Try again." . PHP_EOL);
    }
}
