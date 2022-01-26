<?php

declare(strict_types=1);

namespace Violet\TypeKit\Pcre;

use Violet\TypeKit\Debug\ErrorHandler;
use Violet\TypeKit\Exception\PcreException;
use Violet\TypeKit\TypeAs;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class Regex
{
    private const NO_LIMIT = -1;

    public readonly string $regex;

    public function __construct(string $regex)
    {
        $this->regex = $regex;
        $this->call(fn () => preg_match($this->regex, ''));
    }

    /**
     * @param string $subject
     * @param int $offset
     * @return array<RegexMatch>|null
     */
    public function match(string $subject, int $offset = 0): ?array
    {
        [$count, $match] = $this->call(function () use ($subject, $offset) {
            $match = [];
            $count = preg_match($this->regex, $subject, $match, PREG_UNMATCHED_AS_NULL | PREG_OFFSET_CAPTURE, $offset);

            return [$count, $match];
        });

        if ($count !== 1) {
            return null;
        }

        $result = [];

        foreach ($match as $name => [$value,  $matchOffset]) {
            if (\is_string($value)) {
                $result[$name] = new RegexMatch($name, $value, $matchOffset);
            }
        }

        return $result;
    }

    /**
     * @param string $subject
     * @param int $offset
     * @return list<array<RegexMatch>>
     */
    public function matchAll(string $subject, int $offset = 0): array
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

        $result = [];

        foreach ($match as $set) {
            $resultSet = [];

            foreach ($set as $name => [$value, $matchOffset]) {
                if (\is_string($value)) {
                    $resultSet[$name] = new RegexMatch($name, $value, $matchOffset);
                }
            }

            $result[] = $resultSet;
        }

        return $result;
    }

    public function matches(string $subject, int $offset = 0): bool
    {
        return $this->call(fn () => preg_match($this->regex, $subject, offset: $offset)) === 1;
    }

    public function replace(string $replacement, string $subject, int $limit = self::NO_LIMIT): string
    {
        return TypeAs::string($this->call(fn () => preg_replace($this->regex, $replacement, $subject, $limit)));
    }

    /**
     * @param \Closure(array<string>):string $callback
     * @param string $subject
     * @param int $limit
     * @return string
     */
    public function replaceCallback(\Closure $callback, string $subject, int $limit = self::NO_LIMIT): string
    {
        return TypeAs::string($this->call(fn () => preg_replace_callback($this->regex, $callback, $subject, $limit)));
    }

    /**
     * @param string $subject
     * @param int $limit
     * @return list<string>
     */
    public function split(string $subject, int $limit = self::NO_LIMIT): array
    {
        return TypeAs::stringList($this->call(fn () => preg_split($this->regex, $subject, $limit)));
    }

    /**
     * @param array<string> $subjects
     * @return array<string>
     */
    public function grep(array $subjects): array
    {
        return TypeAs::stringArray($this->call(fn () => preg_grep($this->regex, $subjects)));
    }

    /**
     * @param array<string> $subjects
     * @return array<string>
     */
    public function reject(array $subjects): array
    {
        return TypeAs::stringArray($this->call(fn () => preg_grep($this->regex, $subjects, PREG_GREP_INVERT)));
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
        } catch (\Throwable $exception) {
            $message = preg_replace('/^preg_[a-z0-9_]+\(\):\s*/', '', $exception->getMessage());
            throw new PcreException('Error in regular expression: ' . $message, 0, $exception);
        }

        if (preg_last_error() !== PREG_NO_ERROR) {
            throw new PcreException('Error in regular expression: ' . preg_last_error_msg());
        }

        return $result;
    }
}
