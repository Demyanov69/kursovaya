<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Glossary extends Model
{
    protected $fillable = [
        'course_id',
        'term',
        'short_definition',
        'full_definition',
        'created_by',
        'status'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function comments()
    {
        return $this->hasMany(GlossaryComment::class);
    }

    public function ratings()
    {
        return $this->hasMany(GlossaryRating::class);
    }

    public function averageRating()
    {
        return round($this->ratings()->avg('rating') ?? 0, 1);
    }
}