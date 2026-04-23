<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Lesson extends Model
{
    protected $fillable = ['module_id', 'title', 'content', 'assignment_file', 'available_from', 'deadline', 'late_penalty_percent', 'required_lesson_id', 'required_min_score',];
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
    public function requiredLesson()
    {
        return $this->belongsTo(Lesson::class, 'required_lesson_id');
    }
}
