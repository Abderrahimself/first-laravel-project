<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function showCorrectHomepage()
    {
        // return auth()->check() ? view('homepage-fade') : view('homepage');
        if (auth()->check()) {
            return view('homepage-fade');
        } else {
            return view('homepage');
        }
    }

    public function login(Request $request)
    {
        $incommingFiels = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required'
        ]);

        if (auth()->attempt(['username' => $incommingFiels['loginusername'], 'password' => $incommingFiels['loginpassword']])) {
            return redirect()->to('/')->with('success', 'You have successfully logged in, enjoy');
        } else {
            return redirect()->to('/')->with('failure', 'Invalid login, try again ');
        }
    }

    public function register(Request $request)
    {
        $incommingFiels = $request->validate([
            'username' => ['required', 'min:3', 'max:20', Rule::unique('users', 'username')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'confirmed']
        ]);

        // $incommingFiels['password'] = bcrypt($incommingFiels['password']);

        $user = User::create($incommingFiels);
        auth()->login($user);
        return redirect()->to('/')->with('success', 'Thank you for creating an account, explore');
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->to('/')->with('success', 'You are now logged out, see you again');
    }
}
