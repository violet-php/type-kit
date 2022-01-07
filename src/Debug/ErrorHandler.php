<?php

declare(strict_types=1);

namespace Violet\TypeKit\Debug;

use Violet\TypeKit\Exception\ErrorException;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class ErrorHandler
{
    /**
     * @template T
     * @param \Closure():T $callback
     * @return T
     */
    public static function handleCall(\Closure $callback): mixed
    {
        set_error_handler(self::throwErrorException(...));

        try {
            return $callback();
        } finally {
            restore_error_handler();
        }
    }

    public static function throwErrorException(int $severity, string $error, string $filename, int $line): never
    {
        throw new ErrorException($error, 0, $severity, $filename, $line);
    }
}
