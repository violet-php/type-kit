<?php

declare(strict_types=1);

namespace TypeKit\Debug;

use PHPUnit\Framework\TestCase;
use Violet\TypeKit\Debug\Trace;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class TraceTest extends TestCase
{
    public function testDefaultTraceHasNoExtraData(): void
    {
        $trace = Trace::getBacktrace();
        $nulls = array_fill(0, \count($trace), null);

        $this->assertSame($nulls, array_column($trace, 'object'));
        $this->assertSame($nulls, array_column($trace, 'args'));
    }

    public function testIncludingOnlyArgs(): void
    {
        $trace = Trace::getBacktrace(Trace::INCLUDE_ARGUMENTS);
        $nulls = array_fill(0, \count($trace), null);

        $this->assertSame($nulls, array_column($trace, 'object'));
        $this->assertNotSame($nulls, array_column($trace, 'args'));
    }

    public function testIncludingOnlyObject(): void
    {
        $trace = Trace::getBacktrace(Trace::INCLUDE_OBJECT);
        $nulls = array_fill(0, \count($trace), null);

        $this->assertNotSame($nulls, array_column($trace, 'object'));
        $this->assertSame($nulls, array_column($trace, 'args'));
    }

    public function testIncludingExtraData(): void
    {
        $trace = Trace::getBacktrace(Trace::INCLUDE_ARGUMENTS | Trace::INCLUDE_OBJECT);
        $nulls = array_fill(0, \count($trace), null);

        $this->assertNotSame($nulls, array_column($trace, 'object'));
        $this->assertNotSame($nulls, array_column($trace, 'args'));
    }

    public function testTraceContainsValidData(): void
    {
        $line = __LINE__ + 1;
        $trace = Trace::getBacktrace(Trace::INCLUDE_ARGUMENTS | Trace::INCLUDE_OBJECT);
        $top = array_shift($trace);

        while ($top instanceof Trace && $top->function !== 'getBacktrace') {
            $top = array_shift($trace);
        }

        $this->assertInstanceOf(Trace::class, $top);

        $this->assertSame(__FILE__, $top->file);
        $this->assertSame($line, $top->line);
        $this->assertSame(Trace::class, $top->class);
        $this->assertSame('::', $top->type);
        $this->assertSame('getBacktrace', $top->function);
        $this->assertSame([Trace::INCLUDE_ARGUMENTS | Trace::INCLUDE_OBJECT], $top->args);
        $this->assertNull($top->object);

        $next = array_shift($trace);

        $this->assertInstanceOf(Trace::class, $next);
        $this->assertSame($this, $next->object);
    }

    public function testLocationRequiresFileAndLine(): void
    {
        $this->assertTrue((new Trace(['file' => __FILE__, 'line' => __LINE__]))->hasLocation());
        $this->assertFalse((new Trace(['file' => __FILE__]))->hasLocation());
        $this->assertFalse((new Trace(['line' => __LINE__]))->hasLocation());
    }
}
