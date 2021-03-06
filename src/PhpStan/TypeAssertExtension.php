<?php

declare(strict_types=1);

namespace Violet\TypeKit\PhpStan;

use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Analyser\SpecifiedTypes;
use PHPStan\Analyser\TypeSpecifierContext;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\StaticMethodTypeSpecifyingExtension;
use PHPStan\Type\TypeCombinator;
use Violet\TypeKit\Type\TypeAssert;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class TypeAssertExtension extends AbstractTypeExtension implements StaticMethodTypeSpecifyingExtension
{
    public function getClass(): string
    {
        return TypeAssert::class;
    }

    public function isStaticMethodSupported(
        MethodReflection $staticMethodReflection,
        StaticCall $node,
        TypeSpecifierContext $context
    ): bool {
        return $this->isTypeMethod(strtolower($staticMethodReflection->getName()))
            && $context->null()
            && isset($node->getArgs()[0]);
    }

    public function specifyTypes(
        MethodReflection $staticMethodReflection,
        StaticCall $node,
        Scope $scope,
        TypeSpecifierContext $context
    ): SpecifiedTypes {
        $assertedType = $this->getMethodType(
            strtolower($staticMethodReflection->getName()),
            $scope,
            isset($node->getArgs()[1]) ? $node->getArgs()[1]->value : null
        );

        $expression = $node->getArgs()[0]->value;
        $typeBefore = $scope->getType($expression);
        $type = TypeCombinator::intersect($typeBefore, $assertedType);

        return $this->typeSpecifier->create($expression, $type, TypeSpecifierContext::createTruthy());
    }
}
