<?php

namespace App\Models;

use App\Traits\Filter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as Model;

class Role extends Model
{
    use Filterable, HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'group',
        'display_name',
        'is_default',
        'guard_name',
    ];

    /***********************************************
     *  1. Relation
    ***********************************************/

    public function company()
    {
        return $this->belongsTo(Company::class);
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
