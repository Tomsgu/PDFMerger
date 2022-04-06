<?php

declare(strict_types=1);

namespace Tomsgu\PdfMerger;

use Tomsgu\PdfMerger\Exception\FileNotFoundException;
use Tomsgu\PdfMerger\Exception\InvalidArgumentException;

/**
 * @author Tomas Jakl <tomasjakll@gmail.com>
 */
class PdfCollection implements PdfCollectionInterface
{
    /** @var PdfFile[] */
    private $collection;

    public function __construct()
    {
        $this->collection = [];
    }

    /**
     * @throws FileNotFoundException
     * @throws InvalidArgumentException
     */
    public function addPdf($filePath, string $pages = PdfFile::ALL_PAGES, string $orientation = '')
    {
        $parsedPages = PagesParser::parsePages($pages);
        $pdfFile = new PdfFile($filePath, $parsedPages, $orientation);
        $this->collection[] = $pdfFile;

        return $this;
    }

    /**
     * @return PdfFile[]
     */
    public function getPdfs() : array
    {
        return $this->collection;
    }

    public function hasPdfs() : bool
    {
        return count($this->collection) !== 0;
    }
}
