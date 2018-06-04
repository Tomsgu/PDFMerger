<?php

declare(strict_types=1);

namespace Tomsgu\PdfMerger;

use setasign\Fpdi\Fpdi;
use Tomsgu\PdfMerger\Exception\InvalidArgumentException;

/**
 * @author Tomas Jakl <tomasjakll@gmail.com>
 */
class PdfMerger
{
    const MODE_BROWSER  = 'I';
    const MODE_DOWNLOAD = 'D';
    const MODE_FILE     = 'F';
    const MODE_STRING   = 'S';

    const AVAILABLE_MODES = [
        self::MODE_BROWSER,
        self::MODE_DOWNLOAD,
        self::MODE_FILE,
        self::MODE_STRING,
    ];

    private $fpdi;

    public function __construct(Fpdi $fpdi)
    {
        $this->fpdi = $fpdi;
    }

    /**
     * @throws InvalidArgumentException
     * @throws \setasign\Fpdi\PdfReader\PdfReaderException
     */
    public function merge(
        PdfCollectionInterface $pdfCollection,
        string $outputPath = 'new_file.pdf',
        string $mode = self::MODE_BROWSER,
        string $orientation = PdfFile::ORIENTATION_PORTRAIT
    ): string {
        if ($pdfCollection->hasPdfs() === false ||
            in_array($mode, self::AVAILABLE_MODES) === false) {
            throw new InvalidArgumentException();
        }

        foreach ($pdfCollection->getPdfs() as $pdf) {
            $pagesCnt = $this->fpdi->setSourceFile($pdf->getPath());

            if ($pdf->getPages() === []) {
                for ($i = 1; $i <= $pagesCnt; $i++) {
                    $this->addPage($i, $pdf, $orientation);
                }
            } else {
                foreach ($pdf->getPages() as $pageNumber) {
                    $this->addPage($pageNumber, $pdf, $orientation);
                }
            }
        }

        return $this->fpdi->Output($outputPath, $mode);
    }

    /**
     * @throws InvalidArgumentException
     * @throws \setasign\Fpdi\PdfReader\PdfReaderException
     */
    private function addPage(int $pageNumber, PdfFile $pdfFile, string $orientation = ''): void
    {
        $fileOrientation = $pdfFile->getOrientation($orientation);

        $template = $this->fpdi->importPage($pageNumber);
        if (!$template) {
            $filename = $pdfFile->getPath();
            throw InvalidArgumentException::create("Could not load a page number '$pageNumber' from '$filename' PDF. Does the page exist?");
        }
        $size = $this->fpdi->getTemplateSize($template);
        $this->fpdi->AddPage($fileOrientation, [$size['width'], $size['height']]);
        $this->fpdi->useTemplate($template);
    }
}