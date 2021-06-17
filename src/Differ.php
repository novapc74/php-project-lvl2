<?php

namespace Differ\Differ;

use Symfony\Component\Yaml\Yaml;
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
    require_once __DIR__ . '/../vendor/docopt/docopt/src/docopt.php';
    $args = Docopt::handle($doc, array('version' => 'GenDiff 2.0'));
    $format = $args['--format'];
    $sormat = is_null($format) ? 'stylish' : $format;
    $diff = selectFormat($args['<firstFile>'], $args['<secondFile>'], $format);
    print_r($diff);
}
