<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class AdminForgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $user = null;

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $url = url('admin/password/reset/'.base64_encode($this->user->email));

        if($this->user->roleId == config('constant.hr_id'))
        {
            $url = url('hr/password/reset/'.base64_encode($this->user->email));
        }

        if($this->user->roleId == config('constant.ma_id'))
        {
            $url = url('marketing-admin/password/reset/'.base64_encode($this->user->email));
        }

        $customerName = $this->user->firstName.' '.$this->user->lastName;


        return $this->subject('Forgot Password')
            ->with(['customerName' => $customerName, 'url' => $url])
            ->view('mail.adminMailSend.reset_password_mail');
    }
}
