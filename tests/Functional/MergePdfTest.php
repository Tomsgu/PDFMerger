<?php

declare(strict_types=1);

namespace Tomsgu\PdfMerger\Test\Functional;

use PHPUnit\Framework\TestCase;
use setasign\Fpdi\Fpdi;
use Tomsgu\PdfMerger\PagesParser;
use Tomsgu\PdfMerger\PdfCollection;
use Tomsgu\PdfMerger\PdfMerger;

/**
 * @author Tomas Jakl <tomasjakll@gmail.com>
 */
class MergePdfTest extends TestCase
{
    const TMP_FILENAME = '/tmp/sldkjfwejfokwjfoijcoemhgocermgcoerkjgpjgoergc.pdf';

    public function setUp()
    {
        if ( file_exists(self::TMP_FILENAME) === true ){
            unlink(self::TMP_FILENAME);
        }
    }

    public function testItCreatesNewPdf()
    {
        $collection = new PdfCollection();
        $collection->addPdf('./tests/Data/page1.pdf');
        $collection->addPdf('./tests/Data/page1.pdf');

        $fpdi = new Fpdi();
        $merger = new PdfMerger($fpdi);
        $merger->merge($collection, self::TMP_FILENAME, PdfMerger::MODE_FILE);

        $this->assertEquals(true, file_exists(self::TMP_FILENAME));

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $this->assertEquals('application/pdf', finfo_file($finfo, self::TMP_FILENAME));

        $pagesCount = $fpdi->setSourceFile(self::TMP_FILENAME);
        $this->assertEquals(2, $pagesCount);
    }

    public function tearDown()
    {
        unlink(self::TMP_FILENAME);
    }
}
