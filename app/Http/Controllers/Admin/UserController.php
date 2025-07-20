<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index()
    {
        $userTypes = UserType::all(); // Fetch user types for dropdown
        return view('admin.user_registration', compact('userTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'nic'        => 'required|string|unique:users,nic',
            'password'   => 'required|string|min:6',
            'mobile_number' => 'required|string|max:10',
            'address'    => 'required|string|max:255',
            'user_type_id' => 'required|exists:user_types,id',

            // Optional fields
            'image_url'     => 'nullable|string',
            'company_name'  => 'nullable|string|max:255',
            'reg_number'    => 'nullable|string|max:255',
            
        ]);

        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->nic = $request->nic;
        $user->password = Hash::make($request->password);
        $user->mobile_number = $request->mobile_number;
        $user->address = $request->address;
        $user->user_type_id = $request->user_type_id;

         // Optional fields
        $user->image_url      = $request->image_url;
        $user->company_name   = $request->company_name;
        $user->reg_number     = $request->reg_number;
        
        $user->creation_date = Carbon::now();

        $user->save();

        return redirect()->route('admin.user_registration')->with('success', 'User registered successfully!');
        
    }

    public function create()
    {
        $userTypes = UserType::all();
        return view('admin.user_registration', compact('userTypes'));
    }

}

