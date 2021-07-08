<?php

namespace Differ\Phpunit\Tests\Differ;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\getDiff;
use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testGetDiff()
    {

        $ast1 = [
                [
            'key' => "",
            'type' => 'added',
            'oldValue' => 'oldValue',
            'newValue' => 'newValue',
            'children' => []
                ],
                [
                'key' => "",
                'type' => 'removed',
                'oldValue' => 'oldValue',
                'newValue' => 'newValue',
                'children' => []
                ]
            ];
        $expected ='{
  + : newValue
  - : oldValue
}';
        $this->assertEquals($expected, getDiff($ast1, 'stylish'));

        $expected2 = "Property '' was added with value: 'newValue'" . PHP_EOL .  "Property '' was removed";

        $this->assertEquals($expected2, getDiff($ast1, 'plain'));

        $exprcted3 = '{
    "key": "",
    "type": "added",
    "oldValue": "oldValue",
    "newValue": "newValue",
    "children": []
}
{
    "key": "",
    "type": "removed",
    "oldValue": "oldValue",
    "newValue": "newValue",
    "children": []
}';
        $this->assertEquals($exprcted3, getDiff($ast1, 'json'));
    }

    public function testGenDiff()
    {
        $beginFilePath = 'tests/fixtures/treeFixtures/beginFile.json';
        $endFilePath = 'tests/fixtures/treeFixtures/endFile.json';
        $expected = "Property 'common.follow' was added with value: false
Property 'common.setting2' was removed
Property 'common.setting3' was updated. From true to null
Property 'common.setting4' was added with value: 'blah blah'
Property 'common.setting5' was added with value: [complex value]
Property 'common.setting6.doge.wow' was updated. From '' to 'so much'
Property 'common.setting6.ops' was added with value: 'vops'
Property 'group1.baz' was updated. From 'bas' to 'bars'
Property 'group1.nest' was updated. From [complex value] to 'str'
Property 'group2' was removed
Property 'group3' was added with value: [complex value]
";
        $this->assertEquals($expected, genDiff($beginFilePath, $endFilePath, 'plain'));
    }
}
