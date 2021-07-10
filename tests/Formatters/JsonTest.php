<?php

namespace Differ\Phpunit\Tests\JsonTest;

use PHPUnit\Framework\TestCase;
use function Differ\Formatters\Json\displayJson;
use function Differ\Formatters\Json\iter;

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
}
