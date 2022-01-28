<?php

declare(strict_types=1);

namespace Violet\TypeKit\PhpStan;

use PhpParser\Node;
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
        Node\Expr\StaticCall $node,
        TypeSpecifierContext $context
    ): bool {
        return self::isTypeMethod(strtolower($staticMethodReflection->getName())) && !$context->null();
    }

    public function specifyTypes(
        MethodReflection $staticMethodReflection,
        Node\Expr\StaticCall $node,
        Scope $scope,
        TypeSpecifierContext $context
    ): SpecifiedTypes {
        if ($this->typeSpecifier === null || $context->null()) {
            throw new ShouldNotHappenException();
        }

        if (!isset($node->getArgs()[0])) {
            return new SpecifiedTypes();
        }

        $type = self::getMethodType(
            strtolower($staticMethodReflection->getName()),
            $scope,
            isset($node->getArgs()[1]) ? $node->getArgs()[1]->value : null
        );

        return $this->typeSpecifier->create($node->getArgs()[0]->value, $type, $context, \false, $scope);
    }

    public static function getMethodType(string $type, Scope $scope, ?Node\Expr $class = null): Type\Type
    {
        if ($type !== 'array' && str_ends_with($type, 'array')) {
            return new Type\ArrayType(
                new Type\MixedType(),
                self::getMethodType(substr($type, 0, -5), $scope, $class)
            );
        }

        if ($type !== 'list' && str_ends_with($type, 'list')) {
            return new Type\ArrayType(
                new Type\IntegerType(),
                self::getMethodType(substr($type, 0, -4), $scope, $class)
            );
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
            'instance' => $class === null ? new Type\ObjectWithoutClassType() : self::getInstanceType($class, $scope),
            'iterable' => new Type\IterableType(new Type\MixedType(), new Type\MixedType()),
            'resource' => new Type\ResourceType(),
            'callable' => new Type\CallableType(),
            default => throw new ShouldNotHappenException("Unexpected type name '$type'"),
        };
    }

    private static function getInstanceType(Node\Expr $classNameExpression, Scope $scope): Type\Type
    {
        $classNameType = $scope->getType($classNameExpression);

        if (
            $classNameExpression instanceof Node\Expr\ClassConstFetch &&
            $classNameExpression->class instanceof Node\Name &&
            $classNameExpression->name instanceof Node\Identifier &&
            \strtolower($classNameExpression->name->name) === 'class'
        ) {
            return $scope->resolveTypeByName($classNameExpression->class);
        }

        if ($classNameType instanceof Type\Constant\ConstantStringType) {
            return new Type\ObjectType($classNameType->getValue());
        }

        if ($classNameType instanceof Type\Generic\GenericClassStringType) {
            return $classNameType->getGenericType();
        }

        return new Type\ObjectWithoutClassType();
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
