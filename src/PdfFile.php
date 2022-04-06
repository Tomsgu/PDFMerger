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

    const ORIENTATION_LANDSCAPE = 'landscape';
    const ORIENTATION_PORTRAIT = 'portrait';
    const ORIENTATION_AUTO_DETECT = 'auto';
    const AVAILABLE_ORIENTATIONS = [
        self::ORIENTATION_PORTRAIT,
        self::ORIENTATION_LANDSCAPE,
        self:: ORIENTATION_AUTO_DETECT,
        '',
    ];

    /**
     * @var resource|string
     */
    private $path;

    /**
     * @var int[]
     */
    private $pages;

    /**
     * @var string
     */
    private $orientation;

    /**
     * @throws FileNotFoundException
     * @throws InvalidArgumentException
     *
     * @param string|resource $file
     * @param int[] $pages
     */
    public function __construct(
        $file,
        array $pages = [],
        string $orientation = ''
    ) {
        if (is_string($file) && file_exists($file) === false) {
            throw new FileNotFoundException();
        }
        if (in_array($orientation, self::AVAILABLE_ORIENTATIONS) === false) {
            throw new InvalidArgumentException();
        }

        $this->path = $file;
        $this->pages = $pages;
        $this->orientation = $orientation;
    }

    /**
     * @return int[]
     */
    public function getPages(): array
    {
        return $this->pages;
    }

    /**
     * @return string|resource
     */
    public function getPath()
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
