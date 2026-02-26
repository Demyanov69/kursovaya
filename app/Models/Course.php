<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable =
        [
            'title',
            'description',
            'category_id',
            'author_id',
            'faculty',
            'direction',
            'course_year',
            'lessons_count'
        ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
    public function students()
    {
        return $this->belongsToMany(User::class);
    }
    public function modules()
    {
        return $this->hasMany(Module::class);
    }
    public function lessons()
    {
        return $this->hasManyThrough(
            \App\Models\Lesson::class,
            \App\Models\Module::class,
            'course_id', 
            'module_id', 
            'id',    
            'id'   
        );
    }



}