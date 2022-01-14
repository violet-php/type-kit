<?php

declare(strict_types=1);

namespace Violet\TypeKit\Pcre;

use PHPUnit\Framework\TestCase;
use Violet\TypeKit\Exception\PcreException;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class RegexTest extends TestCase
{
    public function testInvalidPattern(): void
    {
        $this->expectException(PcreException::class);
        $this->expectExceptionMessage('Error in regular expression: No ending delimiter');
        $this->expectExceptionCode(0);

        new Regex('/foo');
    }

    public function testNoFunctionNameInError(): void
    {
        try {
            new Regex('/foo');
            $this->fail('Failed to throw the expected exception');
        } catch (PcreException $exception) {
            $this->assertStringNotContainsStringIgnoringCase('preg_', $exception->getMessage());
        }
    }

    public function testErrorWhileMatching(): void
    {
        $regex = new Regex('/(?:\D+|<\d+>)*[!?]/');

        $this->expectException(PcreException::class);
        $this->expectExceptionMessage('Error in regular expression: Backtrack limit exhausted');
        $this->expectExceptionCode(0);

        $regex->match('foobar foobar foobar');
    }

    public function testSuccessfulMatch(): void
    {
        $regex = new Regex('/foo/');
        $result = $regex->match('foo');

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey(0, $result);
        $this->assertSame('foo', (string)$result[0]);
    }

    public function testNoMatchReturnsNull(): void
    {
        $regex = new Regex('/foo/');
        $this->assertNull($regex->match('bar'));
    }

    public function testMatchReturnsResults(): void
    {
        $regex = new Regex("/f(o)(?'name'o)/");
        $this->assertMatches([
            0 => ['foo', 0],
            1 => ['o', 1],
            'name' => ['o', 2],
            2 => ['o', 2],
        ], $regex->match('foo'));
    }

    public function testOffsetNoMatch(): void
    {
        $regex = new Regex('/foo/');
        $this->assertNull($regex->match('foo', 1));
    }

    public function testNoUnmatchedResults(): void
    {
        $regex = new Regex('/foo(bar)?/');
        $this->assertMatches([0 => ['foo', 0]], $regex->match('foo'));
    }

    public function testSuccessfulMatchAll(): void
    {
        $regex = new Regex('/foo/');
        $this->assertCount(2, $regex->matchAll('/foofoo/'));
    }

    public function testNoMatchAllReturnsEmptyArray(): void
    {
        $regex = new Regex('/foo/');
        $this->assertCount(0, $regex->matchAll('bar'));
    }

    public function testMatchAllReturnsResults(): void
    {
        $regex = new Regex("/f(o)(?'name'o)/");
        $this->assertAllMatches([
            [
                0 => ['foo', 0],
                1 => ['o', 1],
                'name' => ['o', 2],
                2 => ['o', 2],
            ],
            [
                0 => ['foo', 3],
                1 => ['o', 4],
                'name' => ['o', 5],
                2 => ['o', 5],
            ]
        ], $regex->matchAll('foofoo'));
    }

    public function testOffsetNoMatchAll(): void
    {
        $regex = new Regex('/foo/');
        $this->assertCount(0, $regex->matchAll('foo', 1));
    }

    public function testNoUnmatchedAllResults(): void
    {
        $regex = new Regex('/foo(bar)?/');
        $this->assertAllMatches([[0 => ['foo', 0]]], $regex->matchAll('foo'));
    }

    public function testMatches(): void
    {
        $regex = new Regex('/foo/');

        $this->assertTrue($regex->matches('foo'));
        $this->assertFalse($regex->matches('bar'));
        $this->assertFalse($regex->matches('foo', 1));
    }

    public function testReplace(): void
    {
        $regex = new Regex('/foo/');
        $this->assertSame('barbar', $regex->replace('bar', 'foofoo'));
    }

    public function testReplaceLimit(): void
    {
        $regex = new Regex('/foo/');
        $this->assertSame('barfoo', $regex->replace('bar', 'foofoo', 1));
    }

    public function testReplaceCallback(): void
    {
        $regex = new Regex('/foo/');
        $this->assertSame('barfoobarfoo', $regex->replaceCallback(fn ($match) => "bar$match[0]", 'foofoo'));
    }

    public function testReplaceCallbackLimit(): void
    {
        $regex = new Regex('/foo/');
        $this->assertSame('barfoofoo', $regex->replaceCallback(fn ($match) => "bar$match[0]", 'foofoo', 1));
    }

    public function testSplit(): void
    {
        $regex = new Regex('/foo/');
        $this->assertSame(['bar', 'bar', 'bar'], $regex->split('barfoobarfoobar'));
    }

    public function testSplitLimit(): void
    {
        $regex = new Regex('/foo/');
        $this->assertSame(['bar', 'barfoobar'], $regex->split('barfoobarfoobar', 2));
    }

    public function testGrep(): void
    {
        $regex = new Regex('/foo/');
        $this->assertSame([0 => 'foo', 2 => 'foobar'], $regex->grep(['foo', 'bar', 'foobar']));
    }

    public function testReject(): void
    {
        $regex = new Regex('/foo/');
        $this->assertSame([1 => 'bar'], $regex->reject(['foo', 'bar', 'foobar']));
    }

    public function testQuote(): void
    {
        $regex = new Regex('_foo_');
        $this->assertSame('foo\\_bar', $regex->quote('foo_bar'));
    }

    /**
     * @param array<array{string, int}> $expected
     * @param ?array<RegexMatch> $actual
     * @return void
     */
    private function assertMatches(array $expected, ?array $actual): void
    {
        $this->assertIsArray($actual);
        $this->assertSame(array_keys($expected), array_keys($actual));

        $actualArray = [];

        foreach ($actual as $match) {
            $actualArray[$match->name] = [$match->value, $match->offset];
        }

        $this->assertSame($expected, $actualArray);
    }

    /**
     * @param list<array<array{string, int}>> $expected
     * @param list<array<RegexMatch>> $actual
     * @return void
     */
    private function assertAllMatches(array $expected, array $actual): void
    {
        $this->assertSame(array_keys($expected), array_keys($actual));

        foreach ($expected as $key => $expectedMatch) {
            $this->assertMatches($expectedMatch, $actual[$key]);
        }
    }
}
