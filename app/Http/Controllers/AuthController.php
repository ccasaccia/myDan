<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function index()
    {
        return Inertia::render('Users', [
            'users' => User::paginate(5)
        ]);

    }
    public function register(Request $request)
    {
        // sleep(1);
        //Validate

        $fields = $request->validate([
            'avatar' => ['file','nullable','max:800'],
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed']
        ]);

        if($request->hasFile('avatar'))
        {
            // dd($request->avatar,Storage::disk('public')->put('avatars', $request->avatar));
          $fields['avatar'] = Storage::disk('public')->put('avatars', $request->avatar);
        }
            //Register
            $user = User::create($fields);

            //Login
            Auth::login($user);

            //redirect
            return redirect()->route('dashboard')->with('success', 'User created and logged in successfully');
    }
    public function login(Request $request)
    {
        //Validate
        $fields = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'min:8']
            ]);

        if(Auth::attempt($fields, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard')->with('success', 'Logged in successfully');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');

    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('dashboard');
    }
}
