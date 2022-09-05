<?php

namespace App\Mail;

use App\Models\User;
use App\Helpers\CommonHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class SendRegisterUserPassword extends Mailable
{
    use Queueable, SerializesModels;
    protected $details;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $details = $this->details;

      
        $randomPassword = CommonHelper::randomPassword();

        User::where('id',$details->id)->update(['password'=> bcrypt($randomPassword)]);

        // if(Hash::check($randomPassword, $details->password) == true){
            // $password = $randomPassword;
        // }else{
        //     $password = '-';
        // }

        return $this->view('mail.admin_approved_send_pass_to_user',['details' => $details, 'password' => $randomPassword]);
    }
}
