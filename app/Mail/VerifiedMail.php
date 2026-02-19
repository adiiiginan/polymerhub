<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifiedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nama;
    public $email;
    public $perusahaan;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($nama, $email, $perusahaan)
    {
        $this->nama = $nama;
        $this->email = $email;
        $this->perusahaan = $perusahaan;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Akun Anda Telah Diverifikasi')
            ->view('Emails.VerifiedMail');
    }
}
