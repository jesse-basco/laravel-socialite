<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $new_user = array(
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        );

        try{
            User::create($new_user);
            return response()->json($new_user, 200);
        }catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function sign_in(Request $request) {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = User::where('email', $request->email)->first();
            $token = $user->createToken('Sign-in')->accessToken;
            return response()->json([
                "message" => "Signed-in successfully",
                "token" => $token,
                "user" => $user
            ], 200);
        } else {
            return response()->json([
                "message" => "User not found",
            ], 401);
        }
    }

    public function facebook_sign_in_redirect() {
        return Socialite::driver('facebook')->stateless()->redirect();
    }

    public function handle_facebook_callback()
    {
        $facebook_user = Socialite::driver('facebook')->stateless()->user();
        $user = User::where('provider_id', $facebook_user->getId())
            ->orWhere('email', $facebook_user->getEmail())
            ->first();
        
        if(!$user) {
            $user = User::create([
                'name' => $facebook_user->getName(),
                'email' => $facebook_user->getEmail(),
                'provider' => $facebook_user,
                'provider_id' => $facebook_user->getId()
            ]);
        }

        $token = $user->createToken('socialite-auth')->accesToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ],200);
    }
}
