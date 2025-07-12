<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nic' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('nic', $request->nic)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Login success
            Auth::login($user);
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'nic' => 'Invalid NIC or password.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
