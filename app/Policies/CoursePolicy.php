<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    /**
     * Может ли пользователь обновлять (редактировать) курс.
     */
    public function update(User $user, Course $course): bool
    {
        // Разрешаем, если пользователь — админ или автор курса
        return $user->isAdmin() || $course->author_id === $user->id;
    }

    /**
     * Может ли пользователь удалить курс.
     */
    public function delete(User $user, Course $course): bool
    {
        // Разрешаем, если пользователь — админ или автор курса
        return $user->isAdmin() || $course->author_id === $user->id;
    }
}
