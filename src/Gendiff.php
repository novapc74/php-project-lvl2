<?php

namespace Project\Package\Gendiff;

use Docopt;

function run(): void
{
    $doc = <<<DOC
    Generate diff

    Usage:
      gendiff (-h|--help)
      gendiff (-v|--version)

    Options:
      -h --help                     Show this screen
      -v --version                  Show version

    DOC;
            require_once __DIR__ . '/docopt.php';
        $args = Docopt::handle($doc, array('version'=>'Generate diff 1.0'));
    foreach ($args as $k => $v)
        echo $k.': '.json_encode($v).PHP_EOL;
}





