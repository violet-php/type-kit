<?php

declare(strict_types=1);

namespace Violet\TypeKit\Debug;

use PHPUnit\Framework\TestCase;
use Violet\TypeKit\Exception\ErrorException;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class ErrorHandlerTest extends TestCase
{
    public function testHandlingCall(): void
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('Test Error');

        ErrorHandler::handleCall(static fn() => trigger_error('Test Error', E_USER_NOTICE));
    }

    public function testHandlingCallReturnValue(): void
    {
        $this->assertSame(123, ErrorHandler::handleCall(static fn() => 123));
    }

    public function testThrowingError(): void
    {
        $file = __FILE__;
        $line = __LINE__;

        try {
            ErrorHandler::throwErrorException(E_USER_NOTICE, 'Test error', $file, $line);
        } catch (ErrorException $exception) {
            $this->assertSame('Test error', $exception->getMessage());
            $this->assertSame(0, $exception->getCode());
            $this->assertSame(E_USER_NOTICE, $exception->getSeverity());
            $this->assertSame($file, $exception->getFile());
            $this->assertSame($line, $exception->getLine());
        }
    }
}
