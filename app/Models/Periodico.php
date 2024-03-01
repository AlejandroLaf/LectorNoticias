<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periodico extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'name',
        // Agrega otros atributos aquí si los tienes
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'periodicos_user');
    }
}
