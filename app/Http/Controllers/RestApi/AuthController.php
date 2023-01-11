<?php

namespace App\Http\Controllers\RestApi;

use App\Http\Controllers\Controller;
use  App\Http\Services\TeacherService;
use  App\Http\Services\TeacherLoginService;
use  App\Http\Services\CommonService;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {

    }
    public function TeacherRegister(TeacherService $teacherService)
    {
        return $teacherService->teacherRegisterService();
    }

    public function TeacherLogin(TeacherLoginService $teacherLoginService)
    {
        return $teacherLoginService->teacherLoginService();

    }
    public function TeacherDetails(CommonService $commonService)
    {
        return $commonService->teacherProfile(Auth::user());
    }

    public function TeacherProfileUpdate(CommonService $commonService)
    {
        return $commonService->teacherUpdateProfile(Auth::user());
    }
}
