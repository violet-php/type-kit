<?php

declare(strict_types=1);

namespace Violet\TypeKit\Pcre;

use Violet\TypeKit\Debug\ErrorHandler;
use Violet\TypeKit\Exception\ErrorException;
use Violet\TypeKit\Exception\PcreException;
use Violet\TypeKit\Type;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class Regex
{
    public readonly string $regex;

    public function __construct(string $regex)
    {
        $this->regex = $regex;
        $this->call(fn () => preg_match($this->regex, ''));
    }

    /**
     * @param string $subject
     * @param int $offset
     * @return array<string>|null
     */
    public function match(string $subject, int $offset = 0): ?array
    {
        [$count, $match] = $this->call(function () use ($subject, $offset) {
            $match = [];
            $count = preg_match($this->regex, $subject, $match, PREG_UNMATCHED_AS_NULL, $offset);

            return [$count, $match];
        });

        if ($count !== 1) {
            return null;
        }

        return array_filter($match, \is_string(...));
    }

    /**
     * @param string $subject
     * @param int $offset
     * @return array<array{string, int}>|null
     */
    public function matchOffset(string $subject, int $offset = 0): ?array
    {
        [$count, $match] = $this->call(function () use ($subject, $offset) {
            $match = [];
            $count = preg_match($this->regex, $subject, $match, PREG_UNMATCHED_AS_NULL | PREG_OFFSET_CAPTURE, $offset);

            return [$count, $match];
        });

        if ($count !== 1) {
            return null;
        }

        return array_filter($match, static fn (mixed $value) => \is_string($value[0]));
    }

    /**
     * @param string $subject
     * @param int $offset
     * @return list<array<string>>
     */
    public function matchAll(string $subject, int $offset = 0): array
    {
        [$count, $match] = $this->call(function () use ($subject, $offset) {
            $match = [];
            $count = preg_match_all($this->regex, $subject, $match, PREG_UNMATCHED_AS_NULL | PREG_SET_ORDER, $offset);

            return [$count, $match];
        });

        if ($count < 1) {
            return [];
        }

        return array_map(
            static fn (array $set) => array_filter($set, \is_string(...)),
            $match
        );
    }

    /**
     * @param string $subject
     * @param int $offset
     * @return list<array<array{string, int}>>
     */
    public function matchAllOffsets(string $subject, int $offset = 0): array
    {
        [$count, $match] = $this->call(function () use ($subject, $offset) {
            $match = [];
            $flags = PREG_UNMATCHED_AS_NULL | PREG_SET_ORDER | PREG_OFFSET_CAPTURE;
            $count = preg_match_all($this->regex, $subject, $match, $flags, $offset);

            return [$count, $match];
        });

        if ($count < 1) {
            return [];
        }

        return array_map(
            static fn (array $set) => array_filter($set, static fn (mixed $value) => \is_string($value[0])),
            $match
        );
    }

    public function matches(string $subject, int $offset = 0): bool
    {
        return $this->call(fn () => preg_match($this->regex, $subject, offset: $offset)) === 1;
    }

    public function replace(string $replacement, string $subject, int $limit = -1): string
    {
        return Type::string($this->call(fn () => preg_replace($this->regex, $replacement, $subject, $limit)));
    }

    /**
     * @param \Closure(array<string>):string $callback
     * @param string $subject
     * @param int $limit
     * @return string
     */
    public function replaceCallback(\Closure $callback, string $subject, int $limit = -1): string
    {
        return Type::string($this->call(fn () => preg_replace_callback($this->regex, $callback, $subject, $limit)));
    }

    /**
     * @param string $subject
     * @param int $limit
     * @return list<string>
     */
    public function split(string $subject, int $limit = -1): array
    {
        return Type::stringList($this->call(fn () => preg_split($this->regex, $subject, $limit)));
    }

    /**
     * @param array<string> $subjects
     * @return array<string>
     */
    public function grep(array $subjects): array
    {
        return Type::stringArray($this->call(fn () => preg_grep($this->regex, $subjects)));
    }

    /**
     * @param array<string> $subjects
     * @return array<string>
     */
    public function reject(array $subjects): array
    {
        return Type::stringArray($this->call(fn () => preg_grep($this->regex, $subjects, PREG_GREP_INVERT)));
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

    /**
     * @template T
     * @param \Closure():T $closure
     * @return T
     */
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