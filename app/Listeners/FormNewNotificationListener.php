<?php

namespace App\Listeners;

use App\Events\FormNewEvent;
use App\Models\Form;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\Sender\EmailSender;

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

        // Отправка письма менеджеру
        EmailSender::formNewManager($this->form, $this->user);
        // Отправка письма
        EmailSender::formNewUser($this->form, $this->user);
    }
}
