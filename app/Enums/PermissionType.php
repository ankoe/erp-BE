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
    const PurchaseRequest   = 'purchase request';

    // ---------------------------------------
    const OfficePurchaseRequest     = 'office purchase request';

    // ---------------------------------------
    const ProcurementPurchaseOrder  = 'procurement purchase request';
    const ProcurementRFQ            = 'procurement rfq';
    const ProcurementPO             = 'procurement po';
    const ProcurementMessage        = 'procurement message';

    // ---------------------------------------
    const VendorOffer               = 'vendor offer';

    // ---------------------------------------
    const VendorMessage             = 'vendor message';
}