<?php

namespace Differ\Phpunit\Tests\Differ;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function getFixtureFullPath($fixtureName): string
    {
        $parts = [__DIR__, 'fixtures', $fixtureName];
        return realpath(implode('/', $parts));
    }

    public function fixturesProvider()
    {
        $firstPathJson = $this->getFixtureFullPath('firstFile.json');
        $secondPathJson = $this->getFixtureFullPath('secondFile.json');

        $firstPathYaml = $this->getFixtureFullPath('firstFile.yaml');
        $secondPathYaml = $this->getFixtureFullPath('secondFile.yaml');

        $expectedStylish = trim(file_get_contents($this->getFixtureFullPath('resultStylishDiff')));
        $expectedPlain = trim(file_get_contents($this->getFixtureFullPath('resultPlainDiff')));
        $expectedJson = trim(file_get_contents($this->getFixtureFullPath('resultJsonDiff')));

        return [
            [$firstPathJson, $secondPathJson, $expectedStylish],
            [$firstPathJson, $secondPathJson, $expectedStylish, 'stylish'],
            [$firstPathJson, $secondPathJson, $expectedPlain , 'plain'],
            [$firstPathJson, $secondPathJson, $expectedJson, 'json'],
            [$firstPathYaml, $secondPathYaml, $expectedStylish],
            [$firstPathYaml, $secondPathYaml, $expectedStylish, 'stylish'],
            [$firstPathYaml, $secondPathYaml, $expectedPlain, 'plain'],
            [$firstPathYaml, $secondPathYaml, $expectedJson, 'json']
        ];
    }

    /**
     * @dataProvider fixturesProvider
     */
    public function testGenDiff($firstPath, $secondPath, $expected, $style = 'stylish')
    {
        $this->assertEquals($expected, genDiff($firstPath, $secondPath, $style));
    }
}
