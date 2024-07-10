<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function store(Request $request)
    {
        // Validation des données entrantes avec Validator::make()
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'sometimes|string|min:8',
            'img' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'verified' => 'required|boolean',
        ]);

        // Vérifier si la validation a échoué
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            // Création de l'utilisateur en instanciant un nouvel objet User
            $user = new User();
            $user->name = $request->input('first_name') . ' ' . $request->input('last_name');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->img = $request->input('img');
            $user->last_name = $request->input('last_name');
            $user->first_name = $request->input('first_name');
            $user->phone = $request->input('phone');
            $user->verified = $request->input('verified');

            // Sauvegarde de l'utilisateur en base de données
            $user->save();

            // Retourne une réponse JSON avec le message de succès et les détails de l'utilisateur créé
            return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
        } catch (\Exception $e) {
            // En cas d'erreur, retournez une réponse avec le code d'erreur approprié
            return response()->json(['message' => 'Failed to create user', 'error' => $e->getMessage()], 500);
        }
    }

    public function storeBatch(Request $request)
    {
        $users = $request->all();

        $validatedUsers = [];

        foreach ($users as $user) {
            // dump($user); // Pour voir les données avant validation

            // Validation des données entrantes
            $validator = Validator::make($user, [
                'name' => 'sometimes|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'sometimes|string|min:8', // Utiliser sometimes pour le champ facultatif
                'img' => 'nullable|string|max:255',
                'last_name' => 'required|string|max:255',
                'first_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'verified' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                // Affiche les erreurs de validation
                // dump($validator->errors()->all());
            } else {
                $validatedUsers[] = $validator->validated();
            }
        }

        // dd($validatedUsers);
        // Création des utilisateurs
        foreach ($validatedUsers as $validatedUser) {

            $user = new User();
            $user->img =  $validatedUser['img'];
            $user->last_name =  $validatedUser['last_name'];
            $user->first_name = $validatedUser['first_name'];
            $user->name = $validatedUser['first_name'] . ' ' . $validatedUser['last_name'];
            $user->email = $validatedUser['email'];
            $user->phone = $validatedUser['phone'];
            $user->verified = $validatedUser['verified'];
            $user->password =  Hash::make($validatedUser['password']);

            // Sauvegarde de l'utilisateur en base de données
            $user->save();
        }

        return response()->json(['message' => 'Users created successfully'], 201);
    }
}
