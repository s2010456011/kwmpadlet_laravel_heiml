<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PadletUser extends Model
{
    protected $fillable = ['padlet_id', 'user_id', 'role_id'];

    //zuweisen des Models zur Tabelle padlet_user wegen abweichender Konvention
    protected $table = 'padlet_user';

    use HasFactory;

    public function padlet() : BelongsTo{
        return $this->belongsTo(Padlet::class);
    }

    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function role():BelongsTo{
        return $this->belongsTo(Role::class);
    }

}
