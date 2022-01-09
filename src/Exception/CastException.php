<?php

declare(strict_types=1);

namespace Violet\TypeKit\Exception;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CastException extends TypeException
{
    public static function createFromFailure(mixed $value, string $expectedType, \Throwable $exception): TypeException
    {
        return new self(sprintf(
            "Error trying to cast '%s' to '%s': %s",
            self::describeType($value),
            $expectedType,
            $exception->getMessage()
        ), $exception);
    }

    public static function createFromMessage(mixed $value, string $expectedType, string $message): TypeException
    {
        return new self(sprintf(
            "Error trying to cast '%s' to '%s': %s",
            self::describeType($value),
            $expectedType,
            $message
        ));
    }
}
