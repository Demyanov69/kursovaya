<?php

namespace Database\Seeders; // <-- ДОБАВЬТЕ ЭТУ СТРОКУ

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Course;
use App\Models\Category; // <-- Также добавьте импорт Category
use Illuminate\Support\Facades\Hash;

class InitialDataSeeder extends Seeder
{
    public function run()
    {
        // Создание ролей
        $rStudent = Role::firstOrCreate(['name' => 'student']);
        $rTeacher = Role::firstOrCreate(['name' => 'teacher']);
        $rAdmin = Role::firstOrCreate(['name' => 'admin']);

        // Создание пользователей (используем firstOrCreate, чтобы не дублировать при повторном запуске)
        $admin = User::firstOrCreate(
            ['email' => 'admin@test.local'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role_id' => $rAdmin->id
            ]
        );
        $teacher = User::firstOrCreate(
            ['email' => 'teacher@test.local'],
            [
                'name' => 'Prof. Ivanov',
                'password' => Hash::make('password'),
                'role_id' => $rTeacher->id
            ]
        );
        $student = User::firstOrCreate(
            ['email' => 'student@test.local'],
            [
                'name' => 'Student Petrov',
                'password' => Hash::make('password'),
                'role_id' => $rStudent->id,
                'faculty' => 'Информатика',
                'direction' => 'Программирование',
                'course_year' => 2
            ]
        );
        $student = User::firstOrCreate(
            ['email' => 'student1@test.local'],
            [
                'name' => 'Student Orlrov',
                'password' => Hash::make('password'),
                'role_id' => $rStudent->id,
                'faculty' => 'Информатика',
                'direction' => 'Программирование',
                'course_year' => 2
            ]
        );

        // Создание категории (также firstOrCreate)
        $cat = Category::firstOrCreate(['name' => 'Программирование']);

        // Создание курса (также firstOrCreate)
        $course = Course::firstOrCreate(
            ['title' => 'PHP и Laravel'], // Уникальный идентификатор
            [
                'description' => 'Введение в Laravel 12',
                'category_id' => $cat->id,
                'author_id' => $teacher->id,
                'faculty' => 'Информатика',
                'direction' => 'Программирование',
                'course_year' => 2
            ]
        );
    }
}