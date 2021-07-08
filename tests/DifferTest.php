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

        $exprcted3 = '[
{
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
}
]';
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
Property 'group3' was added with value: [complex value]";
        $this->assertEquals($expected, genDiff($beginFilePath, $endFilePath, 'plain'));

        $expected2 = '{
    common: {
      + follow: false
        setting1: Value 1
      - setting2: 200
      - setting3: true
      + setting3: null
      + setting4: blah blah
      + setting5: {
            key5: value5
        }
        setting6: {
            doge: {
              - wow: 
              + wow: so much
            }
            key: value
          + ops: vops
        }
    }
    group1: {
      - baz: bas
      + baz: bars
        foo: bar
      - nest: {
            key: value
        }
      + nest: str
    }
  - group2: {
        abc: 12345
        deep: {
            id: 45
        }
    }
  + group3: {
        deep: {
            id: {
                number: 45
            }
        }
        fee: 100500
    }
}';
        $this->assertEquals($expected2, genDiff($beginFilePath, $endFilePath, 'stylish'));

                $expected3 = '[
{
    "key": "common",
    "type": "nested",
    "oldValue": null,
    "newValue": null,
    "children": [
        {
            "key": "follow",
            "type": "added",
            "oldValue": null,
            "newValue": false,
            "children": []
        },
        {
            "key": "setting1",
            "type": "unchanged",
            "oldValue": "Value 1",
            "newValue": "Value 1",
            "children": []
        },
        {
            "key": "setting2",
            "type": "removed",
            "oldValue": 200,
            "newValue": null,
            "children": []
        },
        {
            "key": "setting3",
            "type": "replace",
            "oldValue": true,
            "newValue": null,
            "children": []
        },
        {
            "key": "setting4",
            "type": "added",
            "oldValue": null,
            "newValue": "blah blah",
            "children": []
        },
        {
            "key": "setting5",
            "type": "added",
            "oldValue": null,
            "newValue": {
                "key5": "value5"
            },
            "children": []
        },
        {
            "key": "setting6",
            "type": "nested",
            "oldValue": null,
            "newValue": null,
            "children": [
                {
                    "key": "doge",
                    "type": "nested",
                    "oldValue": null,
                    "newValue": null,
                    "children": [
                        {
                            "key": "wow",
                            "type": "replace",
                            "oldValue": "",
                            "newValue": "so much",
                            "children": []
                        }
                    ]
                },
                {
                    "key": "key",
                    "type": "unchanged",
                    "oldValue": "value",
                    "newValue": "value",
                    "children": []
                },
                {
                    "key": "ops",
                    "type": "added",
                    "oldValue": null,
                    "newValue": "vops",
                    "children": []
                }
            ]
        }
    ]
}
{
    "key": "group1",
    "type": "nested",
    "oldValue": null,
    "newValue": null,
    "children": [
        {
            "key": "baz",
            "type": "replace",
            "oldValue": "bas",
            "newValue": "bars",
            "children": []
        },
        {
            "key": "foo",
            "type": "unchanged",
            "oldValue": "bar",
            "newValue": "bar",
            "children": []
        },
        {
            "key": "nest",
            "type": "replace",
            "oldValue": {
                "key": "value"
            },
            "newValue": "str",
            "children": []
        }
    ]
}
{
    "key": "group2",
    "type": "removed",
    "oldValue": {
        "abc": 12345,
        "deep": {
            "id": 45
        }
    },
    "newValue": null,
    "children": []
}
{
    "key": "group3",
    "type": "added",
    "oldValue": null,
    "newValue": {
        "deep": {
            "id": {
                "number": 45
            }
        },
        "fee": 100500
    },
    "children": []
}
]';
        $this->assertEquals($expected3, genDiff($beginFilePath, $endFilePath, 'json'));
    }
}
