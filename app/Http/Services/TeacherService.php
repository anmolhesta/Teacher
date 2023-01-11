<?php

namespace App\Http\Services;

use App\Http\Requests\TeacherStoreRequest;
use App\Models\User;
use App\Models\UserProfile;
use DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class TeacherService
{

    private $userProfile;
    private $user;
    private $request;

    public function __construct(UserProfile $userProfile, User $user, TeacherStoreRequest $request)
    {
        $this->userProfile = $userProfile;
        $this->user = $user;
        $this->request = $request;
    }

    public function teacherRegisterService()
    {
        try {
            $validated = $this->request->validated();
            $result = DB::transaction(function () {
                $user = User::create([
                    'name' => $this->request->name,
                    'email' => $this->request->email,
                    'address' => $this->request->address,
                    'password' => Hash::make($this->request->password),
                    'ref_status_id' => 2,
                ]);

                if ($this->request->file('profile_picture')) {
                    $fileName = time() . '_' . $this->request->file('profile_picture')->getClientOriginalName();
                    $filePath = $this->request->file('profile_picture')->storeAs('uploads/student/avatar', $fileName, 'public');
                    $fileName = time() . '_' . $this->request->file('profile_picture')->getClientOriginalName();
                }

                $profile =  UserProfile::create([
                    'user_id' => $user->id,
                    'profile_picture' => $fileName,
                    'current_school_name' => $this->request->current_school_name,
                    'parent_details' => $this->request->parent_details,
                    'previous_school_name' => $this->request->previous_school_name,
                    'ref_status_id' => 1,
                ]);

                $roleAssign =  $user->assignRole('Teacher');

                return compact('user', 'profile');
            });
            if (!empty($result['user'])) {
                $token = $result['user']->createToken('LaravelAuthApp')->accessToken;

                return response()->json(['token' => $token], 200);
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
