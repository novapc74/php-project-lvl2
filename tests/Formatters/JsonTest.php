<?php

namespace Differ\Phpunit\Tests\JsonTest;

use PHPUnit\Framework\TestCase;

use function Differ\Formatters\Json\displayJson;
use function Differ\Formatters\Json\iter;
use function Differ\Formatters\Json\makeString;

class JsonTest extends TestCase
{
    public function setUp(): void
    {
        $this->structure = '[{"key":"common","type":"nested","children":[]}]';

        $this->arrTest = [[
            'key' => 'common',
            'type' => 'nested',
            'oldValue' => '',
            'newValue' => '',
            'children' => [
            ]]];
    }
    public function testIter()
    {
        $this->assertEquals($this->structure, iter($this->arrTest));
    }
    public function testDysplayJson()
    {
        $this->structure = '{"type":"root","children":[{"key":"common","type":"nested","children":[]}]}';

        $this->arrTest = [[
            'key' => 'common',
            'type' => 'nested',
            'oldValue' => '',
            'newValue' => '',
            'children' => [
            ]]];
        $this->assertEquals($this->structure, displayJson($this->arrTest));
    }
    public function testMakeString()
    {
        $structureAdd = '"key": "common","type": "added","oldValue": "","newValue": "test"';
        $arrAdd = [
            'key' => 'common',
            'type' => 'added',
            'oldValue' => '',
            'newValue' => 'test',
            'children' => [
            ]];
        $this->assertEquals($structureAdd, makeString($arrAdd));
        $structureRemoved = '"key": "common","type": "removed","oldValue": "","newValue": "test"';
        $arrRemoved = [
            'key' => 'common',
            'type' => 'removed',
            'oldValue' => '',
            'newValue' => 'test',
            'children' => [
            ]];
        $this->assertEquals($structureRemoved, makeString($arrRemoved));
    }
}
