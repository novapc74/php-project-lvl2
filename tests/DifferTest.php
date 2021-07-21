<?php

namespace Differ\Phpunit\Tests\Differ;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testGenDiff()
    {
        $beginJson = 'tests/fixtures/beginFile.json';
        $endJson = 'tests/fixtures/endFile.json';

        $beginYaml = 'tests/fixtures/beginFile.yaml';
        $endYaml = 'tests/fixtures/endFile.yaml';

        $expected = trim(file_get_contents('tests/fixtures/plainDiff'));
        $this->assertEquals($expected, genDiff($beginJson, $endJson, 'plain'));

        $expected = trim(file_get_contents('tests/fixtures/stylishDiff'));
        $this->assertEquals($expected, genDiff($beginJson, $endJson, 'stylish'));

        $expected = trim(file_get_contents('tests/fixtures/jsonDiff'));
        $this->assertEquals($expected, genDiff($beginJson, $endJson, 'json'));

        $expected = trim(file_get_contents('tests/fixtures/stylishDiff'));
        $this->assertEquals($expected, genDiff($beginYaml, $endYaml, 'stylish'));
    }
}
