<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserImportExportController extends Controller
{
    // 📤 ЭКСПОРТ
    public function export()
    {
        $filename = "users.csv";

        $users = User::with('role')->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');

            // заголовки
            fputcsv($file, ['name', 'email', 'role']);

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->name,
                    $user->email,
                    $user->role->name ?? ''
                ]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    // 📥 ИМПОРТ
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        $file = fopen($request->file('file'), 'r');

        // пропускаем заголовок
        fgetcsv($file);

        while (($row = fgetcsv($file)) !== false) {

            [$name, $email, $roleName] = $row;

            // ищем роль
            $role = Role::where('name', $roleName)->first();

            if (!$role) {
                continue; // пропускаем если роль не найдена
            }

            // проверка на существующего пользователя
            if (User::where('email', $email)->exists()) {
                continue;
            }

            User::create([
                'name' => $name,
                'email' => $email,
                'password' => 'password', // Laravel сам захеширует
                'role_id' => $role->id
            ]);
        }

        fclose($file);

        return back()->with('success', 'Импорт завершён');
    }
}