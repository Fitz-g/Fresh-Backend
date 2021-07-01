<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Register page view
     *
     * @return void
     */
    public function register()
    {
        if (auth()->check()) {
            return redirect()->back();
        }
        return view('auth.register');
    }
    /**
     * Return login page view
     *
     * @return void
     */
    public function login()
    {
        if (auth()->check()) {
            return redirect()->back();
        }
        return view('auth.login');
    }

    /**
     * Register post
     *
     * @return void
     */
    public function register_post(Request $request)
    {
        $validator = Validator::make($request->all(), [], []);
    }

    /**
     * Login post
     *
     * @return void
     */
    public function login_post(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "email|required",
            "password" => "required",
            "remember_me" => "nullable",
        ], []);
    }
}
