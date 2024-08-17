<?php

namespace App\Services\Sender;

interface SenderInterface
{
    /**
     * @param string $message
     * @param string|array $to
     * @param array $params
     */
    public function __construct(string $message, string|array $to, array $params = []);
}
