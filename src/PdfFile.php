<?php

declare(strict_types=1);

namespace Tomsgu\PdfMerger;

use Tomsgu\PdfMerger\Exception\FileNotFoundException;
use Tomsgu\PdfMerger\Exception\InvalidArgumentException;

/**
 * @author Tomas Jakl <tomasjakll@gmail.com>
 */
class PdfFile
{
    const ALL_PAGES = 'all';

    const ORIENTATION_LANDSCAPE  = 'landscape';
    const ORIENTATION_PORTRAIT   = 'portrait';
    const ORIENTATION_AUTO_DETECT = 'auto';
    const AVAILABLE_ORIENTATIONS = [
        self::ORIENTATION_PORTRAIT,
        self::ORIENTATION_LANDSCAPE,
        self:: ORIENTATION_AUTO_DETECT,
        '',
    ];

    private $path;
    private $pages;
    private $orientation;

    /**
     * @throws FileNotFoundException
     * @throws InvalidArgumentException
     */
    public function __construct(
        string $filePath,
        array $pages = [],
        string $orientation = ''
    ) {
        if (file_exists($filePath) === false) {
            throw new FileNotFoundException();
        }
        if (in_array($orientation, self::AVAILABLE_ORIENTATIONS) === false) {
            throw new InvalidArgumentException();
        }

        $this->path = $filePath;
        $this->pages = $pages;
        $this->orientation = $orientation;
    }

    public function getPages(): array
    {
        return $this->pages;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getOrientation(string $defaultValue = ''): string
    {
        if ($this->orientation === '') {
            return $defaultValue;
        }

        return $this->orientation;
    }
}