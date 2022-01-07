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
        $this->expectExceptionMessage('No ending delimiter');

        new Regex('/foo');
    }

    public function testNoFunctionNameInEerror(): void
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
        $this->expectExceptionMessage('Backtrack limit exhausted');

        $regex->match('foobar foobar foobar');
    }

    public function testNoMatchReturnsNull(): void
    {
        $regex = new Regex('/foo/');
        $this->assertNull($regex->match('bar'));
    }

    public function testMatchReturnsResults(): void
    {
        $regex = new Regex("/f(o)(?'name'o)/");
        $result = $regex->match('foo');

        $this->assertSame([
            0 => 'foo',
            1 => 'o',
            'name' => 'o',
            2 => 'o',
        ], $result);
    }

    public function testOffsetNoMatch(): void
    {
        $regex = new Regex('/foo/');
        $this->assertNull($regex->match('foo', 1));
    }

    public function testNoUnmatchedResults(): void
    {
        $regex = new Regex('/foo(bar)?/');
        $this->assertSame(['foo'], $regex->match(('foo')));
    }

    public function testNoMatchOffsetReturnsNull(): void
    {
        $regex = new Regex('/foo/');
        $this->assertNull($regex->matchOffset('bar'));
    }

    public function testMatchOffsetReturnsResults(): void
    {
        $regex = new Regex("/f(o)(?'name'o)/");
        $result = $regex->matchOffset('foo');

        $this->assertSame([
            0 => ['foo', 0],
            1 => ['o', 1],
            'name' => ['o', 2],
            2 => ['o', 2],
        ], $result);
    }

    public function testOffsetNoMatchOffset(): void
    {
        $regex = new Regex('/foo/');
        $this->assertNull($regex->matchOffset('foo', 1));
    }

    public function testNoUnmatchedOffsetResults(): void
    {
        $regex = new Regex('/foo(bar)?/');
        $this->assertSame([['foo', 0]], $regex->matchOffset(('foo')));
    }

    public function testNoMatchAllReturnsEmptyArray(): void
    {
        $regex = new Regex('/foo/');
        $this->assertSame([], $regex->matchAll('bar'));
    }

    public function testMatchAllReturnsResults(): void
    {
        $regex = new Regex("/f(o)(?'name'o)/");
        $result = $regex->matchAll('foofoo');

        $this->assertSame([
            [
                0 => 'foo',
                1 => 'o',
                'name' => 'o',
                2 => 'o',
            ],
            [
                0 => 'foo',
                1 => 'o',
                'name' => 'o',
                2 => 'o',
            ]
        ], $result);
    }

    public function testOffsetNoMatchAll(): void
    {
        $regex = new Regex('/foo/');
        $this->assertSame([], $regex->matchAll('foo', 1));
    }

    public function testNoUnmatchedAllResults(): void
    {
        $regex = new Regex('/foo(bar)?/');
        $this->assertSame([['foo']], $regex->matchAll('foo'));
    }

    public function testNoMatchAllOffsetsReturnsEmptyArray(): void
    {
        $regex = new Regex('/foo/');
        $this->assertSame([], $regex->matchAllOffsets('bar'));
    }

    public function testMatchAllOffsetsReturnsResults(): void
    {
        $regex = new Regex("/f(o)(?'name'o)/");
        $result = $regex->matchAllOffsets('foofoo');

        $this->assertSame([
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
        ], $result);
    }

    public function testOffsetNoMatchAllOffsets(): void
    {
        $regex = new Regex('/foo/');
        $this->assertSame([], $regex->matchAllOffsets('foo', 1));
    }

    public function testNoUnmatchedAllOffsetsResults(): void
    {
        $regex = new Regex('/foo(bar)?/');
        $this->assertSame([[['foo', 0]]], $regex->matchAllOffsets('foo'));
    }

    public function testMatches(): void
    {
        $regex = new Regex('/foo/');

        $this->assertTrue($regex->matches('foo'));
        $this->assertFalse($regex->matches('bar'));
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
}
