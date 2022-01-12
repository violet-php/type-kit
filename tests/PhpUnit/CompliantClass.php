<?php

declare(strict_types=1);

namespace Violet\TypeKit\PhpUnit;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CompliantClass extends AbstractCompliantClass implements CompliantInterface
{
    use CompliantTrait;
}
