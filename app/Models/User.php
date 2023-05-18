<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'firstname',
        'lastname',
        'image',
        'email'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function ratings():hasMany{
        return $this->hasMany(Rating::class);
    }

    public function comments():hasMany{
        return $this->hasMany(Comment::class);
    }

    public function entries():hasMany{
        return $this->hasMany(Entrie::class);
    }

    public function padlets() : BelongsToMany{
        return $this->belongsToMany(Padlet::class);
    }

    public function roles() : BelongsToMany{
        return $this->belongsToMany(Role::class);
    }

    /*Zusätzliche padlets() : has Many von Padlet?*/
}
