<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class SendRegisterAdminPassword extends Mailable
{
    use Queueable, SerializesModels;
    protected $details;
    protected $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details, $password, $siteUrl)
    {
        $this->details = $details;
        $this->password = $password;
        $this->siteUrl = $siteUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $details = $this->details;
        $password = $this->password;
        $siteUrl = $this->siteUrl;

        return $this->view('mail.admin_sub_admin_send_password',['details' => $details, 'password' => $password, 'siteUrl' => $siteUrl])->subject('Find Your Access Details');
    }
}
