<?php

namespace Differ\Differ;

use Docopt;

use function Differ\Formatters\selectFormat;

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
    $args = Docopt::handle($doc, array('version' => 'GenDiff 2.0'));
    $diff = selectFormat($args['<firstFile>'], $args['<secondFile>']);
    print_r($diff);
    echo PHP_EOL;
}
