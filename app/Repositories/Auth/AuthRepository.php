<?php

namespace App\Repositories\Auth;

use App\Http\Resources\UserInfoLoginResource;
use App\Http\Resources\UserInfoResource;
use App\Models\User;
use App\Repositories\Auth\AuthRepositoryInterface;
use App\Traits\ApiResponderTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class AuthRepository implements AuthRepositoryInterface
{
    use ApiResponderTrait;
    public function login($request)
    {
        // TODO: Implement login() method.

        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        $user = User::where('email', $request['email'])->with(['roles'])->first();
        if (auth()->attempt($data)){
            $token = $user->createToken('Social-Media')->accessToken;
            return $this->returnData(collect(new UserInfoLoginResource($user))->put('token', $token), 'login has been successfully',200);
        }
        return $this->returnError(401, 'The login information is incorrect, please try again');
    }

    public function userInfo()
    {
        // TODO: Implement userInfo() method.

        $user = Auth::user();
        if ($user) {

            return $this->returnData(new UserInfoResource($user->load(['posts','post_images'])),'this is your info profile',200);
        }
        return $this->returnError(403, 'You are not authorized to enter');
    }

    public function logout()
    {
        // TODO: Implement logout() method.

        $user = Auth::user()->token();
        $user->revoke();
        return $this->returnSuccessMessage('logged out successfully',200);
    }
}
