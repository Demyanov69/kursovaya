<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Submission extends Model
{
    protected $fillable = ['lesson_id', 'student_id', 'text_answer', 'file_answer', 'status', 'submitted_at'];
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    public function grade()
    {
        return $this->hasOne(Grade::class, 'submission_id');
    }
}
