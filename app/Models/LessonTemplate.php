<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonTemplate extends Model
{
    protected $fillable = [
        'teacher_id',
        'name',
        'blocks_json',
    ];
}