<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_user_id', 'receiver_user_id', 'sender_type', 'receiver_type',
        'purchase_request_id', 'request_quotation_id'
    ];

    /***********************************************
     *  1. Relation
    ***********************************************/

    public function sender()
    {
        if ($this->sender_type == 'vendor')
            return $this->belongsTo(Vendor::class, 'sender_user_id');
        else
            return $this->belongsTo(User::class, 'sender_user_id');
    }

    public function receiver()
    {
        if ($this->receiver_type == 'vendor')
            return $this->belongsTo(Vendor::class, 'receiver_user_id');
        else
            return $this->belongsTo(User::class, 'receiver_user_id');
    }

    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class, 'purchase_request_id');
    }

    public function requestQuotation()
    {
        return $this->belongsTo(RequestQuotation::class, 'request_quotation_id');
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

    public function lastChat()
    {
        return $this->hasMany(Chat::class)->latest()->limit(1);
    }
}
