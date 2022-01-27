<?php

declare(strict_types=1);

namespace Violet\TypeKit;

use PHPUnit\Framework\TestCase;
use Violet\TypeKit\PhpUnit\AbstractCompliantClass;
use Violet\TypeKit\PhpUnit\CompliantClass;
use Violet\TypeKit\PhpUnit\CompliantInterface;
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
    public function getValidValuesTestCases(): array
    {
        return [
            [$this->makeCall('null'), null],
            [$this->makeCall('bool'), true],
            [$this->makeCall('int'), 1],
            [$this->makeCall('float'), 1.1],
            [$this->makeCall('string'), 'foobar'],
            [$this->makeCall('array'), []],
            [$this->makeCall('array'), [1]],
            [$this->makeCall('array'), [1, 2, 3]],
            [$this->makeCall('array'), ['foo' => 'bar']],
            [$this->makeCall('list'), []],
            [$this->makeCall('list'), [1]],
            [$this->makeCall('list'), [1, 2, 3]],
            [$this->makeCall('object'), new CompliantClass()],
            [$this->makeCall('instance', [CompliantClass::class]), new CompliantClass()],
            [$this->makeCall('instance', [AbstractCompliantClass::class]), new CompliantClass()],
            [$this->makeCall('instance', [CompliantInterface::class]), new CompliantClass()],
            [$this->makeCall('iterable'), [1]],
            [$this->makeCall('resource'), tmpfile()],
            [$this->makeCall('callable'), 'strlen'],
        ];
    }

    /**
     * @return array<array{\Closure, mixed, string}>
     */
    public function getInvalidValuesTestCases(): array
    {
        return [
            [$this->makeCall('null'), true, 'null'],
            [$this->makeCall('bool'), null, 'bool'],
            [$this->makeCall('int'), null, 'int'],
            [$this->makeCall('float'), null, 'float'],
            [$this->makeCall('string'), null, 'string'],
            [$this->makeCall('array'), null, 'array'],
            [$this->makeCall('list'), null, 'list'],
            [$this->makeCall('list'), ['foo' => 'bar'], 'list'],
            [$this->makeCall('object'), null, 'object'],
            [$this->makeCall('instance', [CompliantClass::class]), null, CompliantClass::class],
            [$this->makeCall('instance', [CompliantClass::class]), new NonCompliantClass(), CompliantClass::class],
            [$this->makeCall('iterable'), null, 'iterable'],
            [$this->makeCall('resource'), null, 'resource'],
            [$this->makeCall('callable'), null, 'callable'],
        ];
    }

    /**
     * @param string $type
     * @param array<mixed> $arguments
     * @return \Closure
     */
    private function makeCall(string $type, array $arguments = []): \Closure
    {
        $callback = $this->formatCallback($type);

        if (!\is_callable($callback)) {
            $this->fail('Got unexpected return value from formatCallback()');
        }

        $callback = \Closure::fromCallable($callback);

        return \count($arguments) > 0
            ? static fn (mixed $value): mixed => $callback($value, ... $arguments)
            : $callback;
    }

    /**
     * @param string $name
     * @return array{class-string, string}
     */
    abstract protected function formatCallback(string $name): array;
}
