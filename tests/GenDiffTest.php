<?php

namespace Project\Phpunit\Tests;

use PHPUnit\Framework\TestCase;
use function Project\Package\Gendiff\convertFileArrayContent;
use function Project\Package\Gendiff\getOutFotmat;

class GenDiffTest extends TestCase
{
    private $fileJsonFirst;
    private $fileJsonSecond;

    public function setUp(): void
    {
        $this->fileJsonFirst = file('work/file1.json');
        $this->fileJsonSecond = file('work/file2.json');
        $this->firstConverted = [
            'follow' => 'false',
            'host' => 'hexlet.io',
            'proxy' => '123.234.53.22',
            'timeout' => '50',
            ];
        $this->secondConverted = [
            'host' => 'hexlet.io',
            'timeout' => '20',
            'verbose' => 'true',
            ];
        // $pathToJson1 = getFixtureFullPath('file1.json');
        // $pathToJson2 = getFixtureFullPath('file2.json');
    }

    // определение путей до фикстур
    // public function getFixtureFullPath($fixtureName)
    // {
    //     $parts = [__DIR__, 'fixtures', $fixtureName];
    //     return realpath(implode('/', $parts));
    // }

    public function comparisonFixtures($pathToJson1, $pathToJson2): void
    {
// пока пустая функция
    }

    public function testConvert(): void
    {
        $expected1 = [
            'follow' => 'false',
            'host' => 'hexlet.io',
            'proxy' => '123.234.53.22',
            'timeout' => '50',
            ];
        $expected2 = [
            'host' => 'hexlet.io',
            'timeout' => '20',
            'verbose' => 'true',
            ];
        $this->assertTrue(is_array(convertFileArrayContent($this->fileJsonFirst)));
        $this->assertFalse(empty(convertFileArrayContent($this->fileJsonFirst)));
        $this->assertEquals($expected1, convertFileArrayContent($this->fileJsonFirst));
        $this->assertEquals($expected2, convertFileArrayContent($this->fileJsonSecond));
    }
    public function testGetFormat(): void
    {
        $expected ='{
-follow: false
 host: hexlet.io
-proxy: 123.234.53.22
-timeout: 50
+timeout: 20
+verbose: true
}
';
        $this->assertEquals($expected, getOutFotmat($this->firstConverted, $this->secondConverted));
        $this->assertTrue(!is_array(getOutFotmat($this->firstConverted, $this->secondConverted)));
        $this->assertFalse(empty(getOutFotmat($this->firstConverted, $this->secondConverted)));
    }
}
