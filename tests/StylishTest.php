<?php

namespace Project\Phpunit\Tests\StylishTest;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;
use function Project\Package\Formatters\Stylish\stringifyValue;
use function Project\Package\Formatters\Stylish\stringify;
use function Project\Package\Formatters\Stylish\displayStylish;

class StylishTest extends TestCase
{
    public function setUp(): void
    {
        $this->pathToBeginTreeYaml = 'tests/fixtures/treeFixtures/beginFile.yaml';
        $this->pathToBeginFlatJson = 'tests/fixtures/flatFixtures/beginFileJson.json';
        $this->testArray = [
            'key' => 'key',
            'type' => 'replace',
            'oldValue' => 'oldValue',
            'newValue' => 'newValue',
            'children' => [],
            ];
    }

    public function testStringifyValue()
    {
        $expected = '- key: oldValue' . PHP_EOL;
        $expected .= '+ key: newValue';
        $this->assertEquals($expected, stringifyValue($this->testArray, ''));
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
}
