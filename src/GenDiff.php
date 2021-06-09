<?php

namespace Project\Package\GenDiff;

use Symfony\Component\Yaml\Yaml;
use Docopt;

use function Project\Package\Parsers\parserFile;
use function Project\Package\Viewer\getViewFlat;

function genDiff(string $path1, string $path2, string $formatName = 'stylish'): string
{
    return getViewFlat(parserFile($path1), parserFile($path2));
}

function run(): string
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
    $args = Docopt::handle($doc, array('version' => 'GenDiff 2.0'));

    $path1 = $args['<firstFile>'];
    $path2 = $args['<secondFile>'];
    // $stylish = $args['<fmt>'];

    echo genDiff($path1, $path2);
    return genDiff($path1, $path2);
}
