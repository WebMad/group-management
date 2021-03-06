<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationHistory extends Model
{
    use HasFactory;

    protected $table = 'education_history';

    protected $fillable = ["subject_id", "start_date", "end_date", "teacher_id", "filled", "account_hours"];

    public function subject()
    {
        return $this->hasOne(Subject::class, 'id', 'subject_id');
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'id', 'teacher_id');
    }

    public function sessionLog()
    {
        return $this->hasMany(SessionLog::class, 'eh_id', 'id');
    }
}
