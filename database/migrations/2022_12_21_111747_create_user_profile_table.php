<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('profile_picture',200)->nullable();
            $table->string('current_school_name',200)->nullable();;
            $table->string('previous_school_name',200)->nullable();;
            $table->string('parent_details',200)->nullable();;
            $table->string('experience',10)->nullable();;
            $table->string('expertise_subjects',500)->nullable();;
            $table->integer('approved_by')->nullable();
            $table->boolean('ref_status_id')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
}
