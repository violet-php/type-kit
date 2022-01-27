<?php

declare(strict_types=1);

namespace Violet\TypeKit\PhpStan;

use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Analyser\SpecifiedTypes;
use PHPStan\Analyser\TypeSpecifier;
use PHPStan\Analyser\TypeSpecifierAwareExtension;
use PHPStan\Analyser\TypeSpecifierContext;
use PHPStan\Reflection\MethodReflection;
use PHPStan\ShouldNotHappenException;
use PHPStan\Type;
use PHPStan\Type\StaticMethodTypeSpecifyingExtension;
use Violet\TypeKit;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class TypeExtension implements StaticMethodTypeSpecifyingExtension, TypeSpecifierAwareExtension
{
    private ?TypeSpecifier $typeSpecifier = null;

    public function setTypeSpecifier(TypeSpecifier $typeSpecifier): void
    {
        $this->typeSpecifier = $typeSpecifier;
    }

    public function getClass(): string
    {
        return TypeKit\Type\TypeIs::class;
    }

    public function isStaticMethodSupported(
        MethodReflection $staticMethodReflection,
        StaticCall $node,
        TypeSpecifierContext $context
    ): bool {
        return self::isTypeMethod(strtolower($staticMethodReflection->getName())) && !$context->null();
    }

    public function specifyTypes(
        MethodReflection $staticMethodReflection,
        StaticCall $node,
        Scope $scope,
        TypeSpecifierContext $context
    ): SpecifiedTypes {
        if ($this->typeSpecifier === null || $context->null()) {
            throw new ShouldNotHappenException();
        }

        if (!isset($node->getArgs()[0])) {
            return new SpecifiedTypes();
        }

        $classType = isset($node->getArgs()[1]) ? $scope->getType($node->getArgs()[1]->value) : null;
        $class = $classType instanceof Type\Constant\ConstantStringType ? $classType->getValue() : null;

        return $this->typeSpecifier->create(
            $node->getArgs()[0]->value,
            self::getMethodType(strtolower($staticMethodReflection->getName()), $class),
            $context,
            \false,
            $scope
        );
    }

    public static function getMethodType(string $type, ?string $class = null): Type\Type
    {
        if ($type !== 'array' && str_ends_with($type, 'array')) {
            return new Type\ArrayType(new Type\MixedType(), self::getMethodType(substr($type, 0, -5), $class));
        }

        if ($type !== 'list' && str_ends_with($type, 'list')) {
            return new Type\ArrayType(new Type\IntegerType(), self::getMethodType(substr($type, 0, -4), $class));
        }

        return match ($type) {
            'null' => new Type\NullType(),
            'bool' => new Type\BooleanType(),
            'int' => new Type\IntegerType(),
            'float' => new Type\FloatType(),
            'string' => new Type\StringType(),
            'array' => new Type\ArrayType(new Type\MixedType(), new Type\MixedType()),
            'list' => new Type\ArrayType(new Type\IntegerType(), new Type\MixedType()),
            'object' => new Type\ObjectWithoutClassType(),
            'instance' => $class === null ? new Type\ObjectWithoutClassType() : new Type\ObjectType($class),
            'iterable' => new Type\IterableType(new Type\MixedType(), new Type\MixedType()),
            'resource' => new Type\ResourceType(),
            'callable' => new Type\CallableType(),
            default => throw new ShouldNotHappenException("Unexpected type name '$type'"),
        };
    }

    public static function isTypeMethod(string $name): bool
    {
        if ($name !== 'array' && str_ends_with($name, 'array')) {
            $name = substr($name, 0, -5);
        } elseif ($name !== 'list' && str_ends_with($name, 'list')) {
            $name = substr($name, 0, -4);
        }

        return \in_array($name, [
            'null',
            'bool',
            'int',
            'float',
            'string',
            'array',
            'list',
            'object',
            'instance',
            'iterable',
            'resource',
            'callable'
        ], true);
    }
}
