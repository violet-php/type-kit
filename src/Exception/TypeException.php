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
        $index = 0;

        foreach ($trace as $index => $entry) {
            if ($entry->class !== null) {
                foreach (self::IGNORED_CLASSES as $class) {
                    if (is_a($entry->class, $class, true)) {
                        continue 2;
                    }
                }
            }

            break;
        }

        foreach (\array_slice($trace, $index - 1) as $entry) {
            if ($entry->hasLocation()) {
                $this->file = $entry->file;
                $this->line = $entry->line;
                break;
            }
        }
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
