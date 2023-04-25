<?php

namespace App\Models;

use App\Traits\Filter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use Filterable, HasFactory;

    protected $fillable = [
        'company_id',
        'material_category_id',
        'name',
        'number',
        'description',
        'uom',
        'price',
    ];

    /***********************************************
     *  1. Relation
    ***********************************************/

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function materialCategory()
    {
        return $this->belongsTo(MaterialCategory::class);
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
