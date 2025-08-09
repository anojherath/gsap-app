<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Show list of users with search and pagination.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::with('userType')
            ->when($search, function ($query, $search) {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('nic', 'like', "%{$search}%")
                    ->orWhere('mobile_number', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.user_list', compact('users', 'search'));
    }

    /**
     * Show the user registration form.
     */
    public function create()
    {
        $userTypes = UserType::all();
        return view('admin.user_registration', compact('userTypes'));
    }

    /**
     * Show the edit form for an existing user.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $userTypes = UserType::all();
        return view('admin.user_registration', compact('user', 'userTypes'));
    }

    /**
     * Store a newly created user in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'nic'            => 'required|string|unique:users,nic',
            'password'       => 'required|string|min:6',
            'mobile_number'  => 'required|string|max:10',
            'address'        => 'required|string|max:255',
            'user_type_id'   => 'required|exists:user_types,id',
            'image_url'      => 'nullable|string',
            'company_name'   => 'nullable|string|max:255',
            'reg_number'     => 'nullable|string|max:255',
        ]);

        $user = new User();
        $user->first_name     = $request->first_name;
        $user->last_name      = $request->last_name;
        $user->nic            = $request->nic;
        $user->password       = Hash::make($request->password);
        $user->mobile_number  = $request->mobile_number;
        $user->address        = $request->address;
        $user->user_type_id   = $request->user_type_id;
        $user->image_url      = $request->image_url;
        $user->company_name   = $request->company_name;
        $user->reg_number     = $request->reg_number;
        $user->creation_date  = Carbon::now();

        $user->save();

        return redirect()->route('admin.user_registration')->with('success', 'User registered successfully!');
    }

    /**
     * Update an existing user in the database.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'nic'            => 'required|string|unique:users,nic,' . $id,
            'mobile_number'  => 'required|string|max:10',
            'address'        => 'required|string|max:255',
            'user_type_id'   => 'required|exists:user_types,id',
            'image_url'      => 'nullable|string',
            'company_name'   => 'nullable|string|max:255',
            'reg_number'     => 'nullable|string|max:255',
        ]);

        $user->first_name     = $request->first_name;
        $user->last_name      = $request->last_name;
        $user->nic            = $request->nic;
        $user->mobile_number  = $request->mobile_number;
        $user->address        = $request->address;
        $user->user_type_id   = $request->user_type_id;
        $user->image_url      = $request->image_url;
        $user->company_name   = $request->company_name;
        $user->reg_number     = $request->reg_number;

        $user->save();

        return redirect()->route('admin.user_registration')->with('success', 'User updated successfully!');
    }

    /**
     * Delete a user from the system.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.user_registration')->with('success', 'User deleted successfully.');
    }
}
