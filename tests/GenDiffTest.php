<?php

namespace Project\Phpunit\Tests;

use PHPUnit\Framework\TestCase;
use function Project\Package\Gendiff\convertFileContent;
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
        $this->assertTrue(is_array(convertFileContent($this->fileJsonFirst)));
        $this->assertFalse(empty(convertFileContent($this->fileJsonFirst)));
        $this->assertEquals($expected1, convertFileContent($this->fileJsonFirst));
        $this->assertEquals($expected2, convertFileContent($this->fileJsonSecond));
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
    }
}
