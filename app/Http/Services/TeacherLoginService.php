<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Http\Request;

class TeacherLoginService
{
    private $user;
    private $request;

    public function __construct(User $user,Request $request)
    {
        $this->user = $user;
        $this->request = $request;
    }

    public function teacherLoginService()
    {
        $request = $this->request->only(['email', 'password']);
        $data = [
            'email' => $request['email'],
            'password' => $request['password']
        ];
        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }
}
