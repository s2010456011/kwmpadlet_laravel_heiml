<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    protected $fillable = ['number', 'entry_id', 'user_id'];

    use HasFactory;

    public function entry():BelongsTo{
        return $this->belongsTo(Entrie::class);
    }

    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }
}
