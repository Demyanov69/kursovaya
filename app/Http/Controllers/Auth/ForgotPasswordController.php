<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    // Форма восстановления пароля
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    // Генерация токена и его сохранение
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // удаляем старые токены
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // создаем новый токен
        DB::table('password_reset_tokens')->insert([
            'email'      => $request->email,
            'token'      => Str::random(64),
            'created_at' => Carbon::now(),
        ]);

        // в реальном приложении здесь отправляется email
        return back()->with('success', 'Ссылка для сброса пароля была сгенерирована.');
    }
}
