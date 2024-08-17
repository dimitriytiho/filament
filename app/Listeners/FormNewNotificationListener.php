<?php

namespace App\Listeners;

use App\Events\FormNewEvent;
use App\Models\Form;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\EmailSender;

class FormNewNotificationListener
{
    public Form $form;
    public User $user;

    /**
     * Handle the event.
     * @throws \Throwable
     */
    public function handle(FormNewEvent $event): void
    {
        $this->form = $event->form;
        $this->user = $this->form->user;

        // Отправка Emails
        $this->sendEmail();
    }

    /**
     * @return void
     * @throws \Throwable
     */
    protected function sendEmail(): void
    {
        // Данные для отправки писем
        $site = config('app.name');
        $name = $this->user->name;
        $email = $this->user->email;
        $phone = $this->user->phone;
        $id = $this->form->id;
        $message = $this->form->message;
        $userMessage = view('sender.email.contact_us.user')->render();
        $managerMessage = view('sender.email.contact_us.manager', compact('site', 'name', 'email', 'phone', 'id', 'message'))->render();
        $userEmailData['subject'] = __('sender.you_submitted_form') . $site;
        $managerEmailData['subject'] = __('sender.form_sent') . $site;
        $managerEmails = explode(',', getenv('MAIL_MANAGERS'));

        // Отправка письма пользователю
        /*EmailSender::dispatch($userMessage, $email, $userEmailData);
        // Отправка письма менеджеру
        if (app()->environment('production')) {
            EmailSender::dispatch($managerMessage, $managerEmails, $managerEmailData);
        }*/
    }
}
