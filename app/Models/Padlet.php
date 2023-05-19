<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Padlet extends Model
{

    protected $fillable = ['title', 'description', 'is_public', 'user_id'];

    use HasFactory;

    public function entries() : HasMany{
        return $this->hasMany(Entrie::class);
    }

    public function user() : BelongsTo{
        return $this->belongsTo(User::class);
    }

    //Fremdschlüssel werden in Pivot Tabelle padlet_user gespeichert
    //rolle von user in bestimmten Padlet
    public function users() : BelongsToMany{
        return $this->belongsToMany(User::class, 'padlet_user');
    }
}
