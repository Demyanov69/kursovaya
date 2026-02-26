<?php

namespace App\Models;

// Импорты необходимых классов и трейтов
use Illuminate\Contracts\Auth\MustVerifyEmail; // Используется, если включена верификация email
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Базовый класс для аутентификации
use Illuminate\Notifications\Notifiable; // Для отправки уведомлений
use Laravel\Sanctum\HasApiTokens; // Для API токенов (если используется Sanctum)

class User extends Authenticatable implements MustVerifyEmail // implements MustVerifyEmail - опционально
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'faculty',
        'direction',
        'course_year'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function courses()
    {
        return $this->belongsToMany(Course::class);
    } // записаные
    public function authoredCourses()
    {
        return $this->hasMany(Course::class, 'author_id');
    }

    public function isStudent()
    {
        return $this->role && $this->role->name === 'student';
    }
    public function isTeacher()
    {
        return $this->role && $this->role->name === 'teacher';
    }
    public function isAdmin()
    {
        return $this->role && $this->role->name === 'admin';
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime', // Автоматически преобразует в объект Carbon
        'password' => 'hashed',            // Автоматически хеширует пароль при сохранении (Laravel 10+)
    ];
}