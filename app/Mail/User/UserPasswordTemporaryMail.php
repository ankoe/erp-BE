<?php

namespace App\Mail\Auth;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserPasswordTemporaryMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, String $password)
    {
        $this->user     = $user->fresh();
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from('noreply@erp.com')
            ->view('mail.user.password_temporary')
            ->subject('Temporary Password login erp.com')
            ->with([
                'name'          => $this->user->name,
                'email'         => $this->user->email,
                'company'       => $this->user->company->name,
                'password'      => $this->password,
                'createdAt'     => $this->user->created_at,
            ]);
    }
}
