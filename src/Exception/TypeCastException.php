<?php

declare(strict_types=1);

namespace Violet\TypeKit\Exception;

use Violet\TypeKit\Debug\Debug;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class TypeCastException extends InvalidTypeException
{
    public static function createFromFailure(mixed $value, string $expectedType, \Throwable $exception): self
    {
        return new self(sprintf(
            "Error trying to cast '%s' to '%s': %s",
            Debug::describeType($value),
            $expectedType,
            $exception->getMessage()
        ), 0, $exception);
    }

    public static function createFromMessage(mixed $value, string $expectedType, string $message): self
    {
        return new self(sprintf(
            "Error trying to cast '%s' to '%s': %s",
            Debug::describeType($value),
            $expectedType,
            $message
        ));
    }

    public static function createFromInvalidType(mixed $value, string $expectedType): self
    {
        return self::createFromMessage($value, $expectedType, 'Type cannot be cast to ' . $expectedType);
    }
}
