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
        $this->function = isset($trace['function']) ? Type::string($trace['function']) : null;
        $this->line = isset($trace['line']) ? Type::int($trace['line']) : null;
        $this->file = isset($trace['file']) ? Type::string($trace['file']) : null;
        $this->class = isset($trace['class']) ? Type::string($trace['class']) : null;
        $this->object = isset($trace['object']) ? Type::object($trace['object']) : null;
        $this->type = isset($trace['type']) ? Type::string($trace['type']) : null;
        $this->args = isset($trace['args']) ? Type::list($trace['args']) : null;
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
