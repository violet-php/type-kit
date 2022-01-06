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
    public function testInvalidPattern()
    {
        $this->expectException(PcreException::class);
        $this->expectExceptionMessage('No ending delimiter');

        new Regex('/foo');
    }

    public function testNoFunctionNameInEerror()
    {
        try {
            new Regex('/foo');
            $this->fail('Failed to throw the expected exception');
        } catch (PcreException $exception) {
            $this->assertStringNotContainsStringIgnoringCase('preg_', $exception->getMessage());
        }
    }

    public function testErrorWhileMatching()
    {
        $regex = new Regex('/(?:\D+|<\d+>)*[!?]/');

        $this->expectException(PcreException::class);
        $this->expectExceptionMessage('Backtrack limit exhausted');

        $regex->match('foobar foobar foobar');
    }
}
