<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'users_id',
        'name',
        'description',
        'activated',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    public function actions()
    {
        return $this->hasMany(Action::class, 'areas_id', 'id');
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class, 'actions_id', 'id');
    }

    public function areaHistorique()
    {
        return $this->hasMany(AreaHistorique::class, 'areas_id', 'id');
    }
}
