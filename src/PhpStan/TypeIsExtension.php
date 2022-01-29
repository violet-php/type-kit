<?php

declare(strict_types=1);

namespace Violet\TypeKit\PhpStan;

use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Analyser\SpecifiedTypes;
use PHPStan\Analyser\TypeSpecifierContext;
use PHPStan\Reflection\MethodReflection;
use PHPStan\ShouldNotHappenException;
use PHPStan\Type\StaticMethodTypeSpecifyingExtension;
use Violet\TypeKit\Type\TypeIs;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class TypeIsExtension extends AbstractTypeExtension implements StaticMethodTypeSpecifyingExtension
{
    public function getClass(): string
    {
        return TypeIs::class;
    }

    public function isStaticMethodSupported(
        MethodReflection $staticMethodReflection,
        StaticCall $node,
        TypeSpecifierContext $context
    ): bool {
        return $this->isTypeMethod(strtolower($staticMethodReflection->getName())) && !$context->null();
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

        $type = $this->getMethodType(
            strtolower($staticMethodReflection->getName()),
            $scope,
            isset($node->getArgs()[1]) ? $node->getArgs()[1]->value : null
        );

        return $this->typeSpecifier->create($node->getArgs()[0]->value, $type, $context, \false, $scope);
    }
}
