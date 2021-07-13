<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('users.index', ["users" => $users]);
    }

    public function profile()
    {
        return view('users.profile', ["user" => auth()->user()]);
    }

    public function update_avatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "file" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
        ], [
            "file.required" => "Il n'y a aucune image a uploader",
            "file.image" => "Uploader uniquement une image",
            "file.mimes" => "L'image doit être au format : jpeg,png,jpg,gif,svg uniquement",
            "file.max" => "L'image doit être d'au plus 2Mo",
        ]);

        if ($validator->fails()) {
            Log::alert("Erreur lors de la modification de la photo de profile " . json_encode($request->all()));
            toastr()->warning("Veuillez uploader une image correcte.");
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        try {
            $file = $request->file;
            $path = "uploaded". DIRECTORY_SEPARATOR ."users". DIRECTORY_SEPARATOR ."profile";

            $result = Helpers::addFile($file, $path);

            $toSave = $path. DIRECTORY_SEPARATOR .$result["fileName"];
            $user = Auth::user();
            $user->avatar_path = $toSave;
            $rs = $user->save();
            if ($rs) {
                toastr()->success("Image modifiée avec succès");
                return redirect()->back();
            }
            toastError("Une erreur est survenue");
            return back();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            toastError("Une erreur est survenue");
            return redirect()->back();
        }
    }

    public function update_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "first_name" => "required|min:2|string",
            "last_name" => "required|min:2|string",
            "email" => "required|email",
            "phone" => "required|min:10|max:10",
            "title" => "nullable|min:2",
        ], [
            "first_name.required" => "Le nom est requis",
            "first_name.min" => "Le nom doit avoir au moins deux (2) caractères",
            "first_name.string" => "Le nom n'est pas au bon format",
            "last_name.required" => "Ajoutez au moins un prénom",
            "last_name.min" => "Le prénom doit avoir au moins deux (2) caractères",
            "last_name.string" => "Le prénom n'est pas au bon format",
            "email.email" => "L'adresse mail n'est pas au bon format",
            "email.required" => "L'adresse mail est obligatoire",
            "phone.required" => "Le numéro de téléphone est obligatoire.",
            "phone.min" => "Le numéro de téléphone doit être de 10 chiffres.",
            "phone.max" => "Le numéro de téléphone doit être de 10 chiffres.",
            "title.min" => "Le titre peut être vide, mais doit être d'au moins 2 caractères s'il est rempli.",
        ]);

        if ($validator->fails()) {
            Log::alert("Erreur lors de la modification du profile " . json_encode($request->all()));
            toastr()->warning("Veuillez correctement remplir les champs.");
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        try {
            $data = $request->only("first_name", "last_name", "email", "phone", "title");
            $user = auth()->user();
            $user->update($data);

            toastSuccess("Mise à jour du profile effectué avec succès.");
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            toastError("Une erreur est survenue");
            return redirect()->back();
        }
    }

    public function update_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "old_password" => "required",
            "password" => "required|min:8|confirmed",
            "password_confirmation" => "required|min:8|same:password",
        ], [
            "old_password.required" => "L'ancien mot de passe est requis",
            "new_password.required" => "Le nouveau mot de passe est requis",
            "new_password.min" => "Le nouveau mot de passe doit être d'au moins 8 caractères",
            "password_confirmation.required" => "Veuillez retaper le nouveau mot de passe",
            "password_confirmation.same" => "Les mots de passe ne sont pas identique",
        ]);

        if ($validator->fails()) {
            Log::alert("Erreur lors de la modification du mot de passe " . json_encode($request->all()));
            toastr()->warning("Veuillez correctement remplir les champs.");
            return redirect()->back()
                ->withErrors($validator)->withInput();
        }

        try {
            $data = $request->only('old_password', 'password', 'password_confirmation');
            $user = auth()->user();
            // If passwords are same
            if (Hash::check($data["old_password"], $user->getAuthPassword())) {
                // If old_password and new password are not same
                if (!Hash::check($data["password"], $user->getAuthPassword())) {
                    $newPassword = Hash::make($data["password"]);
                    $user->password = $newPassword;
                    $user->save();

                    toastSuccess("Mot de passe modifié avec succès.");
                    return redirect()->back();
                } else {
                    toastWarning("Le nouveau mot de passe est le même que l'ancien mot de passe");
                    return redirect()->back();
                }
            } else {
                toastWarning("L'ancien mot de passe ne correspond pas à nos enregistrement.");
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            toastError("Une erreur est survenue");
            return redirect()->back();
        }
    }
}
