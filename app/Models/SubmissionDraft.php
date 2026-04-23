<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionDraft extends Model
{
    protected $fillable = [
        'lesson_id',
        'student_id',
        'text_answer',
    ];
}