<?php

declare(strict_types=1);

namespace Violet\TypeKit\Exception;

use Violet\TypeKit\Debug\Debug;
use Violet\TypeKit\Type\TypeAs;
use Violet\TypeKit\Type\TypeAssert;
use Violet\TypeKit\Type\TypeCast;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class InvalidTypeException extends \UnexpectedValueException implements TypeKitException
{
    private const IGNORED_CLASSES = [
        self::class,
        TypeAs::class,
        TypeAssert::class,
        TypeCast::class,
    ];

    public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        [$caller] = Debug::getCallerBacktrace(self::IGNORED_CLASSES);
        $this->file = $caller->file;
        $this->line = $caller->line;
    }
}
