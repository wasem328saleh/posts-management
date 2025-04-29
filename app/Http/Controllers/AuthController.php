<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Repositories\Auth\AuthRepositoryInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthRepositoryInterface $auth;

    /**
     * @param AuthRepositoryInterface $auth
     */
    public function __construct(AuthRepositoryInterface $auth)
    {
        $this->auth = $auth;
    }

    public function login(LoginRequest $request)
    {
        return $this->auth->login($request);
    }
    public function userInfo()
    {
        return $this->auth->userInfo();
    }
    public function logout()
    {
        return $this->auth->logout();
    }
}
