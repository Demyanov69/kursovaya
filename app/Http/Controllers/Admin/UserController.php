<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\ActivityLogger;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();
        return view('admin.users', compact('users'));
    }
    public function create()
    {
        $roles = Role::all();
        return view('admin.users', compact('roles'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role_id' => $request->input('role_id'),
            'faculty' => $request->input('faculty'),
            'direction' => $request->input('direction'),
            'course_year' => $request->input('course_year'),
        ]);

        ActivityLogger::log(
            'user_created_admin',
            'Администратор создал пользователя: ' . $user->name . ' (' . $user->email . ')'
        );

        return redirect()->route('admin.users.index')
            ->with('success', 'Пользователь создан.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();

        return view('admin.user_form', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
        ]);
        $data = $request->only(['name', 'email', 'role_id', 'faculty', 'direction', 'course_year']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }
        $user->update($data);
        ActivityLogger::log(
            'user_updated_admin',
            'Администратор обновил пользователя: ' . $user->name . ' (' . $user->email . ')'
        );
        return redirect()->route('admin.users.index')
            ->with('success', 'Данные пользователя обновлены.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        ActivityLogger::log(
            'user_deleted_admin',
            'Администратор удалил пользователя: ' . $user->name . ' (' . $user->email . ')'
        );

        $user->delete();

        return back()->with('success', 'Пользователь удалён.');
    }
}
