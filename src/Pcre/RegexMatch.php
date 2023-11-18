<?php

declare(strict_types=1);

namespace Violet\TypeKit\Pcre;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class RegexMatch
{
    public function __construct(
        public readonly int|string $name,
        public readonly string $value,
        public readonly int $offset
    ) {}

    public function __toString(): string
    {
        return $this->value;
    }
}
