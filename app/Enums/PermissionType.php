<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PermissionType extends Enum
{   
    const Location          = 'location';
    const ConfigApproval    = 'config approval';
    const Material          = 'material';
    const MaterialCategory  = 'material category';
    const Role              = 'role';
    const User              = 'user';
    const Vendor            = 'vendor';

    // ---------------------------------------
    const PurchaseRequestCreate  = 'purchase request create';
    const PurchaseRequestApprove = 'purchase request approve';
}