<?php

namespace Differ\Phpunit\Tests\Differ;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testGenDiffDefault()
    {
        $this->firstJson = 'tests/fixtures/firstFile.json';
        $this->secondJson = 'tests/fixtures/secondFile.json';
        $this->firstYaml = 'tests/fixtures/firstFile.yaml';
        $this->secondYaml = 'tests/fixtures/secondFile.yaml';

        $this->resultStylish = trim(file_get_contents('tests/fixtures/resultStylishDiff'));

        $this->assertEquals($this->resultStylish, genDiff($this->firstJson, $this->secondJson));
        $this->assertEquals($this->resultStylish, genDiff($this->firstYaml, $this->secondYaml));
    }
    public function testGenDiffStylish()
    {
        $this->firstJson = 'tests/fixtures/firstFile.json';
        $this->secondJson = 'tests/fixtures/secondFile.json';
        $this->firstYaml = 'tests/fixtures/firstFile.yaml';
        $this->secondYaml = 'tests/fixtures/secondFile.yaml';

        $this->resultStylish = trim(file_get_contents('tests/fixtures/resultStylishDiff'));

        $this->assertEquals($this->resultStylish, genDiff($this->firstJson, $this->secondJson, 'stylish'));
        $this->assertEquals($this->resultStylish, genDiff($this->firstYaml, $this->secondYaml, 'stylish'));
    }

    public function testGenDiffPlain()
    {
        $this->firstJson = 'tests/fixtures/firstFile.json';
        $this->secondJson = 'tests/fixtures/secondFile.json';
        $this->firstYaml = 'tests/fixtures/firstFile.yaml';
        $this->secondYaml = 'tests/fixtures/secondFile.yaml';

        $this->resultPlain = trim(file_get_contents('tests/fixtures/resultPlainDiff'));

        $this->assertEquals($this->resultPlain, genDiff($this->firstJson, $this->secondJson, 'plain'));
        $this->assertEquals($this->resultPlain, genDiff($this->firstYaml, $this->secondYaml, 'plain'));
    }

    public function testGenDiffJson()
    {
        $this->firstJson = 'tests/fixtures/firstFile.json';
        $this->secondJson = 'tests/fixtures/secondFile.json';
        $this->firstYaml = 'tests/fixtures/firstFile.yaml';
        $this->secondYaml = 'tests/fixtures/secondFile.yaml';

        $this->resultJson = trim(file_get_contents('tests/fixtures/resultJsonDiff'));

        $this->assertEquals($this->resultJson, genDiff($this->firstJson, $this->secondJson, 'json'));
        $this->assertEquals($this->resultJson, genDiff($this->firstYaml, $this->secondYaml, 'json'));
    }
}
