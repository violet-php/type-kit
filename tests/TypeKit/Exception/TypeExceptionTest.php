<?php

declare(strict_types=1);

namespace Violet\TypeKit\Exception;

use PHPUnit\Framework\TestCase;
use Violet\TypeKit\Type;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class TypeExceptionTest extends TestCase
{
    public function testFileAndLine(): void
    {
        $line = __LINE__ + 1;
        $exception = TypeException::createFromValue(1, 'string');

        $this->assertSame(__FILE__, $exception->getFile());
        $this->assertSame($line, $exception->getLine());
    }

    public function testFileAndLineViaInternalCall(): void
    {
        $line = __LINE__ + 1;
        $result = array_map(TypeException::createFromValue(...), [1], ['string']);

        $this->assertInstanceOf(TypeException::class, $result[0]);
        $this->assertSame(__FILE__, $result[0]->getFile());
        $this->assertSame($line, $result[0]->getLine());
    }

    public function testFileAndLineViaTypeClass(): void
    {
        $line = 0;

        try {
            $line = __LINE__ + 1;
            Type::string(1);
            $this->fail('Failed to throw expected exception');
        } catch (TypeException $exception) {
            $this->assertSame(__FILE__, $exception->getFile());
            $this->assertSame($line, $exception->getLine());
        }
    }

    /** @dataProvider getTypeNameTestCases */
    public function testTypeName(mixed $value, string $type): void
    {
        $exception = TypeException::createFromValue($value, $type);
        $pattern = preg_quote("Got unexpected value type '$type', was expecting '$type'", '/');

        $this->assertMatchesRegularExpression("/$pattern/", $exception->getMessage());
    }

    /**
     * @return array<array{mixed, string}>
     */
    public function getTypeNameTestCases(): array
    {
        return [
            [null, 'null'],
            [true, 'bool'],
            [1, 'int'],
            [1.0, 'float'],
            ['foobar', 'string'],
            [tmpfile(), 'resource (stream)'],
            [new \DateTimeImmutable(), \DateTimeImmutable::class],
            [[null], 'list<null>'],
            [[true], 'list<bool>'],
            [[1], 'list<int>'],
            [[1.0], 'list<float>'],
            [['foobar'], 'list<string>'],
            [[tmpfile()], 'list<resource (stream)>'],
            [[new \DateTimeImmutable()], 'list<' . \DateTimeImmutable::class . '>'],
            [[[]], 'list<array>'],
            [[1 => 1], 'array<int>'],
            [[1, 'list'], 'list<int|string>'],
            [[new \DateTimeImmutable(), new \DateTimeImmutable()], 'list<' . \DateTimeImmutable::class . '>']
        ];
    }
}
