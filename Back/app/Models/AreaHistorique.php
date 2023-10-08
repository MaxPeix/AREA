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
        'areas_id',
        'name',
        'description',
        'informations_random',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'areas_id', 'id');
    }
}
