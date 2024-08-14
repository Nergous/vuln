<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('login', 'password');


        if (Auth::attempt($credentials)) {
            return redirect()->intended('/');
        }

        return back()->withErrors(['login' => 'Invalid credentials']);
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'login' => 'required|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'login' => $request->login,
            'password' => Hash::make($request->password),
            'type' => 'Operator',
        ]);

        Auth::login($user);

        return redirect('/');
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'login' => 'required|unique:users',
            'password' => 'required|min:6',
            'type' => 'required|in:Admin,Operator',
        ]);

        $user = User::create([
            'login' => $request->login,
            'password' => Hash::make($request->password),
            'type' => $request->type,
        ]);


        return redirect('/users');
    }

    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'login' => 'nullable|unique:users,login,' . $user->id,
            'password' => 'nullable|min:6',
            'type' => 'required|in:Admin,Operator',

        ]);
        if ($user->login != $request->login && $request->login != null) {
            $login = $request->login;
            $user->login = $login;
        }
        if ($user->password != $request->password && $request->password != null) {
            $password = $request->password;
            $password = Hash::make($password);
            $user->password = $password;
        }
        if ($user->type != $request->type) {
            $type = $request->type;
            $user->type = $type;
        }
        $user->save();
        return redirect()->route('users.index');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index');
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}