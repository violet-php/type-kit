<?php

declare(strict_types=1);

namespace Violet\TypeKit\Type;

use Violet\TypeKit\TypeCast;
use Violet\TypeKit\Exception\TypeCastException;
use Violet\TypeKit\Exception\InvalidClassException;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
trait CastListTypesTrait
{
    /**
     * @param mixed $value
     * @return list<null>
     * @throws TypeCastException
     */
    public static function nullList(mixed $value): array
    {
        return self::handleListCast(TypeCast::null(...), $value, 'list<null>');
    }

    /**
     * @param mixed $value
     * @return list<bool>
     * @throws TypeCastException
     */
    public static function boolList(mixed $value): array
    {
        return self::handleListCast(TypeCast::bool(...), $value, 'list<bool>');
    }

    /**
     * @param mixed $value
     * @return list<int>
     * @throws TypeCastException
     */
    public static function intList(mixed $value): array
    {
        return self::handleListCast(TypeCast::int(...), $value, 'list<int>');
    }

    /**
     * @param mixed $value
     * @return list<float>
     * @throws TypeCastException
     */
    public static function floatList(mixed $value): array
    {
        return self::handleListCast(TypeCast::float(...), $value, 'list<float>');
    }

    /**
     * @param mixed $value
     * @return list<string>
     * @throws TypeCastException
     */
    public static function stringList(mixed $value): array
    {
        return self::handleListCast(TypeCast::string(...), $value, 'list<string>');
    }

    /**
     * @param mixed $value
     * @return list<array<mixed>>
     * @throws TypeCastException
     */
    public static function arrayList(mixed $value): array
    {
        return self::handleListCast(TypeCast::array(...), $value, 'list<array>');
    }

    /**
     * @param mixed $value
     * @return list<list<mixed>>
     * @throws TypeCastException
     */
    public static function listList(mixed $value): array
    {
        return self::handleListCast(TypeCast::list(...), $value, 'list<list>');
    }

    /**
     * @param mixed $value
     * @return list<object>
     * @throws TypeCastException
     */
    public static function objectList(mixed $value): array
    {
        return self::handleListCast(TypeCast::object(...), $value, 'list<object>');
    }

    /**
     * @template T of object
     * @param mixed $value
     * @param class-string<T> $class
     * @return list<T>
     * @throws TypeCastException
     */
    public static function instanceList(mixed $value, string $class): array
    {
        if (!class_exists($class) && !interface_exists($class)) {
            throw InvalidClassException::createFromName($class);
        }

        return self::handleListCast(
            static fn (mixed $value) => TypeCast::instance($value, $class),
            $value,
            "list<$class>"
        );
    }

    /**
     * @param mixed $value
     * @return list<iterable<mixed>>
     * @throws TypeCastException
     */
    public static function iterableList(mixed $value): array
    {
        return self::handleListCast(TypeCast::iterable(...), $value, 'list<iterable>');
    }

    /**
     * @param mixed $value
     * @return list<resource>
     * @throws TypeCastException
     */
    public static function resourceList(mixed $value): array
    {
        return self::handleListCast(TypeCast::resource(...), $value, 'list<resource>');
    }

    /**
     * @param mixed $value
     * @return list<callable>
     * @throws TypeCastException
     */
    public static function callableList(mixed $value): array
    {
        return self::handleListCast(TypeCast::callable(...), $value, 'list<callable>');
    }

    /**
     * @template T
     * @param \Closure(mixed):T $cast
     * @param mixed $value
     * @param string $expectedType
     * @return list<T>
     */
    private static function handleListCast(\Closure $cast, mixed $value, string $expectedType): array
    {
        try {
            $result = array_values(TypeCast::array($value));

            /** @var mixed $item */
            foreach ($result as $key => $item) {
                $new = $cast($item);
                $result[$key] = $new;
            }

            return $result;
        } catch (\Throwable $exception) {
            throw TypeCastException::createFromFailure($value, $expectedType, $exception);
        }
    }
}
