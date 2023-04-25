<?php

namespace App\Models;

use App\Traits\Filter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use Filterable, HasFactory;

    protected $fillable = [
        'name', 'address', 'mobile', 'email',
    ];
}
