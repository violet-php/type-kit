<?php

declare(strict_types=1);

namespace Violet\TypeKit\Debug;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class Debug
{
    public static function describeType(mixed $value): string
    {
        if (\is_array($value)) {
            $types = array_map('get_debug_type', $value);
            $type = array_is_list($value) ? 'list' : 'array';
            return sprintf('%s<%s>', $type, implode('|', array_unique($types)));
        }

        return get_debug_type($value);
    }

    /**
     * @param bool $includeArguments
     * @param bool $includeObject
     * @param int $depth
     * @return list<StackFrame>
     */
    public static function getBacktrace(
        bool $includeArguments = false,
        bool $includeObject = false,
        int $depth = 0
    ): array {
        $callOptions =
            ($includeArguments ? 0 : DEBUG_BACKTRACE_IGNORE_ARGS) |
            ($includeObject ? DEBUG_BACKTRACE_PROVIDE_OBJECT : 0);

        return array_map(
            static fn (array $entry) => new StackFrame($entry),
            debug_backtrace($callOptions, $depth)
        );
    }

    /**
     * @param array<class-string> $ignoredClasses
     * @return list<StackFrame>
     */
    public static function getCallerBacktrace(array $ignoredClasses = []): array
    {
        $trace = \array_slice(self::getBacktrace(), 2);

        do {
            $top = array_shift($trace);
        } while ($trace !== [] && self::shouldIgnoreFrame($trace[0], $ignoredClasses));

        while ($top->isInternal()) {
            $top = array_shift($trace);
        }

        array_unshift($trace, $top);

        return $trace;
    }

    /**
     * @param StackFrame $frame
     * @param array<class-string> $ignoredClasses
     * @return bool
     */
    private static function shouldIgnoreFrame(StackFrame $frame, array $ignoredClasses): bool
    {
        foreach ($ignoredClasses as $class) {
            if (is_a($frame->class, $class, true)) {
                return true;
            }
        }

        return false;
    }
}
