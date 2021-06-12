<?php

namespace Project\Phpunit\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;
use stdClass;

use function Project\Package\Parsers\parserFile;
use function Project\Package\Parsers\isFileYaml;

use function Project\Package\Ast\makeStructure;
use function Project\Package\Ast\compareIter;

use function Project\Package\Formatters\Stylish\toString;
use function Project\Package\Formatters\Stylish\stringifyValue;
use function Project\Package\Formatters\Stylish\convertObject;
use function Project\Package\Formatters\Stylish\stringify;
use function Project\Package\Formatters\Stylish\displayStylish;

use function Project\Package\GenDiff\genDiff;

use function Project\Package\Formatters\selectFormat;

use function Project\Package\Formatters\Plain\makeString;
use function Project\Package\Formatters\Plain\displayPlain;

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

    public function testToString()
    {
        $nested = true;
        $expected = 'true';
        $this->assertEquals($expected, toString($nested));
        $nested = false;
        $expected = 'false';
        $this->assertEquals($expected, toString($nested));
        $nested = null;
        $expected = 'null';
        $this->assertEquals($expected, toString($nested));
        $nested = 50.45;
        $expected = '50.45';
        $this->assertEquals($expected, toString($nested));
    }

    public function testStringifyValue()
    {
        $expected = '- key: oldValue' . PHP_EOL;
        $expected .= '+ key: newValue';
        $this->assertEquals($expected, stringifyValue($this->testArray, ''));
    }
    public function testConvertObject()
    {
        $object = json_decode(file_get_contents($this->pathToBeginFlatJson));
        $object1 = Yaml::parse(file_get_contents($this->pathToBeginTreeYaml), Yaml::PARSE_OBJECT_FOR_MAP);
        $this->assertTrue(is_array(convertObject($object)));
        $this->assertTrue(is_array(convertObject($object1)));
    }
    public function testStryngify()
    {
        $this->assertTrue(is_string(stringify($this->testArray)));
    }

    public function testDisplayStylish()
    {
        $expected = '{
  - key: oldValue
  + key: newValue
}';
        $this->assertEquals($expected, displayStylish([$this->testArray]));
    }

    public function testMakeStructure()
    {
        $values = ['key', 'replace', 'oldValue', 'newValue' , []];
        $this->assertEquals($this->testArray, makeStructure($values));
    }

    public function testCompareIter()
    {
        $beginObject = new stdClass();
        $beginObject->common = 'oldValue';
        $endObject = new stdClass();
        $endObject->common = 'newValue';
        $arrTest = [$this->testArray2];
        $this->assertEquals($arrTest, compareIter($beginObject, $endObject));
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
    }

    public function testMakeString()
    {
        $arr = $this->testArray;
        $parent = 'parent.';
        $expected = "'parent.key' was updated. From oldValue to newValue";
        $this->assertEquals($expected, makeString($arr, $parent));
    }

    public function testDisplayPlain()
    {
        $arrTest = [$this->testArray2];
        $this->assertTrue(is_string(displayPlain($arrTest)));
    }
}
