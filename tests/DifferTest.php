<?php

namespace Differ\Phpunit\Tests\Differ;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testGenDiff()
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
        $this->assertEquals($expected, genDiff($ast1, 'stylish'));

        $expected2 = "Property '' was added with value: 'newValue'" . PHP_EOL .  "Property '' was removed";
        $this->assertEquals($expected2, genDiff($ast1, 'plain'));

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
        $this->assertEquals($exprcted3, genDiff($ast1, 'json'));
    }
}
