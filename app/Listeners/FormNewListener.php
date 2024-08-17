<?php

namespace App\Listeners;

use App\Events\FormNewEvent;
use App\Models\Form;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\Sender\EmailSender;

class FormNewListener
{
    public Form $form;
    public User $user;

    /**
     * Слушатель события новой формы.
     *
     * Handle the event.
     * @throws \Throwable
     */
    public function handle(FormNewEvent $event): void
    {
        // Form model
        $this->form = $event->form;
        // User model
        $this->user = $this->form->user;

        // Отправка письма менеджеру
        EmailSender::formNewManager($this->form, $this->user);
        // Отправка письма
        EmailSender::formNewUser($this->form, $this->user);
    }
}
