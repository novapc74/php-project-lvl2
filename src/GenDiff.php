<?php

namespace Project\Package\GenDiff;

use Symfony\Component\Yaml\Yaml;
use Docopt;

// use function Project\Package\Parsers\parserFile;
// use function Project\Package\Ast\compareIter;
// use function Project\Package\Formatters\Stylish\displayResult;
use function Project\Package\Formatters\selectFormat;

function genDiff(string $path1, string $path2, string $format = 'stylish'): string
{
    return selectFormat($path1, $path2, $format);
}

function run(): void
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
    $style = $args['--format'];
    $diff = genDiff($path1, $path2, $style);
    print_r($diff);
}
