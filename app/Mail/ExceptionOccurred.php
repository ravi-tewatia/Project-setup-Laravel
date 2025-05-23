<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Throwable;

class ExceptionOccurred extends Mailable
{
    use Queueable, SerializesModels;

    public $exception;

    public function __construct(Throwable $exception)
    {
        $this->exception = $exception;
    }

    public function build()
    {
        return $this->view('emails.exception')
                    ->subject('Exception Occurred');
    }
} 