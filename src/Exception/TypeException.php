<?php

declare(strict_types=1);

namespace Violet\TypeKit\Exception;

use Violet\TypeKit\Assert;
use Violet\TypeKit\Cast;
use Violet\TypeKit\Debug\Trace;
use Violet\TypeKit\Type;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class TypeException extends \UnexpectedValueException implements TypeKitException
{
    private const IGNORED_CLASSES = [
        TypeKitException::class,
        Trace::class,
        Type::class,
        Assert::class,
        Cast::class,
    ];

    protected function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, previous: $previous);

        $this->overrideLocation();
    }

    private function overrideLocation(): void
    {
        $trace = Trace::getBacktrace();

        do {
            $caller = array_shift($trace);
        } while ($this->isIgnored($trace[0]));

        while (!$caller->hasLocation()) {
            $caller = array_shift($trace);
        }

        $this->file = $caller->file;
        $this->line = $caller->line;
    }

    private function isIgnored(Trace $entry): bool
    {
        foreach (self::IGNORED_CLASSES as $class) {
            if (is_a($entry->class, $class, true)) {
                return true;
            }
        }

        return false;
    }

    public static function createFromValue(mixed $value, string $expectedType): self
    {
        return new static(
            sprintf("Got unexpected value type '%s', was expecting '%s'", self::describeType($value), $expectedType)
        );
    }

    protected static function describeType(mixed $value): string
    {
        if (\is_array($value)) {
            $types = array_map(get_debug_type(...), $value);
            $type = array_is_list($value) ? 'list' : 'array';
            return sprintf('%s<%s>', $type, implode('|', array_unique($types)));
        }

        return get_debug_type($value);
    }
}
