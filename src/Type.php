<?php

declare(strict_types=1);

namespace Violet\TypeKit;

use Violet\TypeKit\Type\ArrayTypesTrait;
use Violet\TypeKit\Type\ConditionalArrayTypesTrait;
use Violet\TypeKit\Type\ConditionalListTypesTrait;
use Violet\TypeKit\Type\ListTypesTrait;
use Violet\TypeKit\Type\PlainTypesTrait;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class Type
{
    use PlainTypesTrait;
    use ArrayTypesTrait;
    use ListTypesTrait;
    use ConditionalArrayTypesTrait;
    use ConditionalListTypesTrait;
}
