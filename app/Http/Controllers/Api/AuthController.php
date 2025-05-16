<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginUserRequst;
use App\Http\Requests\ApiLoginRequest;
use App\Models\User;
use App\Permissions\V1\Abilities;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponses;

    public function login(LoginUserRequst $request)
    {

        $request->validated($request->all());
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return $this->error('invalid credentials', 401);
        }
        $user = User::firstWhere('email', $request->email);

        return $this->ok(
            "Authenticated",
            [
                'token' => $user->createToken('API TOKEN FOR' . $user->email,
                    Abilities::getAbilities($user), //abilities : for each use: [ticket:create , ticket:own:update]
                    now()->addMonth())
                    ->plainTextToken,
            ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->ok('');
    }

}
