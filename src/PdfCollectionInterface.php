<?php

declare(strict_types=1);

namespace Tomsgu\PdfMerger;

/**
 * @author Tomas Jakl <tomasjakll@gmail.com>
 */
interface PdfCollectionInterface
{
    /**
     * Add pdf file to the collection.
     *
     * @param string $filePath Path of the file.
     * @param string $pages String representation of pages to parse.
     * @param string $orientation Can be landscape or portrait.
     */
    public function addPdf(string $filePath, string $pages = PdfFile::ALL_PAGES, string $orientation = '');

    /**
     * Returns all PdfFile objects.
     *
     * @return array|PdfFile[]
     */
    public function getPdfs() : array;

    /**
     * Returns true if there is any file in the collection. False otherwise.
     *
     * @return bool
     */
    public function hasPdfs() : bool;
}