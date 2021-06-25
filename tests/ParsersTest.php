<?php

namespace Differ\Phpunit\Tests\ParsersTest;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

use function Differ\Parsers\parserFile;

class ParsersTest extends TestCase
{
    public function setUp(): void
    {
        $this->pathToBeginFlatJson = 'tests/fixtures/flatFixtures/beginFileJson.json';
        $this->pathToEndFlatYaml = 'tests/fixtures/flatFixtures/endFileYaml.yaml';
    }

    public function testParsersFile()
    {
        $expected = json_decode(file_get_contents($this->pathToBeginFlatJson));
        $this->assertEquals($expected, parserFile($this->pathToBeginFlatJson, 'json'));
        $this->assertTrue(is_object(parserFile($this->pathToBeginFlatJson, 'json')));

        $expected1 = Yaml::parse(file_get_contents($this->pathToEndFlatYaml), Yaml::PARSE_OBJECT_FOR_MAP);
        $this->assertEquals($expected1, parserFile($this->pathToEndFlatYaml, 'yaml'));
        $this->assertTrue(is_object(parserFile($this->pathToEndFlatYaml, 'yaml')));
    }
}
