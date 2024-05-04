<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function showHomepage()
    {
        return view('homepage-fade');
    }

    public function showLoginForm()
    {
        return view('homepage');
    }

    public function login(Request $request)
    {
        $incomingFields = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required'
        ]);

        if (auth()->attempt(['username' => $incomingFields['loginusername'], 'password' => $incomingFields['loginpassword']])) {
            return redirect()->route('home')->with('success', 'You have successfully logged in, enjoy');
        } else {
            return redirect()->route('home')->with('failure', 'Invalid login, try again ');
        }
    }

    public function register(Request $request)
    {
        $incomingFields = $request->validate([
            'username' => ['required', 'min:3', 'max:20', Rule::unique('users', 'username')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'confirmed']
        ]);

        $user = User::create($incomingFields);
        auth()->login($user);
        return redirect()->route('home')->with('success', 'Thank you for creating an account, explore');
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('home')->with('success', 'You are now logged out, see you again');
    }
}
