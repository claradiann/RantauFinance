<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordBase;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPasswordBase
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = $this->resetUrl($notifiable);

        return (new MailMessage)
            ->subject('Atur Ulang Kata Sandi Akun Rantau Finance Anda')
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Kami menerima permintaan untuk mengatur ulang kata sandi akun Rantau Finance Anda.')
            ->line('Silakan klik tombol di bawah ini untuk melanjutkan proses pengaturan ulang kata sandi:')
            ->action('Atur Ulang Kata Sandi', $url)
            ->line('Tautan ini hanya berlaku selama ' . config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60) . ' menit.')
            ->line('Jika Anda tidak merasa melakukan permintaan ini, abaikan saja email ini. Keamanan akun Anda tetap terjaga.')
            ->salutation("Salam hangat,\nTim Rantau Finance");
    }
}
