<?php

namespace Project\Package\GenDiff;

use Symfony\Component\Yaml\Yaml;
use Docopt;

use function Project\Package\Parser\parserFileYaml;

function isFilesYaml(string $nameFile): string
{
    $fileType = explode('.', $nameFile)[1];
    return $fileType == 'yaml' || $fileType == 'yml' ? true : false;
}

function decodeJsonFormat(string $contentFile): array
{
    return json_decode($contentFile, true);
}

function convertBooleanToString($arrayContentFile)
{
    $formatedType = [];
    foreach ($arrayContentFile as $key => $value) {
        if (gettype($arrayContentFile[$key]) === 'boolean') {
            $arrayContentFile[$key] ? $formatedType[$key] = 'true' : $formatedType[$key] = 'false';
        } else {
            $formatedType[$key] = $arrayContentFile[$key];
        }
    }
    ksort($formatedType, SORT_STRING);
    return $formatedType;
}

function getOutputFotmat(array $firstFile, array $secondFile): string
{
    $listKey = array_values(array_unique(array_merge(array_keys($firstFile), array_keys($secondFile))));
    $mainOutSts = array_reduce($listKey, function ($acc, $item) use ($firstFile, $secondFile) {
        switch ($item) {
            case (isset($firstFile[$item]) && isset($secondFile[$item]) && $firstFile[$item] === $secondFile[$item]):
                $acc .= "\n" . " " . $item . ": " . $firstFile[$item];
                break;
            case (isset($firstFile[$item]) && isset($secondFile[$item])):
                $acc .= "\n" . "-" . $item . ": " . $firstFile[$item] . "\n" . "+" . $item . ": " . $secondFile[$item];
                break;
            case (isset($firstFile[$item]) && !isset($secondFile[$item])):
                $acc .= "\n" . "-" . $item . ": " . $firstFile[$item];
                break;
            case (!isset($firstFile[$item]) && isset($secondFile[$item])):
                $acc .= "\n" . "+" . $item . ": " . $secondFile[$item];
                break;
        }
        return $acc;
    }, '');
    return ("{" . $mainOutSts . "\n" . "}" . "\n");
}

function genDiff(): void
{
    $doc = <<<DOC
    Generate diff

    Usage:
      gendiff (-h|--help)
      gendiff (-v|--version)
      gendiff [--format <fmt>] <firstFile> <secondFile>

    Options:
      -h --help                     Show this screen
      -v --version                  Show version
      --format <fmt>                Report format [default: stylish]

    DOC;

    require_once __DIR__ . '/../vendor/docopt/docopt/src/docopt.php';

    $args = Docopt::handle($doc, array('version' => 'Generate diff 2.0'));

    $typFile1 = isFilesYaml($args['<firstFile>']);
    $typFile2 = isFilesYaml($args['<secondFile>']);

    if (isFilesYaml($args['<firstFile>']) && isFilesYaml($args['<secondFile>'])) {
        $contentFirstFile = convertBooleanToString(parserFileYaml(file_get_contents($args['<firstFile>'])));
        $contentSecondFile = convertBooleanToString(parserFileYaml(file_get_contents($args['<secondFile>'])));
    } else {
        $contentFirstFile = convertBooleanToString(decodeJsonFormat(file_get_contents($args['<firstFile>'])));
        $contentSecondFile = convertBooleanToString(decodeJsonFormat(file_get_contents($args['<secondFile>'])));
    }
    echo getOutputFotmat($contentFirstFile, $contentSecondFile);
}
