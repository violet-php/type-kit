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
    private const SUPPORTED_CLASSES = [
        TypeAs::class,
        TypeAssert::class,
        TypeCast::class,
        TypeIs::class,
    ];

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

    private const SUPPORTED_VARIANTS = [
        '%s',
        '%sArray',
        '%sList',
    ];

    public function testAllTypesAreSupported(): void
    {
        foreach (self::SUPPORTED_CLASSES as $class) {
            $reflection = new \ReflectionClass($class);

            foreach (self::SUPPORTED_TYPES as $type) {
                foreach (self::SUPPORTED_VARIANTS as $format) {
                    $method = sprintf($format, $type);

                    $this->assertTrue(
                        $reflection->hasMethod($method),
                        "Failed asserting that $class has method $method"
                    );

                    $this->assertSame($method, $reflection->getMethod($method)->getName());
                }
            }
        }
    }
}
