<?php

namespace Differ\Formatters;

use Docopt;

use function Differ\Ast\compareIter;
use function Differ\Parsers\parserFile;
use function Differ\Formatters\Stylish\displayStylish;
use function Differ\Formatters\Plain\displayPlain;
use function Differ\Formatters\Json\displayJson;

function selectFormat(string $path1, string $path2): string
{
    $doc = <<<DOC
    Generate diff

    Usage:
      genDiff [--format <fmt>] <firstFile> <secondFile>

    Options:
      --format <fmt>                Report format [default: stylish]
    DOC;
    $args = Docopt::handle($doc, array('version' => 'GenDiff 2.0'));
    $style = $args['--format'];

    $parserPath1 = parserFile($path1);
    $parserPath2 = parserFile($path2);
    if ($style === 'json') {
        return displayJson(compareIter($parserPath1, $parserPath2));
    } elseif ($style === 'plain') {
        return displayPlain(compareIter($parserPath1, $parserPath2));
    }
    return displayStylish(compareIter($parserPath1, $parserPath2));
}
