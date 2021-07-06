<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Register page view
     *
     * @return \Illuminate\Http\RedirectResponse
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register_post(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "first_name" => "required|min:2|string",
            "last_name" => "required|min:2|string",
            "email" => "required|email",
            "phone" => "required|unique:users",
            "password" => "required|required_with:password_confirmation|confirmed|min:8",
            "password_confirmation" => "required|same:password",
        ], [
            "first_name.required" => "Le nom est requis",
            "first_name.min" => "Le nom doit avoir au moins deux (2) caractères",
            "first_name.string" => "Le nom n'est pas au bon format",
            "last_name.required" => "Ajoutez au moins un prénom",
            "last_name.min" => "Le prénom doit avoir au moins deux (2) caractères",
            "last_name.string" => "Le prénom n'est pas au bon format",
            "email.email" => "L'adresse mail n'est pas au bon format",
            "email.required" => "L'adresse mail est obligatoire",
            "password.required" => "Le mot de passe est obligatoire",
            "password.confirmed" => "Les mots de passe doivent être les même",
            "password.min" => "Le mot de passe doit avoir au moins huit (8) caractères",
            "password_confirmation.required" => "Le mot de passe est requis",
            "password_confirmation.same" => "Les mots de passe doivent être les mêmes",
            "phone.required" => "Le numéro de téléphone est obligatoire.",
            "phone.unique" => "Ce numéro de téléphone est déjà utilisé.",
        ]);

        if ($validator->fails()) {
            Log::error("Erreur lors de l'inscription : " . json_encode($request->all()));
            toastr()->warning("Veuillez correctement remplir les champs.");
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $data = $request->only("first_name", "last_name", "email", "password", "phone");

            $data["password"] = Hash::make($data["password"]);
            $data["last_login_at"] = Carbon::now();
            $data["last_login_ip"] = $request->getClientIp();
            // Génération d'un slug
            $slug = Str::random(6);
            while(User::where("slug", $slug)->first() != null) {
                $slug = Str::random();
            }
            $data["slug"] = $slug;

            $user = User::create($data);

            Auth::login($user);

            toastr()->success("Bienvenue " . Auth::user()->first_name ." ". Auth::user()->last_name);

            return redirect()->intended('home');
        } catch (\Exception $e) {
            Log::error("Error register => Hour : " . date('Y-m-d H:i:s'));
            Log::error("Error register => " . $e->getMessage());
            toastr()->error("Une erreur est survenue.");
            return redirect()->back();
        }
    }

    /**
     * Login post
     *
     * @return void
     */
    public function login_post(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email",
            "password" => "required",
            "remember_me" => "integer",
        ], [
            "email.required" => "L'adresse mail est obligatoire",
            "email.email" => "Veuillez bien entrer l'adresse mails",
            "password.required" => "Le mot de passe est obligatoire"
        ]);

        if ($validator->fails()) {
            Log::info("Tentative de connexion échouée : " . json_encode($request->all()));
            toastr()->warning('Veuillez bien remplir les champs s\'il vous plaît');
            return redirect('/login')
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->except("_token", "_method");
        $remember = isset($data["remember_me"]); // Vérifie si l'utilisateur à coché "se rappeler de moi"
        $credentials = $request->only("email", "password");

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $user->last_login_at = Carbon::now();
            $user->last_login_ip = $request->getClientIp();
            $user->save();

            Log::info("Nouvelle Connexion " . json_encode($user));

            toastr()->success("Bienvenue Mme / M " . $user->first_name . ' ' . $user->last_name);

            return redirect()->intended('home');
        }
        toastr()->error("Adresse mail ou mot de passe incorrect.");
        return back();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        toastr()->success("Vous êtes déconnecté.");

        return redirect('/');
    }
}
