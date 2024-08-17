<?php

namespace App\Interfaces;

interface SenderInterface
{
    /**
     * @param string $message
     * @param string|array $to
     * @param array $params
     */
    public function __construct(string $message, string|array $to, array $params = []);
}
