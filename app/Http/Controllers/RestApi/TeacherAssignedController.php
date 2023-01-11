<?php

namespace App\Http\Controllers\RestApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\TeacherAssignService;

class TeacherAssignedController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function assignTeacher(TeacherAssignService $assignservice)
    {
        return $assignservice->assign();
    }
}
