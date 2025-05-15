<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailQueue extends Model
{
    use HasFactory;

    public $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'to_email', 'cc_email', 'bcc_email', 'subject', 'message', 'module', 'mail_send', 'message_id', 'failure_reason', 'cron_email_response', 'webhook_response_data', 'created_at', 'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'message_id', 'failure_reason', 'cron_email_response', 'webhook_response_data', 'created_at', 'updated_at',
    ];
}
