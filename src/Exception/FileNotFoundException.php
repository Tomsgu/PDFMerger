<?php

declare(strict_types=1);

namespace Tomsgu\PdfMerger\Exception;

/**
 * @author Tomas Jakl <tomasjakll@gmail.com>
 */
class FileNotFoundException extends \Exception implements PdfMergerExceptionInterface
{
    /**
     * @return self
     */
    public static function create(string $message)
    {
        return new self($message);
    }
}
