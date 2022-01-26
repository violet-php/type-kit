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
use PHPStan\Type;
use PHPStan\Type\StaticMethodTypeSpecifyingExtension;
use PHPStan\Type\TypeCombinator;
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
        return TypeKit\TypeAs::class;
    }

    public function isStaticMethodSupported(
        MethodReflection $staticMethodReflection,
        StaticCall $node,
        TypeSpecifierContext $context
    ): bool {
        return str_starts_with($staticMethodReflection->getName(), 'is')
            && !$context->null()
            && isset($node->getArgs()[0]);
    }

    public function specifyTypes(
        MethodReflection $staticMethodReflection,
        StaticCall $node,
        Scope $scope,
        TypeSpecifierContext $context
    ): SpecifiedTypes {
        $expression = $node->getArgs()[0]->value;
        $typeBefore = $scope->getType($expression);
        $typeName = lcfirst(substr($staticMethodReflection->getName(), 2));

        $classType = isset($node->getArgs()[1]) ? $scope->getType($node->getArgs()[1]->value) : null;
        $class = $classType instanceof Type\Constant\ConstantStringType ? $classType->getValue() : null;

        $type = TypeCombinator::intersect($typeBefore, self::getMethodType($typeName, $class));

        return $this->typeSpecifier->create($expression, $type, TypeSpecifierContext::createTruthy());
    }

    public static function getMethodType(string $type, ?string $class = null): Type\Type
    {
        if (str_ends_with($type, 'Array')) {
            return new Type\ArrayType(new Type\MixedType(), self::getMethodType(substr($type, 0, -5), $class));
        }

        if (str_ends_with($type, 'List')) {
            return new Type\ArrayType(new Type\IntegerType(), self::getMethodType(substr($type, 0, -4), $class));
        }

        if ($type === 'instance' && $class === null) {
            return new Type\ObjectWithoutClassType();
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
            'instance' => new Type\ObjectType($class),
            'iterable' => new Type\IterableType(new Type\MixedType(), new Type\MixedType()),
            'resource' => new Type\ResourceType(),
            'callable' => new Type\CallableType(),
        };
    }
}
