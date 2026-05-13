<?php

namespace App\Support;

use Illuminate\Support\Facades\Mail;

class SiteMail
{
    public static function notificationEmail(): string
    {
        return (string) config('site.notification_email', 'temirovaangelina00@gmail.com');
    }

    /**
     * @param  array<int, array{label: string, value: string}>  $rows
     */
    public static function sendHtml(string $subject, array $rows, ?string $replyTo = null, ?string $replyToName = null): void
    {
        $to = self::notificationEmail();
        if (! filter_var($to, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid site.notification_email in config');
        }

        Mail::send('emails.site-message', [
            'subject' => $subject,
            'fields' => $rows,
        ], function ($message) use ($subject, $to, $replyTo, $replyToName): void {
            $message->to($to)->subject($subject);
            if ($replyTo !== null && $replyTo !== '' && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
                $message->replyTo($replyTo, (string) ($replyToName ?? ''));
            }
        });
    }
}
