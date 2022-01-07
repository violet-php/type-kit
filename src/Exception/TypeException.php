<?php

declare(strict_types=1);

namespace Violet\TypeKit\Exception;

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
        self::class => true,
        Type::class => true,
        Trace::class => true,
    ];

    private function __construct(string $message)
    {
        parent::__construct($message);

        $this->overrideLocation();
    }

    private function overrideLocation(): void
    {
        $trace = Trace::getBacktrace();
        $index = 0;

        foreach ($trace as $index => $entry) {
            if ($entry->class === null || !\array_key_exists($entry->class, self::IGNORED_CLASSES)) {
                break;
            }
        }

        foreach (\array_slice($trace, $index - 1) as $entry) {
            if ($entry->file !== null && $entry->line !== null) {
                $this->file = $entry->file;
                $this->line = $entry->line;
                break;
            }
        }
    }

    public static function createFromValue(mixed $value, string $expectedType): self
    {
        return new self(
            sprintf("Got unexpected value type '%s', was expecting '%s'", self::describeType($value), $expectedType)
        );
    }

    private static function describeType(mixed $value): string
    {
        return match (true) {
            $value === null => 'null',
            \is_bool($value) => 'bool',
            \is_int($value) => 'int',
            \is_float($value) => 'float',
            \is_string($value) => 'string',
            \is_object($value) => $value::class,
            \is_array($value) => self::describeArray($value),
            default => 'resource',
        };
    }

    /**
     * @param array<mixed> $value
     * @return string
     */
    private static function describeArray(array $value): string
    {
        $foundTypes = array_fill_keys(['null', 'bool', 'int', 'float', 'string', 'resource', 'array'], false);

        foreach ($value as $item) {
            $type = match (true) {
                $item === null => 'null',
                \is_bool($item) => 'bool',
                \is_int($item) => 'int',
                \is_float($item) => 'float',
                \is_string($item) => 'string',
                \is_array($item) => 'array',
                \is_object($item) => $item::class,
                default => 'resource',
            };

            $foundTypes[$type] = true;
        }

        $name = array_is_list($value) ? 'list' : 'array';

        return sprintf('%s<%s>', $name, implode('|', array_keys(array_filter($foundTypes))));
    }
}
