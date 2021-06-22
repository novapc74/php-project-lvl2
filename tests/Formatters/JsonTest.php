<?php

namespace Differ\Phpunit\Tests\JsonTest;

use PHPUnit\Framework\TestCase;
use function Differ\Formatters\Json\displayJson;

class JsonTest extends TestCase
{
    public function setUp(): void
    {
        $this->structure = '{
    "key": "common",
    "type": "replace",
    "oldValue": "oldValue",
    "newValue": "newValue",
    "children": []
}
';
        $this->arrTest = [
            'key' => 'common',
            'type' => 'replace',
            'oldValue' => 'oldValue',
            'newValue' => 'newValue',
            'children' => [],
            ];
    }
    public function testDisplayJson()
    {
        $arrTest = [
                    'key' => 'common',
                    'type' => 'replace',
                    'oldValue' => 'oldValue',
                    'newValue' => 'newValue',
                    'children' => [],
                    ];
        // print_r(displayJson($arrTest));
        $expected ='"common"
"replace"
"oldValue"
"newValue"
[]';
        $this->assertEquals($expected, displayJson($arrTest));
    }
}
