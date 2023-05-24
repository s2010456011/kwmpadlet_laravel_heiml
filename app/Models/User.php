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
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'firstname',
        'lastname',
        'image',
        'email',
        'password'
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

    public function padlet() : hasMany{
        return $this->hasMany(Padlet::class);
    }

    //Fremdschlüssel werden in Pivot Tabelle padlet_user gespeichert
    //rolle von user in bestimmten Padlet
    public function padlets() : BelongsToMany{
        return $this->belongsToMany(Padlet::class, 'padlet_user');
    }

    //gibt key von aktuellem JWT zurück
    public function getJWTIdentifier(){
        return $this->getKey();
    }

    //Custom Claim ist Custom Payload --> Daten die selbst im JWT gespeichert werden können
    //dort soll benutzer gespeichert werden --> NICHT VERSCHLÜSSELT!
    public function getJWTCustomClaims(){
        return ['user' => ['id' => $this->id]];
    }

}
