<?php

declare(strict_types=1);

namespace Violet\TypeKit;

use Violet\TypeKit\Exception\TypeException;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class Type
{
    public static function bool(mixed $value): bool
    {
        if (\is_bool($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'bool');
    }

    public static function int(mixed $value): int
    {
        if (\is_int($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'int');
    }

    public static function float(mixed $value): float
    {
        if (\is_float($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'float');
    }

    public static function string(mixed $value): string
    {
        if (\is_string($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'string');
    }

    /**
     * @param mixed $value
     * @return array<mixed>
     */
    public static function array(mixed $value): array
    {
        if (\is_array($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'array');
    }

    /**
     * @param mixed $value
     * @return array<string>
     */
    public static function stringArray(mixed $value): array
    {
        if (\is_array($value)) {
            foreach ($value as $item) {
                if (!\is_string($item)) {
                    throw TypeException::createFromValue($value, 'array<string>');
                }
            }

            return $value;
        }

        throw TypeException::createFromValue($value, 'array<string>');
    }

    /**
     * @param mixed $value
     * @return list<mixed>
     */
    public static function list(mixed $value): array
    {
        if (\is_array($value) && array_is_list($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'list');
    }

    /**
     * @param mixed $value
     * @return list<string>
     */
    public static function stringList(mixed $value): array
    {
        if (\is_array($value) && array_is_list($value)) {
            foreach ($value as $item) {
                if (!\is_string($item)) {
                    throw TypeException::createFromValue($value, 'list<string>');
                }
            }

            return $value;
        }

        throw TypeException::createFromValue($value, 'list<string>');
    }

    public static function object(mixed $value): object
    {
        if (\is_object($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'object');
    }
}
