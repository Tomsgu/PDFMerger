<?php

declare(strict_types=1);


namespace Tomsgu\PdfMerger\Test\Unit;

use PHPUnit\Framework\TestCase;
use Tomsgu\PdfMerger\Exception\InvalidArgumentException;
use Tomsgu\PdfMerger\PagesParser;

/**
 * @author Tomas Jakl <tomasjakll@gmail.com>
 */
class PagesParserTest extends TestCase
{
    /**
     * @dataProvider validArgumentProvider
     */
    public function testParsePages($value, $expected)
    {
        $pagesParser = new PagesParser();

        $pages = $pagesParser->parsePages($value);
        $this->assertEquals($expected, $pages);
    }

    /**
     * @dataProvider invalidArgumentProvider
     */
    public function testInvalidArgumentException(string $invalidValue)
    {
        $pagesParser = new PagesParser();

        $this->expectException(InvalidArgumentException::class);
        $pagesParser->parsePages($invalidValue);
    }

    /**
     * @dataProvider invalidPagesRangeProvider
     */
    public function testInvalidPagesRangeException(string $invalidValue)
    {
        $pagesParser = new PagesParser();

        $this->expectException(InvalidArgumentException::class);
        $pagesParser->parsePages($invalidValue);
    }

    public function validArgumentProvider()
    {
        return [
            ['all', []],
            ['1', [1 => 1]],
            ['123', [123 => 123]],
            ['1-2', [1 => 1, 2 => 2]],
            ['123-124', [123 => 123, 124 => 124]],
            ['9-11', [9 => 9, 10 => 10, 11 => 11]],
            ['1,2', [1 => 1, 2 => 2]],
            ['123-124,123', [123 => 123, 124 => 124]],
            ['123-124,33-34', [123 => 123, 124 => 124, 33 => 33, 34 => 34]],
            ['1-2,2-3', [1 => 1, 2 => 2, 3 => 3]],
        ];
    }

    public function invalidPagesRangeProvider()
    {
        return [
            ['2-2'],
            ['2-1'],
            ['12-12'],
            ['12-1'],
        ];
    }

    public function invalidArgumentProvider()
    {
        return [
            ['0'],
            ['0123'],
            ['123,'],
            ['12-0'],
            ['123-0123'],
            ['1,023'],
            ['1,0'],
            ['1,324-'],
            ['234-'],
            [''],
            ['233-333,'],
        ];
    }
}
