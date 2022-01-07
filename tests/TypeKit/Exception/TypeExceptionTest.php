<?php

declare(strict_types=1);

namespace Violet\TypeKit\Exception;

use PHPUnit\Framework\TestCase;
use Violet\TypeKit\Type;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class TypeExceptionTest extends TestCase
{
    public function testFileAndLine(): void
    {
        $line = __LINE__ + 1;
        $exception = TypeException::createFromValue(1, 'string');

        $this->assertSame(__FILE__, $exception->getFile());
        $this->assertSame($line, $exception->getLine());
    }

    public function testFileAndLineViaInternalCall(): void
    {
        $line = __LINE__ + 1;
        $result = \call_user_func(array_map(...), TypeException::createFromValue(...), [1], ['string']);

        $this->assertIsArray($result);
        $this->assertArrayHasKey(0, $result);
        $this->assertInstanceOf(TypeException::class, $result[0]);
        $this->assertSame(__FILE__, $result[0]->getFile());
        $this->assertSame($line, $result[0]->getLine());
    }

    public function testFileAndLineViaTypeClass(): void
    {
        $line = 0;

        try {
            $line = __LINE__ + 1;
            Type::string(1);
            $this->fail('Failed to throw expected exception');
        } catch (TypeException $exception) {
            $this->assertSame(__FILE__, $exception->getFile());
            $this->assertSame($line, $exception->getLine());
        }
    }
}
