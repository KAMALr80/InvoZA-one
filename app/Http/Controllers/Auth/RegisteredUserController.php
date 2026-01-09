<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration view.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
 public function store(Request $request)
{
     $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'staff',
        'status' => 'pending',
    ]);

    return redirect('/login')
        ->with('success','Account created. Wait for admin approval.');



        // ✅ AUTO EMPLOYEE CREATE (VERY IMPORTANT FOR ATTENDANCE)
        Employee::create([
            'user_id'       => $user->id,
            'employee_code' => 'EMP' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
            'name'          => $user->name,
            'email'         => $user->email,
            'status'        => 1,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('attendance.my');
    }
}
