<?php

declare(strict_types=1);

namespace TypeKit\Debug;

use PHPUnit\Framework\TestCase;
use Violet\TypeKit\Debug\Debug;
use Violet\TypeKit\Debug\StackFrame;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class TraceTest extends TestCase
{
    private const INCLUDE_FUNCTIONS = ['include', 'include_once', 'require', 'require_once'];

    public function testDefaultTraceHasNoExtraData(): void
    {
        $trace = $this->filterIncludes(Debug::getBacktrace());

        $this->assertSame(array_fill(0, \count($trace), null), array_column($trace, 'object'));
        $this->assertSame(array_fill(0, \count($trace), []), array_column($trace, 'args'));
    }

    public function testIncludingOnlyArgs(): void
    {
        $trace = $this->filterIncludes(Debug::getBacktrace(includeArguments: true));
        $nulls = array_fill(0, \count($trace), null);

        $this->assertSame(array_fill(0, \count($trace), null), array_column($trace, 'object'));
        $this->assertNotSame(array_fill(0, \count($trace), []), array_column($trace, 'args'));
    }

    public function testIncludingOnlyObject(): void
    {
        $trace = $this->filterIncludes(Debug::getBacktrace(includeObject: true));

        $this->assertNotSame(array_fill(0, \count($trace), null), array_column($trace, 'object'));
        $this->assertSame(array_fill(0, \count($trace), []), array_column($trace, 'args'));
    }

    public function testIncludingExtraData(): void
    {
        $trace = $this->filterIncludes(Debug::getBacktrace(includeArguments: true, includeObject: true));

        $this->assertNotSame(array_fill(0, \count($trace), null), array_column($trace, 'object'));
        $this->assertNotSame(array_fill(0, \count($trace), []), array_column($trace, 'args'));
    }

    public function testTraceContainsValidData(): void
    {
        $line = __LINE__ + 1;
        $trace = $this->filterIncludes(Debug::getBacktrace(includeArguments: true, includeObject: true));
        $top = array_shift($trace);

        while ($top instanceof StackFrame && $top->function !== 'getBacktrace') {
            $top = array_shift($trace);
        }

        $this->assertInstanceOf(StackFrame::class, $top);

        $this->assertSame(__FILE__, $top->file);
        $this->assertSame($line, $top->line);
        $this->assertSame(Debug::class, $top->class);
        $this->assertSame('::', $top->type);
        $this->assertSame('getBacktrace', $top->function);
        $this->assertSame([true, true], $top->args);
        $this->assertNull($top->object);

        $next = array_shift($trace);

        $this->assertInstanceOf(StackFrame::class, $next);
        $this->assertSame($this, $next->object);
    }

    public function testLocationRequiresFileAndLine(): void
    {
        $this->assertTrue((new StackFrame(['line' => __LINE__]))->isInternal());
        $this->assertTrue((new StackFrame([]))->isInternal());
        $this->assertFalse((new StackFrame(['file' => __FILE__, 'line' => __LINE__]))->isInternal());
        $this->assertFalse((new StackFrame(['file' => __FILE__]))->isInternal());
    }

    private function filterIncludes(array $trace): array
    {
        return array_values(array_filter(
            $trace,
            static fn (StackFrame $trace) => !\in_array($trace->function, self::INCLUDE_FUNCTIONS, true)
        ));
    }
}
