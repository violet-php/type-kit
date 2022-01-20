<?php

declare(strict_types=1);

namespace Violet\TypeKit;

use Violet\TypeKit\Type\AssertArrayTypesTrait;
use Violet\TypeKit\Type\AssertListTypesTrait;
use Violet\TypeKit\Type\AssertPlainTypesTrait;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class TypeAssert
{
    use AssertPlainTypesTrait;
    use AssertArrayTypesTrait;
    use AssertListTypesTrait;
}
