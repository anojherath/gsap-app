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

        $user = User::with('userType')->where('nic', $request->nic)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);

            $userType = strtolower($user->userType->user_type);

            switch ($userType) {
                case 'admin':
                    return redirect()->route('dashboard.admin');
                case 'farmer':
                    return redirect()->route('dashboard.farmer');
                case 'seed provider':
                    return redirect()->route('dashboard.seed_provider');
                case 'fertilizer provider':
                    return redirect()->route('dashboard.fertilizer_provider');
                case 'agro-chemical provider':
                    return redirect()->route('dashboard.agro_chemical_provider');
                case 'harvest buyer':
                    return redirect()->route('dashboard.harvest_buyer');
                default:
                    Auth::logout();
                    return redirect('/login')->withErrors(['user_type' => 'Unauthorized user type']);
            }
        }

        return back()->withErrors(['nic' => 'Invalid NIC or password.']);
    }    

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
