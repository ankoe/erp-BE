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
        'file',
        'is_approve',
        'remarks',
        'incoterms',
        'winning_vendor_id',
        'winning_vendor_price',
        'winning_vendor_stock',
        'winning_vendor_incoterms',
    ];

    /***********************************************
     *  1. Relation
    ***********************************************/

    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function winningVendor()
    {
        return $this->belongsTo(Vendor::class, 'winning_vendor_id');
    }

    public function requestQuotation()
    {
        return $this->hasMany(RequestQuotation::class);
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
