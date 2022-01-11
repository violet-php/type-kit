<?php

declare(strict_types=1);

namespace Violet\TypeKit;

use PHPUnit\Framework\TestCase;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class SupportedTypesTest extends TestCase
{
    private const SUPPORTED_TYPES = [
        'null',
        'bool',
        'int',
        'float',
        'string',
        'array',
        'list',
        'object',
        'instance',
        'iterable',
        'resource',
        'callable',
    ];

    private const TYPE_METHODS = [
        [Type::class, '%s'],
        [Type::class, '%sArray'],
        [Type::class, '%sList'],
        [Type::class, 'is%sArray'],
        [Type::class, 'is%sList'],
        [Assert::class, '%s'],
        [Assert::class, '%sArray'],
        [Assert::class, '%sList'],
        [Cast::class, '%s'],
        [Cast::class, '%sArray'],
        [Cast::class, '%sList'],
    ];

    public function testAllTypesAreSupported(): void
    {
        foreach (self::SUPPORTED_TYPES as $type) {
            $upperName = ucfirst($type);

            foreach (self::TYPE_METHODS as [$class, $format]) {
                $method = lcfirst(sprintf($format, $upperName));
                $class = new \ReflectionClass($class);

                $this->assertTrue($class->hasMethod($method));
                $this->assertSame($method, $class->getMethod($method)->getName());
            }
        }
    }
}
