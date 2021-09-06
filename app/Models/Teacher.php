<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'surname',
        'name',
        'patronymic',
        'email',
        'phone',
    ];

    public function getFioAttribute()
    {
        return "{$this->surname} {$this->name} {$this->patronymic}";
    }
}
