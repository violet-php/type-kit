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
    public function isNullList(mixed $value): bool
    {
        return Type::isNullArray($value) && array_is_list($value);
    }

    public function isBoolList(mixed $value): bool
    {
        return Type::isBoolArray($value) && array_is_list($value);
    }

    public function isIntList(mixed $value): bool
    {
        return Type::isIntArray($value) && array_is_list($value);
    }

    public function isFloatList(mixed $value): bool
    {
        return Type::isFloatArray($value) && array_is_list($value);
    }

    public function isStringList(mixed $value): bool
    {
        return Type::isStringArray($value) && array_is_list($value);
    }

    public function isArrayList(mixed $value): bool
    {
        return Type::isArrayArray($value) && array_is_list($value);
    }

    public function isListList(mixed $value): bool
    {
        return Type::isListArray($value) && array_is_list($value);
    }

    public function isObjectList(mixed $value): bool
    {
        return Type::isObjectArray($value) && array_is_list($value);
    }

    public function isInstanceList(mixed $value, string $class): bool
    {
        return Type::isInstanceArray($value, $class) && array_is_list($value);
    }

    public function isIterableList(mixed $value): bool
    {
        return Type::isIterableArray($value) && array_is_list($value);
    }

    public function isResourceList(mixed $value): bool
    {
        return Type::isResourceArray($value) && array_is_list($value);
    }

    public function isCallableList(mixed $value): bool
    {
        return Type::isCallableArray($value) && array_is_list($value);
    }
}
