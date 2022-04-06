<?php

declare(strict_types=1);

namespace Tomsgu\PdfMerger\Test\Unit;

use PHPUnit\Framework\TestCase;
use setasign\Fpdi\Fpdi;
use Tomsgu\PdfMerger\Exception\InvalidArgumentException;
use Tomsgu\PdfMerger\PdfCollection;
use Tomsgu\PdfMerger\PdfMerger;

/**
 * @author Tomas Jakl <tomasjakll@gmail.com>
 */
class PdfMergerTest extends TestCase
{
    const TEST_FILE = './tests/Data/landscape.pdf';

    public function testEmptyCollection()
    {
        $collection = new PdfCollection();
        $fpdi = new Fpdi();
        $merger = new PdfMerger($fpdi);

        $this->expectException(InvalidArgumentException::class);
        $merger->merge($collection, 'new_file.pdf', PdfMerger::MODE_FILE);
    }

    public function testInvalidMode()
    {
        $collection = new PdfCollection();
        $collection->addPdf(self::TEST_FILE);
        $fpdi = new Fpdi();
        $merger = new PdfMerger($fpdi);

        $this->expectException(InvalidArgumentException::class);
        $merger->merge($collection, 'new_file.pdf', 'ZZZ');
    }
}
