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
            [['null'], null],
            [['bool'], true],
            [['int'], 1],
            [['float'], 1.1],
            [['string'], 'foobar'],
            [['array'], [1]],
            [['array'], ['foo' => 'bar']],
            [['list'], [1]],
            [['object'], new CompliantClass()],
            [['instance', CompliantClass::class], new CompliantClass()],
            [['instance', AbstractCompliantClass::class], new CompliantClass()],
            [['instance', CompliantInterface::class], new CompliantClass()],
            [['iterable'], [1]],
            [['resource'], tmpfile()],
            [['callable'], strlen(...)],
        ];
    }

    public function getInvalidValuesTestCases(): array
    {
        return [
            [['null'], true, 'null'],
            [['bool'], null, 'bool'],
            [['int'], null, 'int'],
            [['float'], null, 'float'],
            [['string'], null, 'string'],
            [['array'], null, 'array'],
            [['list'], null, 'list'],
            [['list'], ['foo' => 'bar'], 'list'],
            [['object'], null, 'object'],
            [['instance', CompliantClass::class], null, CompliantClass::class],
            [['instance', CompliantClass::class], new NonCompliantClass(), CompliantClass::class],
            [['iterable'], null, 'iterable'],
            [['resource'], null, 'resource'],
            [['callable'], null, 'callable'],
        ];
    }

    protected function getCallback(array $call): \Closure
    {
        $name = array_shift($call);
        $callback = $this->formatCallback($name);

        return \count($call) > 0
            ? static fn (mixed $item) => $callback($item, ... $call)
            : $callback;
    }

    abstract protected function formatCallback(string $name): \Closure;
}
