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
    public function getValidValuesTestCases(): array
    {
        return [
            [$this->makeCall('null'), null],
            [$this->makeCall('bool'), true],
            [$this->makeCall('int'), 1],
            [$this->makeCall('float'), 1.1],
            [$this->makeCall('string'), 'foobar'],
            [$this->makeCall('array'), [1]],
            [$this->makeCall('array'), ['foo' => 'bar']],
            [$this->makeCall('list'), [1]],
            [$this->makeCall('object'), new CompliantClass()],
            [$this->makeCall('instance', [CompliantClass::class]), new CompliantClass()],
            [$this->makeCall('instance', [AbstractCompliantClass::class]), new CompliantClass()],
            [$this->makeCall('instance', [CompliantInterface::class]), new CompliantClass()],
            [$this->makeCall('iterable'), [1]],
            [$this->makeCall('resource'), tmpfile()],
            [$this->makeCall('callable'), strlen(...)],
        ];
    }

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

    private function makeCall(string $type, array $arguments = []): \Closure
    {
        $callback = $this->formatCallback($type);

        return \count($arguments) > 0
            ? static fn (mixed $value) => $callback($value, ... $arguments)
            : $callback;
    }

    abstract protected function formatCallback(string $name): \Closure;
}
