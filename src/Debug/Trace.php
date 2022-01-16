<?php

declare(strict_types=1);

namespace Violet\TypeKit\Debug;

use Violet\TypeKit\Type;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class Trace
{
    public const INCLUDE_ARGUMENTS = 1;
    public const INCLUDE_OBJECT = 2;

    public readonly ?string $function;
    public readonly ?int $line;
    public readonly ?string $file;
    public readonly ?string $class;
    public readonly ?object $object;
    public readonly ?string $type;

    /** @var list<mixed>|null  */
    public readonly ?array $args;

    /**
     * @param array<string, mixed> $trace
     */
    public function __construct(array $trace)
    {
        $this->function = isset($trace['function']) && \is_string($trace['function']) ? $trace['function'] : null;
        $this->line = isset($trace['line']) && \is_int($trace['line']) ? $trace['line'] : null;
        $this->file = isset($trace['file']) && \is_string($trace['file']) ? $trace['file'] : null;
        $this->class = isset($trace['class']) && \is_string($trace['class']) ? $trace['class'] : null;
        $this->object = isset($trace['object']) && \is_object($trace['object']) ? $trace['object'] : null;
        $this->type = isset($trace['type']) && \is_string($trace['type']) ? $trace['type'] : null;
        $this->args = isset($trace['args']) && \is_array($trace['args']) ? $trace['args'] : null;
    }

    /**
     * @param bool $includeArguments
     * @param bool $includeObject
     * @param int $depth
     * @return list<self>
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
            static fn (array $entry) => new self($entry),
            debug_backtrace($callOptions, $depth)
        );
    }

    public function hasLocation(): bool
    {
        return $this->file !== null && $this->line !== null;
    }
}
