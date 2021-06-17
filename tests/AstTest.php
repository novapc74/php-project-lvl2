<?php

namespace Differ\Phpunit\Tests\AstTest;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;
use stdClass;

use function Differ\Ast\makeStructure;
use function Differ\Ast\compareIter;

class AstTest extends TestCase
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

    public function testCompareIter()
    {
        $beginObject = new stdClass();
        $beginObject->common = 'oldValue';
        $endObject = new stdClass();
        $endObject->common = 'newValue';
        $arrTest = [$this->testArray2];
        $this->assertEquals($arrTest, compareIter($beginObject, $endObject));
    }
}
