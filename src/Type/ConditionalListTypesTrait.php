<?php

declare(strict_types=1);

namespace Violet\TypeKit\Type;

use Violet\TypeKit\Type;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
trait ConditionalListTypesTrait
{
    public static function isNullList(mixed $value): bool
    {
        return Type::isNullArray($value) && array_is_list($value);
    }

    public static function isBoolList(mixed $value): bool
    {
        return Type::isBoolArray($value) && array_is_list($value);
    }

    public static function isIntList(mixed $value): bool
    {
        return Type::isIntArray($value) && array_is_list($value);
    }

    public static function isFloatList(mixed $value): bool
    {
        return Type::isFloatArray($value) && array_is_list($value);
    }

    public static function isStringList(mixed $value): bool
    {
        return Type::isStringArray($value) && array_is_list($value);
    }

    public static function isArrayList(mixed $value): bool
    {
        return Type::isArrayArray($value) && array_is_list($value);
    }

    public static function isListList(mixed $value): bool
    {
        return Type::isListArray($value) && array_is_list($value);
    }

    public static function isObjectList(mixed $value): bool
    {
        return Type::isObjectArray($value) && array_is_list($value);
    }

    public static function isInstanceList(mixed $value, string $class): bool
    {
        return Type::isInstanceArray($value, $class) && array_is_list($value);
    }

    public static function isIterableList(mixed $value): bool
    {
        return Type::isIterableArray($value) && array_is_list($value);
    }

    public static function isResourceList(mixed $value): bool
    {
        return Type::isResourceArray($value) && array_is_list($value);
    }

    public static function isCallableList(mixed $value): bool
    {
        return Type::isCallableArray($value) && array_is_list($value);
    }
}
