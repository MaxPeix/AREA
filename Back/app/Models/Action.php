<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    use HasFactory;

    protected $fillable = [
        'areas_id',
        'services_id',
        'first_parameter',
        'second_parameter',
        'activated',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class, 'areas_id', 'id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'services_id', 'id');
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class, 'actions_id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_actions', 'actions_id', 'users_id');
    }

}
