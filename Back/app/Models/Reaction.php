<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'actions_id',
        'services_id',
        'activated',
    ];

    public function action()
    {
        return $this->belongsTo(Action::class, 'actions_id', 'id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'services_id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_reactions', 'reactions_id', 'users_id');
    }

}
