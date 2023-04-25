<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Office()
 * @method static static Procurement()
 */
final class RoleGroup extends Enum
{
    const Office        = 'office';
    const Procurement   = 'procurement';
}
