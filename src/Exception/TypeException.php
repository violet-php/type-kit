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

    public static function createFromValue(mixed $value, string $expectedType): self
    {
        return new self(
            sprintf("Got unexpected value type '%s', was expecting '%s'", get_debug_type($value), $expectedType)
        );
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

        $index = max($index - 1, 0);

        while ($trace[$index]->file === null || $trace[$index]->line === null) {
            $index++;
        }

        $this->file = $trace[$index]->file;
        $this->line = $trace[$index]->line;
    }
}
