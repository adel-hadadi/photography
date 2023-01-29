<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidOtpCode;
use App\Exceptions\InvalidOtpCodeException;
use App\Models\Auth\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function sendCode(Request $request)
    {
        $phoneNumber = $request->get('phone_number');
        $user = User::firstOrCreate(['phone_number' => $phoneNumber]);

        $user->otpCodes()->create([
            'code' => rand(111111, 999999),
        ]);

        return response()->json([
            'user' => [
                'phone_number' => $user->phone_number,
                'id' => $user->id
            ]
        ]);
    }

    public function confirmCode(Request $request, User $user)
    {
        $request->validate(['code' => 'required|min:6|max:6']);

        $code = $request->get('code');
        $otpCode = $user->otpCodes()->latest()->where('used_at', null)->first();

        if (is_null($otpCode) || $otpCode->code !== $code)
            throw new InvalidOtpCodeException();

        $user['token'] = $user->createToken('customer-auth')->plainTextToken;

        return response()->json(['user' => $user]);
    }
}
