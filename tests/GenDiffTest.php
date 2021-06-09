<?php

namespace Project\Phpunit\Tests;

use PHPUnit\Framework\TestCase;
use function Project\Package\Parsers\isFilesYaml;
use function Project\Package\Parsers\parserFile;
use function Project\Package\Viewer\getViewFlat;
use function Project\Package\Viewer\toString;
use function Project\Package\Viewer\stringify;
use function Project\Package\GenDiff\genDiff;
use function Project\Package\GenDiff\run;

class GenDiffTest extends TestCase
{
    private $beginFileJson;
    private $endFileJson;
    private $beginFileYaml;
    private $endFileYaml;
    private $flatResult;

    public function setUp(): void
    {
        $this->beginFileJson = file_get_contents('tests/fixtures/JSON1.json');
        $this->endFileJson = file_get_contents('tests/fixtures/JSON2.json');
        $this->beginFileYaml = file_get_contents('tests/fixtures/YAML1.yml');
        $this->endFileYaml = file_get_contents('tests/fixtures/YAML2.yml');
        $this->flatResult = file_get_contents('tests/fixtures/flatResult.txt');
    }

    public function testIsFilesYaml()
    {
        $this->assertEquals(true, isFilesYaml('file.yaml'));
        $this->assertEquals(true, isFilesYaml('file.yml'));
        $this->assertEquals(false, isFilesYaml('file.json'));
    }
////////////////// REMOVED Function....
    // public function testDecodeJsonFormat()
    // {
    //     $expected = [
    //         'host' => 'hexlet.io',
    //         'timeout' => 50,
    //         'proxy' => '123.234.53.22',
    //         'follow' => false,
    //     ];
    //     $this->assertTrue(is_array(DecodeJsonFormat($this->beginFileJson)));
    //     $this->assertEquals($expected, (DecodeJsonFormat($this->beginFileJson)));
    //     $this->assertEquals([], DecodeJsonFormat('{}'));
    // }

    public function testToString()
    {
        $nested = true;
        $expected = 'true';
        $this->assertEquals($expected, toString($nested));
    }

    public function getViewFlat()
    {
        $nested1 = [
            'follow' => 'false',
            'host' => 'hexlet.io',
            'proxy' => '123.234.53.22',
            'timeout' => '50',
            ];
        $nested2 = [
            'host' => 'hexlet.io',
            'timeout' => '20',
            'verbose' => 'true',
            ];

        $this->assertEquals('string', gettype(GetOutputFotmat($nested1, $nested2)));
        $this->assertEquals($this->flatResult, GetOutputFotmat($nested1, $nested2));
    }
    public function testParserFile()
    {
        $expected = [
            'host' => 'hexlet.io',
            'timeout' => '50',
            'proxy' => '123.234.53.22',
            'follow' => false,
            ];
        $test = parserFile($this->beginFileYaml);
        $this->assertEquals($expected, parserFile('tests/fixtures/YAML1.yml'));
    }

    public function testGenDiff()
    {
        $this->assertStringEqualsFile('tests/fixtures/flatResult.txt', genDiff('work/beginFile.json', 'work/endFile.json'));
    }
}
