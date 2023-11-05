<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaHistorique extends Model
{
    use HasFactory;

    protected $table = 'area_historique';

    protected $fillable = [
        'users_id',
        'name',
        'description',
        'informations_random',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
}
