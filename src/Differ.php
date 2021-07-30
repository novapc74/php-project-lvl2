<?php

namespace Differ\Differ;

use function Differ\Parsers\parserFile;
use function Differ\AstFormatter\compareIter;
use function Differ\Formatter\chooseFormat;

function genDiff(string $firstFilePath, string $secondFilePath, string $styleString = 'stylish'): string
{
    try {
        $firstFileContent = (string)(file_get_contents($firstFilePath));
        $extensionFirstFile = pathinfo($firstFilePath, PATHINFO_EXTENSION);

        $secondFileContent = (string)(file_get_contents($secondFilePath));
        $extensionSecondFile = pathinfo($secondFilePath, PATHINFO_EXTENSION);

        $firstObject = parserFile($firstFileContent, $extensionFirstFile);
        $secondObject = parserFile($secondFileContent, $extensionSecondFile);
        $astFormat = compareIter($firstObject, $secondObject);
        return chooseFormat($astFormat, $styleString);
    } catch (\Exception $e) {
        echo 'An exception thrown: ',  $e->getMessage(), PHP_EOL;
    }
}
