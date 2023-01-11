<?php

namespace App\Http\Controllers\RestApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\UserApprovalService;
use App\Models\RefStatus;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserProfileApprovedController extends Controller
{
    private $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function approveProfile()
    {
        return $this->approveUserProfile();
    }

    public function approveUserProfile()
    {
        try {
            $Validator = Validator::make($this->request->only('user_id', 'status_id'), [
                'user_id' => 'required|int',
                'status_id' => 'required|int',
            ]);
            $user = User::with('profile')->whereHas('roles', function ($q) {
                return $q->where('name', '!=', 'Admin');
            })->find($this->request->user_id);
            if ($Validator->fails()) {
                return  $Validator->errors();
            }
            if (!empty($user) && !empty($user->profile)) {
                if ($this->request->status_id != $user->profile->ref_status_id) {
                    $statusId = RefStatus::whereId($this->request->status_id)->first();
                    if (!empty($statusId)) {
                        $statusChanged =  $user->profile->update([
                            'ref_status_id' => $this->request->status_id,
                        ]);
                        return response()->json(['success' => 'User Profile Status updated.','status' => 200,'user' => $user], 200);
                        // if ($statusChanged) {
                        //     $user->notify(new UserApproved($user));
                        // }
                    }else {
                        return response()->json(['error' => 'Invalid Status Code.'], 404);
                    }
                }else {
                    return response()->json(['success' => 'User Profile Status updated.','status' => 304,'user' => $user], 200);
                }
            } else {
                return response()->json(['error' => 'Issue in updating User Profile Status.'], 404);
            }
        } catch (\Throwable $th) {
            Log::error($th);
            return $th;
        }
    }
}
