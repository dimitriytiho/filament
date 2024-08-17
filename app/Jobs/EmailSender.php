<?php

namespace App\Jobs;

use App\Interfaces\SenderInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Mail;

class EmailSender implements SenderInterface, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $message;
    protected string|array $to;
    protected array $params;

    /**
     * @param string $message
     * @param string|array $to
     * @param array $params - в массиве передать subject
     */
    public function __construct(string $message, string|array $to, array $params = [])
    {
        $this->message = $message;
        $this->to = $to;
        $this->params = $params;
    }

    public function handle(): void
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
}
