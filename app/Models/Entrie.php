<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entrie extends Model
{
    protected $fillable = ['title', 'text', 'padlet_id', 'user_id'];

    use HasFactory;

    //entry belongs to exactly one padlet
    public function padlet():BelongsTo{
        return $this->belongsTo(Padlet::class);
    }

    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function comments():hasMany{
        return $this->hasMany(Comment::class);
    }

    public function ratings():hasMany{
        return $this->hasMany(Rating::class);
    }
}
