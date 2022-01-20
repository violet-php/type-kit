<?php

declare(strict_types=1);

namespace Violet\TypeKit;

use Violet\TypeKit\Type\CastArrayTypesTrait;
use Violet\TypeKit\Type\CastListTypesTrait;
use Violet\TypeKit\Type\CastPlainTypesTrait;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class TypeCast
{
    use CastPlainTypesTrait;
    use CastArrayTypesTrait;
    use CastListTypesTrait;
}
