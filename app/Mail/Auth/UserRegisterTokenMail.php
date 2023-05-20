<?php

namespace App\Mail\Auth;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRegisterTokenMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user->fresh();
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
            ->view('mail.auth.activate.token')
            ->subject('Konfirmasi akun ERP')
            ->with([
                'name'          => $this->user->name,
                'limit'         => config('variable.limit.token'),
                'confirmLink'   => config('variable.domain.main')."/auth/activation/confirmation?token={$this->user->email_proof_token}",
            ]);
    }
}
