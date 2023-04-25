<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Approve()
 * @method static static Reject()
 * @method static static Supervisor()
 */
final class PurchaseRequestStatus extends Enum
{
    const Approve   = 'approve';
    const Reject    = 'reject';
}
