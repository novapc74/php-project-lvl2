<?php

namespace Differ\Phpunit\Tests\Extension;

use PHPUnit\Framework\TestCase;

use function Differ\Extension\isFileExtension;

class ExtensionTest extends TestCase
{
    public function setUp(): void
    {
        $this->listExtension = ['json', 'yaml', 'yml'];

        $this->filePathJason1 = 'work/file1.json';
        $this->filePathJason2 = 'work/file2.json';

        $this->filePathYaml1 = 'work/file1.yaml';
        $this->filePathYaml2 = 'work/file2.yaml';

        $this->filePathYml1 = 'work/file1.yml';
        $this->filePathYml2 = 'work/file2.yml';

        $this->filePath3 = 'work/file.yml';
    }

    public function testIsFileExtension()
    {
        $this->assertEquals('json', isFileExtension($this->filePathJason1, $this->filePathJason1, $this->listExtension));
        $this->assertEquals('yaml', isFileExtension($this->filePathYaml1, $this->filePathYaml2, $this->listExtension));
        $this->assertEquals('yml', isFileExtension($this->filePathYml1, $this->filePathYml2, $this->listExtension));

        // isFileExtension($this->filePathJason1, $this->filePathYaml1, $this->listExtension);
        // $this->expectException(Exception::class);
    }
}
