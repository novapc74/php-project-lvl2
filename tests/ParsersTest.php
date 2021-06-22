<?php

namespace Differ\Phpunit\Tests\ParsersTest;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;
use stdClass;

use function Differ\Parsers\parserFile;
use function Differ\Parsers\isFileYaml;

class ParsersTest extends TestCase
{
    public function setUp(): void
    {
        $this->pathToBeginFlatJson = 'tests/fixtures/flatFixtures/beginFileJson.json';
        $this->pathToEndFlatYaml = 'tests/fixtures/flatFixtures/endFileYaml.yaml';
        $this->pathToBeginTreeJson = 'tests/fixtures/treeFixtures/beginFile.json';
        $this->pathToEndTreeJson = 'tests/fixtures/treeFixtures/endFile.json';
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
}
