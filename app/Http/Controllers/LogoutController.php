<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function show()
    {
        return view('logout');
    }
    public function logout(Request $request)
    {
        Auth::logout(); // Log the user out

        // Invalidate the session to prevent reuse
        $request->session()->invalidate();

        // Regenerate the CSRF token to prevent CSRF attacks
        $request->session()->regenerateToken();

        // Redirect to the login or home page
        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }

}
