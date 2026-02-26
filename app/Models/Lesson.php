<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Lesson extends Model
{
    protected $fillable = ['module_id', 'title', 'content', 'assignment_file', 'available_from', 'deadline', 'late_penalty_percent'];
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
