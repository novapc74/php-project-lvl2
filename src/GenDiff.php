<?php

namespace Project\Package\GenDiff;

use Symfony\Component\Yaml\Yaml;
use Docopt;

use function Project\Package\Formatters\selectFormat;

function genDiff(string $path1, string $path2, string $format = 'stylish'): string
{
    return selectFormat($path1, $path2, $format) . PHP_EOL;
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
    $diff = genDiff($args['<firstFile>'], $args['<secondFile>'], $args['--format']);
    print_r($diff);
}
