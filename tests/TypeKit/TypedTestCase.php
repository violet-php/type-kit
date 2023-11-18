<?php

declare(strict_types=1);

namespace Violet\TypeKit;

use PHPUnit\Framework\TestCase;
use Violet\TypeKit\Exception\InvalidClassException;
use Violet\TypeKit\PhpUnit\AbstractCompliantClass;
use Violet\TypeKit\PhpUnit\CompliantClass;
use Violet\TypeKit\PhpUnit\CompliantInterface;
use Violet\TypeKit\PhpUnit\CompliantTrait;
use Violet\TypeKit\PhpUnit\NonCompliantClass;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
abstract class TypedTestCase extends TestCase
{
    /**
     * @return array<array{\Closure, mixed}>
     */
    public static function getValidValuesTestCases(): array
    {
        return [
            [self::makeCall('null'), null],
            [self::makeCall('bool'), true],
            [self::makeCall('int'), 1],
            [self::makeCall('float'), 1.1],
            [self::makeCall('string'), 'foobar'],
            [self::makeCall('array'), []],
            [self::makeCall('array'), [1]],
            [self::makeCall('array'), [1, 2, 3]],
            [self::makeCall('array'), ['foo' => 'bar']],
            [self::makeCall('list'), []],
            [self::makeCall('list'), [1]],
            [self::makeCall('list'), [1, 2, 3]],
            [self::makeCall('object'), new CompliantClass()],
            [self::makeCall('instance', [CompliantClass::class]), new CompliantClass()],
            [self::makeCall('instance', [AbstractCompliantClass::class]), new CompliantClass()],
            [self::makeCall('instance', [CompliantInterface::class]), new CompliantClass()],
            [self::makeCall('iterable'), [1]],
            [self::makeCall('resource'), tmpfile()],
            [self::makeCall('callable'), 'strlen'],
        ];
    }

    /**
     * @return array<array{\Closure, mixed, string}>
     */
    public static function getInvalidValuesTestCases(): array
    {
        return [
            [self::makeCall('null'), true, 'null'],
            [self::makeCall('bool'), null, 'bool'],
            [self::makeCall('int'), null, 'int'],
            [self::makeCall('float'), null, 'float'],
            [self::makeCall('string'), null, 'string'],
            [self::makeCall('array'), null, 'array'],
            [self::makeCall('list'), null, 'list'],
            [self::makeCall('list'), ['foo' => 'bar'], 'list'],
            [self::makeCall('object'), null, 'object'],
            [self::makeCall('instance', [CompliantClass::class]), null, CompliantClass::class],
            [self::makeCall('instance', [CompliantClass::class]), new NonCompliantClass(), CompliantClass::class],
            [self::makeCall('iterable'), null, 'iterable'],
            [self::makeCall('resource'), null, 'resource'],
            [self::makeCall('callable'), null, 'callable'],
        ];
    }

    public function testInstanceDoesNotAcceptTrait(): void
    {
        $callback = self::makeCall('instance', [CompliantTrait::class]);
        $this->expectException(InvalidClassException::class);
        $callback(null);
    }

    /**
     * @param string $type
     * @param array<int, mixed> $arguments
     * @return \Closure
     */
    private static function makeCall(string $type, array $arguments = []): \Closure
    {
        $callback = static::formatCallback($type);

        if (!\is_callable($callback)) {
            throw new \UnexpectedValueException('Got unexpected return value from formatCallback()');
        }

        $callback = $callback(...);

        return \count($arguments) > 0
            ? static fn(mixed $value): mixed => $callback($value, ...$arguments)
            : $callback;
    }

    /**
     * @param string $name
     * @return array{class-string, string}
     */
    abstract protected static function formatCallback(string $name): array;
}
