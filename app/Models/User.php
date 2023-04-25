<?php

namespace App\Models;

use App\Traits\Filter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Filterable, HasApiTokens, HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id', 'name', 'email', 'mobile', 'email_proof_token',
        'email_proof_token_expires_at', 'password',
        'password_proof_token', 'password_proof_token_expires_at',
        'image_profile', 'last_login_at',
        'confirmed_at', 'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password', 'email_proof_token', 'email_proof_token_expires_at', 'password_proof_token', 'password_proof_token_expires_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'confirmed_at'  => 'datetime',
        'is_active'     => 'boolean',
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

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
