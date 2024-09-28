<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function registerSeller(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'country' => 'required|string',
            'province' => 'required|string',
            'city' => 'required|string',
            'district' => 'required|string',
            'postal_code' => 'required|string',
            'photo' => 'required',

        ]);

        $photo = null;
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo')->store('assets/user', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'country' => $request->country,
            'province' => $request->province,
            'city' => $request->city,
            'district' => $request->district,
            'postal_code' => $request->postal_code,
            'roles' => 'seller',
            'photo' => $photo,
        ]);

        // $token = $user->createToken('token-name')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'User registered',
            'data' => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'The provided credentials are incorrect.',
            ], 401);
        }

        $token = $user->createToken('token-name')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'User logged in',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Token revoked',
        ], 200);
    }

    public function registerBuyer(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User registered',
            'data' => $user,
        ], 201);
    }

    //update fcm token
    public function updateFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = $request->user();
        $user->fcm_token = $request->fcm_token;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'FCM token updated'
        ], 200);
    }

    public function getSellerProfile(Request $request)
    {
        $user = $request->user();

        // Pastikan user adalah seller
        if ($user->roles !== 'seller') {
            return response()->json([
                'status' => 'failed',
                'message' => 'Unauthorized',
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Seller profile retrieved',
            'data' => $user,
        ], 200);
    }

    public function updateSellerProfile(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $request->user()->id,
            'phone' => 'sometimes|string',
            'address' => 'sometimes|string',
            'country' => 'sometimes|string',
            'province' => 'sometimes|string',
            'city' => 'sometimes|string',
            'district' => 'sometimes|string',
            'postal_code' => 'sometimes|string',
            'photo' => 'sometimes|image',
        ]);

        $user = $request->user();

        // Pastikan user adalah seller
        if ($user->roles !== 'seller') {
            return response()->json([
                'status' => 'failed',
                'message' => 'Unauthorized',
            ], 403);
        }

        // Update photo if provided
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo')->store('assets/user', 'public');
            $user->photo = $photo;
        }

        // Update the rest of the profile fields including email
        $user->update($request->only([
            'name',
            'email',
            'phone',
            'address',
            'country',
            'province',
            'city',
            'district',
            'postal_code'
        ]));

        return response()->json([
            'status' => 'success',
            'message' => 'Seller profile updated',
            'data' => $user,
        ], 200);
    }

    public function getBuyerProfile(Request $request)
    {
        $user = $request->user();

        // Pastikan user adalah buyer
        if ($user->roles !== 'buyer') {
            return response()->json([
                'status' => 'failed',
                'message' => 'Unauthorized',
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Buyer profile retrieved',
            'data' => $user,
        ], 200);
    }

    public function updateBuyerProfile(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $request->user()->id,
            'phone' => 'sometimes|string',
            'address' => 'sometimes|string',
        ]);

        $user = $request->user();

        // Pastikan user adalah buyer
        if ($user->roles !== 'buyer') {
            return response()->json([
                'status' => 'failed',
                'message' => 'Unauthorized',
            ], 403);
        }

        // Update the rest of the profile fields including email
        $user->update($request->only([
            'name',
            'email',
            'phone',
            'address'
        ]));

        return response()->json([
            'status' => 'success',
            'message' => 'Buyer profile updated',
            'data' => $user,
        ], 200);
    }
}
