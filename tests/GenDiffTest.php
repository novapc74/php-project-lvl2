<?php

namespace Project\Phpunit\Tests;

use PHPUnit\Framework\TestCase;
use function Project\Package\Parsers\parserFileYaml;
use function Project\Package\GenDiff\isFilesYaml;
use function Project\Package\GenDiff\decodeJsonFormat;
use function Project\Package\GenDiff\convertBooleanToString;
use function Project\Package\GenDiff\getOutputFotmat;

class GenDiffTest extends TestCase
{
    private $beginFileJson;
    private $endFileJson;
    private $beginFileYaml;
    private $endFileYaml;

    public function setUp(): void
    {
        $this->beginFileJson = file_get_contents('tests/fixtures/JSON1.json');
        $this->endFileJson = file_get_contents('tests/fixtures/JSON2.json');
        $this->beginFileYaml = file_get_contents('tests/fixtures/YAML1.yml');
        $this->endFileYaml = file_get_contents('tests/fixtures/YAML2.yml');
    }
    // public function getFixtureFullPath($fixtureName)
    // {
    //     $parts = [__DIR__, 'tests/fixtures/', $fixtureName];
    //     return realpath(implode('/', $parts));
    // }

    // public function comparisonFixtures($pathToJson1, $pathToJson2): void
    // {
    //     // пока пустая функция
    //     // $pathToJson1 = getFixtureFullPath('file1.json');
    //     // $pathToJson2 = getFixtureFullPath('file2.json');
    // }
    public function testIsFilesYaml()
    {
        $this->assertEquals(true, isFilesYaml('file.yaml'));
        $this->assertEquals(true, isFilesYaml('file.yml'));
        $this->assertEquals(false, isFilesYaml('file.json'));
    }

    public function testDecodeJsonFormat()
    {
        $expected = [
            'host' => 'hexlet.io',
            'timeout' => 50,
            'proxy' => '123.234.53.22',
            'follow' => false,
        ];
        $this->assertTrue(is_array(DecodeJsonFormat($this->beginFileJson)));
        $this->assertEquals($expected, (DecodeJsonFormat($this->beginFileJson)));
        $this->assertEquals([], DecodeJsonFormat('{}'));
    }

    public function testConvertBooleanToString()
    {
        $nested = [
            'host' => 'hexlet.io',
            'timeout' => true,
            'proxy' => '123.234.53.22',
            'follow' => false,
        ];
        $expected = [
            'host' => 'hexlet.io',
            'timeout' => 'true',
            'proxy' => '123.234.53.22',
            'follow' => 'false',
        ];
        $this->assertEquals($expected, convertBooleanToString($nested));
    }

    public function testGetOutputFotmat()
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
        $expected ='{
-follow: false
 host: hexlet.io
-proxy: 123.234.53.22
-timeout: 50
+timeout: 20
+verbose: true
}
';
        $this->assertEquals('string', gettype(GetOutputFotmat($nested1, $nested2)));
        $this->assertEquals($expected, GetOutputFotmat($nested1, $nested2));
    }
    public function testParserFileYaml()
    {
        $expected = [
            'follow' => false,
            'host' => 'hexlet.io',
            'proxy' => '123.234.53.22',
            'timeout' => '50',
            ];
        $this->assertEquals($expected, parserFileYaml($this->beginFileYaml));
    }
}
