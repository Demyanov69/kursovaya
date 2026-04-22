<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\ActivityLogger;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        ActivityLogger::log(
            'logout',
            'Пользователь вышел из системы'
        );
        Auth::logout();


        // очистка сессии
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
