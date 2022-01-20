<?php

declare(strict_types=1);

namespace Violet\TypeKit\Exception;

use Violet\TypeKit\Debug\Debug;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class TypeAssertException extends InvalidTypeException
{
    public static function createFromValue(mixed $value, string $expectedType): self
    {
        return new static(
            sprintf("Got unexpected value type '%s', was expecting '%s'", Debug::describeType($value), $expectedType)
        );
    }
}
