<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Grade extends Model
{
    protected $fillable = ['submission_id', 'grader_id', 'score', 'comment'];
    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }
    public function grader()
    {
        return $this->belongsTo(User::class, 'grader_id');
    }
}
