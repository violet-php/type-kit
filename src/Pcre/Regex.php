<?php

declare(strict_types=1);

namespace Violet\TypeKit\Pcre;

use Violet\TypeKit\Debug\ErrorHandler;
use Violet\TypeKit\Exception\ErrorException;
use Violet\TypeKit\Exception\PcreException;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class Regex
{
    private readonly string $regex;

    public function __construct(string $regex)
    {
        $this->call(static fn () => preg_match($regex, ''));
        $this->regex = $regex;
    }

    public function match(string $subject, int $offset = 0): ?array
    {
        $matches = [];
        $count = $this->call(function () use ($subject, $offset, &$matches) {
            return preg_match($this->regex, $subject, $matches, PREG_UNMATCHED_AS_NULL, $offset);
        });

        if ($count === 0) {
            return null;
        }

        return array_filter($matches, static fn ($value) => \is_string($value));
    }

    public function matchOffsets(string $subject, int $offset = 0): ?array
    {
        $matches = [];
        $count = $this->call(function () use ($subject, $offset, &$matches) {
            return preg_match($this->regex, $subject, $matches, PREG_UNMATCHED_AS_NULL | PREG_OFFSET_CAPTURE, $offset);
        });

        if ($count === 0) {
            return null;
        }

        return array_filter($matches, static fn (array $value) => \is_string($value[0]));
    }

    public function matches(string $subject, int $offset = 0): bool
    {
        return $this->call(fn () => preg_match($this->regex, $subject, offset: $offset)) === 1;
    }

    public function matchAll(string $subject, int $offset = 0): array
    {
        $matches = [];
        $count = $this->call(function () use ($subject, $offset, &$matches) {
            return preg_match_all($this->regex, $subject, $matches, PREG_UNMATCHED_AS_NULL | PREG_SET_ORDER, $offset);
        });

        if ($count === 0) {
            return [];
        }

        return array_map(
            static fn ($set) => array_filter($matches, static fn ($value) => \is_string($value)),
            $matches
        );
    }

    public function matchAllOffsets(string $subject, int $offset = 0): array
    {
        $matches = [];
        $count = $this->call(function () use ($subject, $offset, &$matches) {
            return preg_match_all(
                $this->regex,
                $subject,
                $matches,
                PREG_UNMATCHED_AS_NULL | PREG_SET_ORDER | PREG_OFFSET_CAPTURE,
                $offset
            );
        });

        if ($count === 0) {
            return [];
        }

        return array_map(
            static fn ($set) => array_filter($matches, static fn ($value) => \is_string($value[0])),
            $matches
        );
    }

    public function replace(string $replacement, string $subject, int $limit = -1): string
    {
        return $this->call(fn () => preg_replace($this->regex, $replacement, $subject, $limit));
    }

    public function replaceCallback(\Closure $callback, string $subject, int $limit = -1): string
    {
        return $this->call(fn () => preg_replace_callback($this->regex, $callback, $subject, $limit));
    }

    public function split(string $subject, int $limit = -1): array
    {
        return $this->call(fn () => preg_split($this->regex, $subject, $limit));
    }

    public function grep(array $subjects): array
    {
        return $this->call(fn () => preg_grep($this->regex, $subjects));
    }

    public function reject(array $subjects): array
    {
        return $this->call(fn () => preg_grep($this->regex, $subjects, PREG_GREP_INVERT));
    }

    /**
     * @param string $string
     * @return string
     * @see https://github.com/php/php-src/blob/master/ext/pcre/php_pcre.c
     */
    public function quote(string $string): string
    {
        return preg_quote($string, $this->regex[strspn($this->regex, "\x09\x0A\x0B\x0C\x0D\x20")]);
    }

    private function call(\Closure $closure): mixed
    {
        try {
            $result = ErrorHandler::handleCall($closure);

            if (preg_last_error() !== PREG_NO_ERROR) {
                throw new PcreException('Error in regular expression: ' . preg_last_error_msg());
            }

            return $result;
        } catch (ErrorException $exception) {
            $message = preg_replace('/^preg_[a-z0-9_]+\(\):\s*/', '', $exception->getMessage());
            throw new PcreException('Error in regular expression: ' . $message, 0, $exception);
        }
    }
}
