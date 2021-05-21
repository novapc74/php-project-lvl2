<?php

namespace Project\Package\Gendiff;

use Docopt;

function convertFileContent(array $contentFile): array
{
    $decodedFormat = json_decode(implode('', $contentFile), true);
    $formatedType = [];
    foreach ($decodedFormat as $key => $value) {
        if (gettype($decodedFormat[$key]) === 'boolean') {
            $decodedFormat[$key] ? $formatedType[$key] = 'true' : $formatedType[$key] = 'false';
        } else {
            $formatedType[$key] = $decodedFormat[$key];
        }
    }
    ksort($formatedType, SORT_STRING);
    return $formatedType;
}

function getOutFotmat(array $firstFile, array $secondFile): string
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

    $contentFirstFile = convertFileContent(file($args->args['<firstFile>']));
    $contentSecondFile = convertFileContent(file($args->args['<secondFile>']));
    print_r(getOutFotmat($contentFirstFile, $contentSecondFile));
}
