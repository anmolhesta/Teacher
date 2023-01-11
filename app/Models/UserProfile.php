<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'profile_picture',
        'current_school_name',
        'parent_details',
        'previous_school_name',
        'user_id',
        'experience',
        'expertise_subjects',
        'approved_by',
        'ref_status_id',
        'verified_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
