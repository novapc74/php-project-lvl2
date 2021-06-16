<?php

namespace Project\Phpunit\Tests\GenDiffTest;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;
use stdClass;

use function Project\Package\Parsers\parserFile;
use function Project\Package\Parsers\isFileYaml;

use function Project\Package\GenDiff\genDiff;

use function Project\Package\Formatters\selectFormat;

class GenDiffTest extends TestCase
{
    public function setUp(): void
    {
        $this->pathToBeginFlatJson = 'tests/fixtures/flatFixtures/beginFileJson.json';
        $this->pathToEndFlatJson = 'tests/fixtures/flatFixtures/endFileJson.json';
        $this->pathToBeginFlatYaml = 'tests/fixtures/flatFixtures/beginYFileaml.yaml';
        $this->pathToEndFlatYaml = 'tests/fixtures/flatFixtures/endFileYaml.yaml';
        $this->pathToBeginTreeJson = 'tests/fixtures/treeFixtures/beginFile.json';
        $this->pathToEndTreeJson = 'tests/fixtures/treeFixtures/endFile.json';
        $this->pathToBeginTreeYaml = 'tests/fixtures/treeFixtures/beginFile.yaml';
        $this->pathToEndTreeYaml = 'tests/fixtures/treeFixtures/endFile.yaml';

        $this->testArray = [
            'key' => 'key',
            'type' => 'replace',
            'oldValue' => 'oldValue',
            'newValue' => 'newValue',
            'children' => [],
            ];
        $this->testArray2 = [
            'key' => 'common',
            'type' => 'replace',
            'oldValue' => 'oldValue',
            'newValue' => 'newValue',
            'children' => [],
            ];
        $this->beginObject = json_decode(file_get_contents($this->pathToBeginTreeJson));
        $this->endObject = json_decode(file_get_contents($this->pathToEndTreeJson));
    }

    public function testParserFile()
    {
        $this->assertTrue(is_object(parserFile($this->pathToBeginFlatJson)));
        $this->assertTrue(is_object(parserFile($this->pathToEndFlatYaml)));
    }

    public function testIsFileYaml()
    {
        $fileNameYaml = 'test.yaml';
        $fileNameYml = 'test.yml';
        $fileNameJson = 'test.json';
        $this->assertEquals(1, isFileYaml($fileNameYaml));
        $this->assertEquals(1, isFileYaml($fileNameYml));
        $this->assertEquals(0, isFileYaml($fileNameJson));
    }



    public function testGenDiff()
    {
        $beginPath = 'tests/fixtures/simpleFixtures/first.json';
        $endPath = 'tests/fixtures/simpleFixtures/second.json';
        $this->assertTrue(is_string(genDiff($beginPath, $endPath, 'stylish')));
    }
    public function testSelectFormat()
    {
        $path1 = $this->pathToBeginFlatJson;
        $path2 = $this->pathToEndFlatJson;
        $this->assertTrue(is_string(selectFormat($path1, $path2, 'stylish')));
        $this->assertTrue(is_string(selectFormat($path1, $path2, 'plain')));
        $this->assertTrue(is_string(selectFormat($path1, $path2, 'json')));
    }
}
