<?php

namespace Differ\Phpunit\Tests\GenDiffTest;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;
use stdClass;

// use function Differ\Parsers\parserFile;
// use function Differ\Parsers\isFileYaml;

// class GenDiffTest extends TestCase
// {
//     public function setUp(): void
//     {
//         $this->pathToBeginFlatJson = 'tests/fixtures/flatFixtures/beginFileJson.json';
//         $this->pathToEndFlatJson = 'tests/fixtures/flatFixtures/endFileJson.json';
//         $this->pathToBeginFlatYaml = 'tests/fixtures/flatFixtures/beginYFileaml.yaml';
//         $this->pathToEndFlatYaml = 'tests/fixtures/flatFixtures/endFileYaml.yaml';
//         $this->pathToBeginTreeJson = 'tests/fixtures/treeFixtures/beginFile.json';
//         $this->pathToEndTreeJson = 'tests/fixtures/treeFixtures/endFile.json';
//         $this->pathToBeginTreeYaml = 'tests/fixtures/treeFixtures/beginFile.yaml';
//         $this->pathToEndTreeYaml = 'tests/fixtures/treeFixtures/endFile.yaml';

//         $this->testArray = [
//             'key' => 'key',
//             'type' => 'replace',
//             'oldValue' => 'oldValue',
//             'newValue' => 'newValue',
//             'children' => [],
//             ];
//         $this->testArray2 = [
//             'key' => 'common',
//             'type' => 'replace',
//             'oldValue' => 'oldValue',
//             'newValue' => 'newValue',
//             'children' => [],
//             ];
//         $this->beginObject = json_decode(file_get_contents($this->pathToBeginTreeJson));
//         $this->endObject = json_decode(file_get_contents($this->pathToEndTreeJson));
//     }

//     public function testParserFile()
//     {
//         $this->assertTrue(is_object(parserFile($this->pathToBeginFlatJson)));
//         $this->assertTrue(is_object(parserFile($this->pathToEndFlatYaml)));
//     }

//     public function testIsFileYaml()
//     {
//         $fileNameYaml = 'test.yaml';
//         $fileNameYml = 'test.yml';
//         $fileNameJson = 'test.json';
//         $this->assertEquals(1, isFileYaml($fileNameYaml));
//         $this->assertEquals(1, isFileYaml($fileNameYml));
//         $this->assertEquals(0, isFileYaml($fileNameJson));
//     }
// }
