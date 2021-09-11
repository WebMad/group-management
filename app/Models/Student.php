<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'surname',
        'name',
        'patronymic',
        'email',
        'phone',
        'code',
        'vk_id',
    ];
}
