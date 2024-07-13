<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\{LoginRequest, RegisterRequest};
use App\Traits\ResponsesTrait;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Enums\TokenAbility;
use App\Services\AddressFormatter;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ResponsesTrait;
    public function register(RegisterRequest $request)
    {
        $address = AddressFormatter::formatAddress($request->postal_code, $request->country, $request->city);

        $user = User::create([
            'ip' => $request->ip(),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'date_of_birth' => $request->date_of_birth,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'address' => $address

        ]);
        $user->save();
        $accessToken = $user->createToken('access_token', [TokenAbility::ACCESS_API->value], Carbon::now()->addMinutes(config('sanctum.ac_expiration')));
        $refreshToken = $user->createToken('refresh_token', [TokenAbility::ISSUE_ACCESS_TOKEN->value], Carbon::now()->addMinutes(config('sanctum.rt_expiration')));

        return $this->sendSuccessWithToken($user, 'User Registered successfully', 201, $accessToken->plainTextToken, $refreshToken->plainTextToken);
    }
    //-----------------------------------------------------------------------------------------

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return $this->sendFail('User not found', 404);
        } else if (!Hash::check($request->password, $user->password)) {
            return $this->sendFail('Invalid password', 422);
        } else {

            $accessToken = $user->createToken('access_token', [TokenAbility::ACCESS_API->value], Carbon::now()->addMinutes(config('sanctum.ac_expiration')));
            $refreshToken = $user->createToken('refresh_token', [TokenAbility::ISSUE_ACCESS_TOKEN->value], Carbon::now()->addMinutes(config('sanctum.rt_expiration')));
            $user->save();
            return $this->sendSuccessWithToken($user, 'User Logged In successfully', 200, $accessToken->plainTextToken, $refreshToken->plainTextToken);
        }
    }
    //-----------------------------------------------------------------------------------------
    public function logout(Request $request)
    {
        if (!$request) {
            return $this->sendFail('Unauthenticated', 401);
        }
        $request->user()->tokens()->delete();
        return $this->sendSuccess(null, 'Tokens Revoked', 200);
    }
}
