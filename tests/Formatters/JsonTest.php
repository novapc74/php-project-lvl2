<?php

// namespace Differ\Phpunit\Tests\JsonTest;

// use PHPUnit\Framework\TestCase;
// use function Differ\Formatters\Json\displayJson;
// use function Differ\Formatters\Json\iter;

// class JsonTest extends TestCase
// {
//     public function setUp(): void
//     {
//         $this->structure = '{"key":"common","type":"replace","oldValue":"oldValue","newValue":"newValue"}';

//         $this->arrTest = [
//             'key' => 'common',
//             'type' => 'nested',
//             'oldValue' => '',
//             'newValue' => '',
//             'children' => [[
//                 'key' => 'follow',
//                 'type' => 'removed',
//                 'oldValue' => "200"],
//                 ['key' => 'test',
//                 'type' => 'added',
//                 'newValue' => 'true']]
//             ];
//     }
//     public function testIter()
//     {
//         // $this->assertEquals($this->structure, iter($this->arrTest));
//     }
// }
