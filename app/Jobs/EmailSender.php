<?php

namespace App\Jobs;

use App\Services\Sender\SenderInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use App\Services\Sender\EmailSender as EmailSenderService;

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
        new EmailSenderService($this->message, $this->to, $this->params);
    }
}
