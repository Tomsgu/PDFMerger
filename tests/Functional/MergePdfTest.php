<?php

declare(strict_types=1);

namespace Tomsgu\PdfMerger\Test\Functional;

use PHPUnit\Framework\TestCase;
use setasign\Fpdi\Fpdi;
use Tomsgu\PdfMerger\PdfCollection;
use Tomsgu\PdfMerger\PdfFile;
use Tomsgu\PdfMerger\PdfMerger;

/**
 * @author Tomas Jakl <tomasjakll@gmail.com>
 */
class MergePdfTest extends TestCase
{
    const TMP_FILENAME = '/tmp/sldkjfwejfokwjfoijcoemhgocermgcoerkjgpjgoergc.pdf';
    const LANDSCAPE_TEST_FILE = './tests/Data/landscape.pdf';
    const PORTRAIT_TEST_FILE = './tests/Data/portrait.pdf';

    public function setUp(): void
    {
        if ( file_exists(self::TMP_FILENAME) === true ){
            unlink(self::TMP_FILENAME);
        }
    }

    public function testItCreatesNewPdf()
    {
        $collection = new PdfCollection();
        $collection->addPdf(self::PORTRAIT_TEST_FILE);
        $collection->addPdf(self::PORTRAIT_TEST_FILE);

        $fpdi = new Fpdi();
        $merger = new PdfMerger($fpdi);
        $merger->merge($collection, self::TMP_FILENAME, PdfMerger::MODE_FILE);

        $this->assertEquals(true, file_exists(self::TMP_FILENAME));

        $fInfo = finfo_open(FILEINFO_MIME_TYPE);
        $this->assertEquals('application/pdf', finfo_file($fInfo, self::TMP_FILENAME));

        $pagesCount = $fpdi->setSourceFile(self::TMP_FILENAME);
        $this->assertEquals(2, $pagesCount);
    }

    public function testItAutoDetectOrientation()
    {
        $collection = new PdfCollection();
        $collection->addPdf(self::PORTRAIT_TEST_FILE);
        $collection->addPdf(self::LANDSCAPE_TEST_FILE, PdfFile::ALL_PAGES, PdfFile::ORIENTATION_AUTO_DETECT);

        $fpdi = new Fpdi();
        $merger = new PdfMerger($fpdi);
        $merger->merge($collection, self::TMP_FILENAME, PdfMerger::MODE_FILE);

        $this->assertEquals(true, file_exists(self::TMP_FILENAME));

        $fInfo = finfo_open(FILEINFO_MIME_TYPE);
        $this->assertEquals('application/pdf', finfo_file($fInfo, self::TMP_FILENAME));

        $pagesCount = $fpdi->setSourceFile(self::TMP_FILENAME);
        $this->assertEquals(3, $pagesCount);

        $template = $fpdi->importPage(1);
        $size = $fpdi->getTemplateSize($template);
        $this->assertGreaterThan($size['width'], $size['height'], 'Portrait orientation wasn\'t detected.');

        $template = $fpdi->importPage(2);
        $size = $fpdi->getTemplateSize($template);
        $this->assertLessThanOrEqual($size['width'], $size['height'], 'Landscape orientation wasn\'t detected.');

        $template = $fpdi->importPage(3);
        $size = $fpdi->getTemplateSize($template);
        $this->assertLessThanOrEqual($size['width'], $size['height'], 'Landscape orientation wasn\'t detected.');
    }


    public function testItMergeResource()
    {
        $collection = new PdfCollection();
        $pdfResource1 = fopen(self::PORTRAIT_TEST_FILE, 'r');
        $pdfResource2 = fopen(self::LANDSCAPE_TEST_FILE, 'r');

        $collection->addPdf($pdfResource1);
        $collection->addPdf($pdfResource2, PdfFile::ALL_PAGES, PdfFile::ORIENTATION_AUTO_DETECT);

        $fpdi = new Fpdi();
        $merger = new PdfMerger($fpdi);
        $merger->merge($collection, self::TMP_FILENAME, PdfMerger::MODE_FILE);

        $this->assertEquals(true, file_exists(self::TMP_FILENAME));

        $fInfo = finfo_open(FILEINFO_MIME_TYPE);
        $this->assertEquals('application/pdf', finfo_file($fInfo, self::TMP_FILENAME));

        $pagesCount = $fpdi->setSourceFile(self::TMP_FILENAME);
        $this->assertEquals(3, $pagesCount);

        $template = $fpdi->importPage(1);
        $size = $fpdi->getTemplateSize($template);
        $this->assertGreaterThan($size['width'], $size['height'], 'Portrait orientation wasn\'t detected.');

        $template = $fpdi->importPage(2);
        $size = $fpdi->getTemplateSize($template);
        $this->assertLessThanOrEqual($size['width'], $size['height'], 'Landscape orientation wasn\'t detected.');

        $template = $fpdi->importPage(3);
        $size = $fpdi->getTemplateSize($template);
        $this->assertLessThanOrEqual($size['width'], $size['height'], 'Landscape orientation wasn\'t detected.');

    }


    public function tearDown(): void
    {
        unlink(self::TMP_FILENAME);
    }
}
