<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlossaryComment extends Model
{
    protected $fillable = [
        'glossary_id',
        'user_id',
        'comment'
    ];

    public function glossary()
    {
        return $this->belongsTo(Glossary::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}