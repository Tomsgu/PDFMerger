<?php

declare(strict_types=1);


namespace Tomsgu\PdfMerger\Test\Unit;

use PHPUnit\Framework\TestCase;
use Tomsgu\PdfMerger\Exception\FileNotFoundException;
use Tomsgu\PdfMerger\Exception\InvalidArgumentException;
use Tomsgu\PdfMerger\PdfFile;

/**
 * @author Tomas Jakl <tomasjakll@gmail.com>
 */
class PdfFileTest extends TestCase
{
    public function testGetOrientation()
    {
        $file = $this->getPdfFile([], PdfFile::ORIENTATION_PORTRAIT);
        $this->assertEquals(PdfFile::ORIENTATION_PORTRAIT, $file->getOrientation());

        $file = $this->getPdfFile([], '');
        $this->assertEquals(PdfFile::ORIENTATION_PORTRAIT,
            $file->getOrientation(PdfFile::ORIENTATION_PORTRAIT));

        $this->expectException(InvalidArgumentException::class);
        $this->getPdfFile([], 'fake_orientation');
    }

    public function testGetPages()
    {
        $file = $this->getPdfFile();
        $this->assertEquals([], $file->getPages());

        $file = $this->getPdfFile([1 => 1, 2 => 2]);
        $this->assertEquals([1 => 1, 2 => 2], $file->getPages());
    }

    public function testGetPath()
    {
        $this->expectException(FileNotFoundException::class);

        new PdfFile('fake_path.pdfpdf', [], '');
    }

    public function testItAcceptsResource()
    {
        $resource = fopen('./tests/Data/landscape.pdf', 'r');
        $pdfFile = new PdfFile($resource, [], '');

        $this->assertIsResource($pdfFile->getPath());
    }

    private function getPdfFile(array $pages = [], string $orientation = ''): PdfFile
    {
        $file = fopen("/tmp/test_file.pdf", "w");
        fclose($file);

        return new PdfFile("/tmp/test_file.pdf", $pages, $orientation);
    }
}
