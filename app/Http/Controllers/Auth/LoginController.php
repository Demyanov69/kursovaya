<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Показать страницу входа
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Обработка запроса входа
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors(['email' => 'Неверный email или пароль.']);
        }

        $request->session()->regenerate();
        $user = Auth::user();

        // Перенаправляем по роли
        if ($user->isStudent()) {
            return redirect('/student');
        } elseif ($user->isTeacher()) {
            return redirect('/teacher');
        } elseif ($user->isAdmin()) {
            return redirect('/admin');
        }

        // fallback
        return redirect('/');
    }
}
