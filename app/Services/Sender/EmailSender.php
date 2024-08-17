<?php

namespace App\Services\Sender;

use Illuminate\Support\Facades\Mail;
use App\Models\{Form, User};
use App\Jobs\EmailSender as EmailSenderJob;

readonly class EmailSender implements SenderInterface
{
    /**
     * @param string $message
     * @param string|array $to
     * @param array $params - в массиве передать subject
     */
    public function __construct(protected string $message, protected string|array $to, protected array $params = [])
    {
        $this->send();
    }

    /**
     * Отправка письма.
     *
     * @return void
     */
    public function send(): void
    {
        $message = $this->message;
        $to = $this->to;
        $params = $this->params;
        Mail::send([], [], function ($mail) use ($message, $to, $params) {
            $subject = $params['subject'] ?? 'No subject';
            $mail
                ->to($to)
                ->subject($subject)
                ->text($message);
        });
    }

    /**
     * Отправка письма менеджеру, только в production.
     *
     * @param Form $form
     * @param User $user
     * @param bool $queue
     * @return void
     * @throws \Throwable
     */
    public static function formNewManager(Form $form, User $user, bool $queue = true): void
    {
        $site = config('app.name');
        $message = view('sender.email.form_new.manager', [
            'site' => $site,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'id' => $form->id,
            'message' => $form->message,
        ])->render();
        $params['subject'] = __('sender.form_sent') . $site;
        $emails = explode(',', getenv('MAIL_MANAGERS'));
        if (app()->environment('production')) {
            if ($queue) {
                EmailSenderJob::dispatch($message, $emails, $params);
            } else {
                new self($message, $emails, $params);
            }
        }
    }

    /**
     * Отправка письма пользователю.
     *
     * @param Form $form
     * @param User $user
     * @param bool $queue
     * @return void
     * @throws \Throwable
     */
    public static function formNewUser(Form $form, User $user, bool $queue = true): void
    {
        $message = view('sender.email.form_new.user')->render();
        $params['subject'] = __('sender.you_submitted_form') . config('app.name');
        if ($queue) {
            EmailSenderJob::dispatch($message, $user->email, $params);
        } else {
            new self($message, $user->email, $params);
        }
    }
}
