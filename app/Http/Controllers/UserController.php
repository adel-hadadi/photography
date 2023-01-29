<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function editProfile(Request $request): JsonResponse
    {
        $user = \request()->user();

        $inputs = $request->only(['first_name', 'last_name', 'zone_id', 'latitude', 'longitude', 'avatar']);

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $inputs['avatar'] = $file->move(public_path('/images' . DIRECTORY_SEPARATOR . 'avatars'), $file->getClientOriginalName())->getRealPath();
        }

        $user->update($inputs);
        return response()->json([
            'user' => $user
        ]);
    }
}
