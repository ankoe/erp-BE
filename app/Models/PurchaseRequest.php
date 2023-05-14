<?php

namespace App\Models;

use App\Traits\Filter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequest extends Model
{
    use Filterable, HasFactory;

    protected $fillable = [
        'company_id',
        'user_id',
        'purchase_request_status_id',
    ];

    /***********************************************
     *  1. Relation
    ***********************************************/

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchaseRequestStatus()
    {
        return $this->belongsTo(PurchaseRequestStatus::class);
    }

    public function purchaseRequestItem()
    {
        return $this->belongsTo(PurchaseRequestItem::class);
    }

    /***********************************************
     *  2. Getter & Setter
    ***********************************************/

    /***********************************************
     *  3. Scope
    ***********************************************/

    /***********************************************
     *  4. Function
    ***********************************************/
}
