<?php

declare(strict_types=1);

namespace Violet\TypeKit\Debug;

use Violet\TypeKit\Type;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class StackFrame
{
    public const INCLUDE_ARGUMENTS = 1;
    public const INCLUDE_OBJECT = 2;
    public const INTERNAL_FRAME = '[internal function]';

    public readonly ?string $function;
    public readonly int $line;
    public readonly string $file;
    public readonly ?string $class;
    public readonly ?object $object;
    public readonly ?string $type;

    /** @var array<mixed>  */
    public readonly array $args;
    private readonly bool $hasArguments;

    /**
     * @param array<string, mixed> $trace
     */
    public function __construct(array $trace)
    {
        $this->function = isset($trace['function']) && \is_string($trace['function']) ? $trace['function'] : null;
        $this->line = isset($trace['line']) && \is_int($trace['line']) ? $trace['line'] : -1;
        $this->file = isset($trace['file']) && \is_string($trace['file']) ? $trace['file'] : self::INTERNAL_FRAME;
        $this->class = isset($trace['class']) && \is_string($trace['class']) ? $trace['class'] : null;
        $this->object = isset($trace['object']) && \is_object($trace['object']) ? $trace['object'] : null;
        $this->type = isset($trace['type']) && \is_string($trace['type']) ? $trace['type'] : null;
        $this->args = isset($trace['args']) && \is_array($trace['args']) ? $trace['args'] : [];
        $this->hasArguments = isset($trace['args']) && \is_array($trace['args']);
    }

    public function isInternal(): bool
    {
        return $this->file === self::INTERNAL_FRAME;
    }

    public function hasArguments(): bool
    {
        return $this->hasArguments;
    }

    public function __toString(): string
    {
        $arguments = [];

        /** @var mixed $value */
        foreach ($this->args as $name => $value) {
            $arguments[] = \is_string($name)
                ? sprintf('%s: %s', $name, Debug::describeType($value))
                : Debug::describeType($value);
        }

        $call = sprintf(
            '%s%s%s(%s)',
            $this->class ?? '',
            $this->type ?? '',
            $this->function ?? '',
            implode(', ', $arguments)
        );

        return $this->isInternal()
            ? sprintf('%s: %s', self::INTERNAL_FRAME, $call)
            : sprintf('%s(%d): %s', $this->file, $this->line, $call);
    }
}
