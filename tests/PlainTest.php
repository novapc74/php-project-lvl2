<?php

namespace Project\Phpunit\Tests\PlainTest;

use PHPUnit\Framework\TestCase;
use function Project\Package\Formatters\Plain\displayPlain;
use function Project\Package\Formatters\Plain\makeString;

class PlainTest extends TestCase
{
    public function setUp(): void
    {
        $this->testArray = [
            'key' => 'key',
            'type' => 'replace',
            'oldValue' => 'oldValue',
            'newValue' => 'newValue',
            'children' => [],
            ];
        $this->testArray2 = [
            'key' => 'common',
            'type' => 'replace',
            'oldValue' => 'oldValue',
            'newValue' => 'newValue',
            'children' => [],
            ];
    }
    public function testMakeString()
    {
        $arr = $this->testArray;
        $parent = 'parent';
        $expected = "Property 'parent.key' was updated. From 'oldValue' to 'newValue'";
        $this->assertEquals($expected, makeString($arr, $parent));
    }

    public function testDisplayPlain()
    {
        $arrTest = [$this->testArray2];
        $this->assertTrue(is_string(displayPlain($arrTest)));
    }
}
