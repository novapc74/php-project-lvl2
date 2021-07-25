<?php

namespace Differ\Phpunit\Tests\Differ;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    private $firstJson;
    private $secondJson;
    private $firstYaml;
    private $secondYaml;
    private $resultStylish;
    private $resultPlain;
    private $resultJson;

    public function setUp(): void
    {
        $this->firstJson = 'tests/fixtures/firstFile.json';
        $this->secondJson = 'tests/fixtures/secondFile.json';

        $this->firstYaml = 'tests/fixtures/firstFile.yaml';
        $this->secondYaml = 'tests/fixtures/secondFile.yaml';

        $this->resultStylish = trim(file_get_contents('tests/fixtures/resultStylishDiff'));
        $this->resultPlain = trim(file_get_contents('tests/fixtures/resultPlainDiff'));
        $this->resultJson = trim(file_get_contents('tests/fixtures/resultJsonDiff'));
    }

    public function testGenDiffStylish()
    {
        $this->assertEquals($this->resultStylish, genDiff($this->firstJson, $this->secondJson, 'stylish'));
        $this->assertEquals($this->resultStylish, genDiff($this->firstYaml, $this->secondYaml, 'stylish'));
    }

    public function testGenDiffPlain()
    {
        $this->assertEquals($this->resultPlain, genDiff($this->firstJson, $this->secondJson, 'plain'));
        $this->assertEquals($this->resultPlain, genDiff($this->firstYaml, $this->secondYaml, 'plain'));
    }

    public function testGenDiffJson()
    {
        $this->assertEquals($this->resultJson, genDiff($this->firstJson, $this->secondJson, 'json'));
        $this->assertEquals($this->resultJson, genDiff($this->firstYaml, $this->secondYaml, 'json'));
    }
}
