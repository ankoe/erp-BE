<?php

namespace App\Models;

use App\Traits\Filter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Vendor extends Model
{
    use Filterable, HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id', 'name', 'email', 'email_cc', 'mobile', 'slug'
    ];

    protected $casts = [
        'email_cc' => 'json',
        'mobile' => 'json',
    ];

    /***********************************************
     *  1. Relation
    ***********************************************/

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function materialCategories()
    {
        return $this->belongsToMany(MaterialCategory::class, 'material_category_vendor');
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

    public function generateUniqueSlug()
    {
        $slug = Str::random(10); // Generate a random 8-character slug

        // Check if the slug already exists for the current model instance
        while (static::where('slug', $slug)->where('id', '!=', $this->id)->exists()) {
            $slug = Str::random(8); // Generate a new slug
        }

        $this->slug = $slug; // Assign the unique slug to the model instance

        return $slug;
    }
}
