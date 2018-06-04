<?php

declare(strict_types=1);


namespace Tomsgu\PdfMerger\Test\Unit;

use PHPUnit\Framework\TestCase;
use Tomsgu\PdfMerger\PdfCollection;
use Tomsgu\PdfMerger\PdfFile;

/**
 * @author Tomas Jakl <tomasjakll@gmail.com>
 */
class PdfCollectionTest extends TestCase
{
    public function testAddPdf()
    {
        $pdfCollection = new PdfCollection();
        $pdfFile = new PdfFile('./tests/Data/page1.pdf');
        $pdfFileLandscape = new PdfFile('./tests/Data/page1.pdf', [], PdfFile::ORIENTATION_LANDSCAPE);
        $this->assertEquals(false, $pdfCollection->hasPdfs());

        $pdfCollection->addPdf('./tests/Data/page1.pdf');
        $this->assertEquals(true, $pdfCollection->hasPdfs());
        $this->assertEquals($pdfFile, $pdfCollection->getPdfs()[0]);

        $pdfCollection->addPdf('./tests/Data/page1.pdf', PdfFile::ALL_PAGES, PdfFile::ORIENTATION_LANDSCAPE);
        $this->assertEquals($pdfFileLandscape, $pdfCollection->getPdfs()[1]);
    }
}
