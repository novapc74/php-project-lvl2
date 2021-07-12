<?php

namespace Differ\Phpunit\Tests\StylishTest;

use PHPUnit\Framework\TestCase;

use function Differ\Formatters\Stylish\makeString;
use function Differ\Formatters\Stylish\stringify;
use function Differ\Formatters\Stylish\displayStylish;

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

    public function testMakeString()
    {
        $expected = '- key: oldValue' . PHP_EOL;
        $expected .= '+ key: newValue';
        $this->assertEquals($expected, makeString($this->testArray, ''));
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
