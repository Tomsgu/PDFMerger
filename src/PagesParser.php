<?php

declare(strict_types=1);

namespace Tomsgu\PdfMerger;

use Tomsgu\PdfMerger\Exception\InvalidArgumentException;

/**
 * @author Tomas Jakl <tomasjakll@gmail.com>
 */
class PagesParser
{
    /**
     * @throws InvalidArgumentException
     * @return int[]
     */
    public static function parsePages(string $pages): array
    {
        if (self::checkPages($pages) === false) {
            throw InvalidArgumentException::create("An invalid argument ({$pages}) passed.");
        }

        return self::getPages($pages);
    }

    /**
     * @throws InvalidArgumentException
     * @return int[]
     */
    private static function getPages(string $pages): array
    {
        $pagesRet = [];
        if ($pages === PdfFile::ALL_PAGES) {
            return $pagesRet;
        }

        $pagesArray = explode(',', $pages);

        foreach ($pagesArray as $pageRange) {
            $range = explode('-', $pageRange);
            if (count($range) === 1) {
                $number = intval($range[0]);
                $pagesRet[$number] = $number;
            } else {
                $startPage = intval($range[0]);
                $endPage = intval($range[1]);

                if ($startPage >= $endPage) {
                    throw InvalidArgumentException::create("Start page {$startPage} is greater or equal to end page {$endPage}.");
                }

                for (; $startPage <= $endPage; $startPage++) {
                    $pagesRet[$startPage] = $startPage;
                }
            }
        }

        return $pagesRet;
    }

    private static function checkPages(string $pages): bool
    {
        $pattern = '/^(([1-9][0-9]*)|([1-9][0-9]*-[1-9][0-9]*))(,(([1-9][0-9]*)|([1-9][0-9]*-[1-9][0-9]*)))*$/';

        if ($pages === PdfFile::ALL_PAGES) {
            return true;
        }

        if (preg_match($pattern, $pages) === 1) {
            return true;
        }

        return false;
    }
}
