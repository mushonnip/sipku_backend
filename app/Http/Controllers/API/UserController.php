<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Actions\Fortify\PasswordValidationRules;
use Exception;

class UserController extends Controller
{
    use PasswordValidationRules;

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);

            $credentials = request(['email', 'password']);
            if (!Auth::attempt($credentials)) {
                return ResponseFormatter::error([
                    'message' => 'Unauthorized'
                ],'Authentication Failed', 500);
            }

            $user = User::where('email', $request->email)->first();
            if ( ! Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Invalid Credentials');
            }

            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ],'Authenticated');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ],'Authentication Failed', 500);
        }
    }

    public function register(Request $request)
    {
        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        try {
            $user = User::create($data);
            return ResponseFormatter::success($user, 'User berhasi dibuat');
        } catch (Exception $e) {
            return ResponseFormatter::error($e, 'Registrasi Gagal');
        }
    }
    public function logout()
    {
        // return ResponseFormatter::success($request, 'Sukses');
        // try {
        //     $user = Auth::user();
        //     $user->tokens()->delete();
        //     // $user = $request->user();
        //     // $user->currentAccessToken()->delete();
        //     return ResponseFormatter::success(null, 'Sukses');
        // } catch (Exception $e) {
        //     return ResponseFormatter::error($e, 'Gagal');
        // }
    }
}
