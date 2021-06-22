<?php

namespace Differ\Phpunit\Tests\Formatters;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;
use stdClass;

use function Differ\Formatters\selectFormat;

class FormattersTest extends TestCase
{
    public function setUp(): void
    {
        $this->pathToBeginFlatJson = 'tests/fixtures/flatFixtures/beginFileJson.json';
        $this->pathToEndFlatJson = 'tests/fixtures/flatFixtures/endFileJson.json';
    }

    public function testSelectFormat()
    {
        $path1 = $this->pathToBeginFlatJson;
        $path2 = $this->pathToEndFlatJson;
        $this->assertTrue(is_string(selectFormat($path1, $path2, 'stylish')));
        $this->assertTrue(is_string(selectFormat($path1, $path2, 'plain')));
        $this->assertTrue(is_string(selectFormat($path1, $path2, 'json')));
    }
}
