<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Admin()
 * @method static static User()
 * @method static static Supervisor()
 */
final class RoleUserDefault extends Enum
{
    const Admin         = 'admin';
    const User          = 'user';
    const Supervisor    = 'supervisor';
}
