<?php

namespace App\Http\Controllers;

use Kreait\Firebase\Auth as FirebaseAuth;
use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;

class FirebaseAuthController extends Controller
{
    // Utilisation de la variable pour accéder à Firebase Auth
    protected $auth;

    public function __construct() {
        // Initialisation de Firebase Auth
        $this->auth = Firebase::auth();
    }

    public function auth(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        
        try {
            // Connexion avec l'email et le mot de passe
            $signInResult = $this->auth->signInWithEmailAndPassword($email, $password);
            
            // Récupérer l'UID de l'utilisateur après la connexion réussie
            $uid = $signInResult->firebaseUserId();
            
            // Créer un custom token à partir de l'UID
            $customToken = $this->auth->createCustomToken($uid);

            // Retourner le Custom Token dans la réponse
            return response()->json([
                'customToken' => $customToken->toString()
            ]);
            
        } catch (\Kreait\Firebase\Exception\Auth\InvalidPassword $e) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
            return response()->json(['error' => 'User not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong: '.$e->getMessage()], 500);
        }
    }

    public function verifyToken(Request $request)
    {
        // Récupère le token depuis le corps de la requête
        $idTokenString = $request->input('idToken');

        try {
            // Vérifie le token avec $this->auth
            $verifiedIdToken = $this->auth->verifyIdToken($idTokenString);
            
            // Si le token est valide, vous pouvez récupérer l'UID de l'utilisateur
            $uid = $verifiedIdToken->claims()->get('sub');
            
            // Vous pouvez aussi obtenir l'utilisateur de Firebase
            $user = $this->auth->getUser($uid);

            return response()->json([
                'message' => 'Token valid',
                'user' => $user
            ], 200);

        } catch (FailedToVerifyToken $e) {
            return response()->json([
                'error' => 'The token is invalid: '.$e->getMessage()
            ], 401);
        }
    }
}
