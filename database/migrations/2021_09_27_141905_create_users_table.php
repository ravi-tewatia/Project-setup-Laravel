<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('user_id', true);
            $table->string('slug', 12)->nullable();
            $table->string('username')->nullable();
            $table->binary('full_name')->nullable();
            $table->binary('email')->nullable();
            $table->binary('phone')->nullable();
            $table->string('password')->nullable();
            $table->string('profile_thumb', 100)->nullable()->comment('Profile Thumb Image');
            $table->string('street_address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('state')->nullable();
            $table->string('rejection_note')->nullable();
            $table->text('custom_data')->nullable()->comment('Store Custom JSON');
            $table->smallInteger('status_id')->nullable()->default(5)->comment('1-Active,2-Inactive,3-Deleted,4-Pending Activation,5 Draft');
            $table->timestamp('activated_at')->nullable()->comment('User activated at');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
