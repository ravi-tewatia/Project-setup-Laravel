<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailQueuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_queues', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('to_email', 2000)->nullable();
            $table->string('cc_email', 2000)->nullable();
            $table->string('bcc_email', 2000)->nullable();
            $table->string('subject', 500)->nullable();
            $table->text('message')->nullable();
            $table->string('module')->nullable();
            $table->boolean('mail_send')->nullable()->default(false)->index('mail_send')->comment('0:Not Send, 1:In Queue, 2:Sent, 3:Invalid email so skip, 4:Partial Sent, 5:Failed');
            $table->string('message_id')->nullable();
            $table->text('failure_reason')->nullable();
            $table->text('cron_email_response')->nullable();
            $table->text('webhook_response_data')->nullable();
            $table->timestamp('created_at')->useCurrent();
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
        Schema::dropIfExists('mail_queues');
    }
}
