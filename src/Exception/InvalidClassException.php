<?php

declare(strict_types=1);

namespace Violet\TypeKit\Exception;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class InvalidClassException extends \InvalidArgumentException implements TypeKitException
{
    public static function createFromName(string $name): self
    {
        return new InvalidClassException("No class or interface named '$name' exists");
    }
}
