<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_name',
        'service_description',
        'apikey',
        'url',
        'working',
    ];

    public function actions()
    {
        return $this->hasMany(Action::class);
    }

}
