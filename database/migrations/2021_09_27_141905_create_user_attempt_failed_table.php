<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAttemptFailedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_attempt_failed', function (Blueprint $table) {
            $table->integer('attempt_failed_id', true);
            $table->integer('attempt_type')->nullable()->comment('\'1-Valid Login,2-Invalid Login\'');
            $table->double('user_id')->nullable();
            $table->integer('no_of_attempt')->nullable();
            $table->integer('is_blocked')->nullable();
            $table->dateTime('unblock_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_attempt_failed');
    }
}
