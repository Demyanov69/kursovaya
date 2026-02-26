<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Обработка входящего запроса.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed ...$roles  Список допустимых ролей (student, teacher, admin)
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // авторизован ли пользователь
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        //есть ли у пользователя роль
        if (!$user->role) {
            abort(403, 'Роль пользователя не определена.');
        }

        //соответствует ли роль пользователя 
        foreach ($roles as $role) {
            if ($user->role->name === $role) {
                return $next($request);
            }
        }

        // Если роль не подходит — выдаем ошибка 403
        abort(403, 'Доступ запрещён.');
    }
}
