<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionLog extends Model
{
    use HasFactory;

    protected $table = 'session_log';

    protected $fillable = ['eh_id', 'student_id', 'attend', 'valid_reason'];
}
