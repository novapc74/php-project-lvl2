<?php

namespace Differ\Phpunit\Tests\Differ;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public static $firstJson = 'tests/fixtures/firstFile.json';
    public static $secondJson = 'tests/fixtures/secondFile.json';
    public static $firstYaml = 'tests/fixtures/firstFile.yaml';
    public static $secondYaml = 'tests/fixtures/secondFile.yaml';

    public function testGenDiffDefault()
    {
        $expected = trim(file_get_contents('tests/fixtures/resultStylishDiff'));
        $this->assertEquals($expected, genDiff(self::$firstJson, self::$secondJson));
        $this->assertEquals($expected, genDiff(self::$firstYaml, self::$secondYaml));
    }
    public function testGenDiffStylish()
    {
        $expected = trim(file_get_contents('tests/fixtures/resultStylishDiff'));
        $this->assertEquals($expected, genDiff(self::$firstJson, self::$secondJson, 'stylish'));
        $this->assertEquals($expected, genDiff(self::$firstYaml, self::$secondYaml, 'stylish'));
    }

    public function testGenDiffPlain()
    {
        $expected = trim(file_get_contents('tests/fixtures/resultPlainDiff'));
        $this->assertEquals($expected, genDiff(self::$firstJson, self::$secondJson, 'plain'));
        $this->assertEquals($expected, genDiff(self::$firstYaml, self::$secondYaml, 'plain'));
    }

    public function testGenDiffJson()
    {
        $expected = trim(file_get_contents('tests/fixtures/resultJsonDiff'));
        $this->assertEquals($expected, genDiff(self::$firstJson, self::$secondJson, 'json'));
        $this->assertEquals($expected, genDiff(self::$firstYaml, self::$secondYaml, 'json'));
    }
}
