<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestQuotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'purchase_request_item_id',
        'vendor_id',
        'vendor_price',
        'vendor_stock',
        'vendor_incoterms',
        'vendor_is_agree',
        'is_selected'
    ];

    protected $casts = [
        'is_selected' => 'boolean',
        'vendor_is_agree' => 'boolean',
    ];

    /***********************************************
     *  1. Relation
    ***********************************************/

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
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
