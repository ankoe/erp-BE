<?php

namespace App\Models;

use App\Traits\Filter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequestItem extends Model
{
    use Filterable, HasFactory;

    protected $fillable = [
        'purchase_request_id',
        'material_id',
        'price',
        'description',
        'quantity',
        'total',
        'vendor_id',
        'branch_id',
        'expected_at',
        'file'
    ];

    /***********************************************
     *  1. Relation
    ***********************************************/

    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
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
