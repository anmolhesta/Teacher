<?php

namespace App\Http\Services;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use DB;

class  CommonService
{
    private $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function teacherProfile(User $authuser)
    {
        return $authuser->load('profile');
    }

    public function teacherUpdateProfile(User $authuser)
    {
        try {
            //  $validated = $this->request->validated();
            $result = DB::transaction(function () use ($authuser) {
                $user = $authuser->update([
                    'name' => $this->request->name,
                    'address' => $this->request->address,
                    'password' => Hash::make($this->request->password),
                    'ref_status_id' => 2,
                ]);

                if ($this->request->file('profile_picture') && !empty($this->request->file('profile_picture'))) {
                    $fileName = time() . '_' . $this->request->file('profile_picture')->getClientOriginalName();
                    $filePath = $this->request->file('profile_picture')->storeAs('uploads/student/avatar', $fileName, 'public');
                    $fileName = time() . '_' . $this->request->file('profile_picture')->getClientOriginalName();
                }

                $profile =  UserProfile::where('user_id', $authuser->id)->update([
                    'profile_picture' => (!empty($fileName)) ?? $fileName,
                    'current_school_name' => $this->request->current_school_name,
                    'parent_details' => $this->request->parent_details,
                    'previous_school_name' => $this->request->previous_school_name,
                    'ref_status_id' => 1,
                ]);

                return compact('authuser', 'profile');
            });
            if (!empty($result['profile'])) {
                return response()->json(['user_details' => $result['authuser']], 200);
            } else {
                return response()->json(['error' => 'Something went wrong.'], 500);
            }
        } catch (\Throwable $th) {
            Log::error($th);
            //return response()->json(['error' => $th->getMessage()], 500);
        } finally {
            if (!empty($th)) {
                if ($th instanceof QueryException || $th instanceof \Throwable)
                    return response()->json(['error' => 'Something went wrong.'], 500);
            }
        }
    }
}
