<?php

namespace App\Models;

use App\Traits\Filter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use Filterable, HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'type',
        'message',
        'route',
        'route_param',
        'is_read',
    ];

    protected $casts = [
        'route_param' => 'array',
    ];

    /***********************************************
     *  1. Relation
    ***********************************************/

    public function user()
    {
        return $this->belongsTo(User::class);
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
