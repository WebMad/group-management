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
        'is_expelled',
        'date_expelled',
    ];

    public function getFioAttribute()
    {
        return "{$this->surname} {$this->name} {$this->patronymic}";
    }

    public function session_log()
    {
        return $this->hasMany(SessionLog::class, 'student_id', 'id')
            ->with(['education_history']);
    }
}
