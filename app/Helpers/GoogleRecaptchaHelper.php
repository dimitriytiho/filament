<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class GoogleRecaptchaHelper
{
    /**
     * @param string|null $recaptchaInput
     * @param string|null $ip
     * @return bool
     * @throws GuzzleException
     */
    public static function check(?string $recaptchaInput, ?string $ip): bool
    {
        $api = 'https://www.google.com/recaptcha/api/siteverify';
        $res = null;
        if ($recaptchaInput && $ip) {
            try {
                $params = [
                    'query' => [
                        'secret' => getenv('RECAPTCHA_SECRET_KEY'),
                        'response' => $recaptchaInput,
                        'remoteip' => $ip,
                    ],
                ];
                $client = new Client([
                    'verify' => false,
                    'connect_timeout' => 2,
                    'timeout' => 2,
                ]);
                $response = $client->post($api, $params);
                if ($response->getStatusCode() === 200) {
                    $data = json_decode($response->getBody()->getContents(), true);
                    $res = $data['success'] ?? null;
                }
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }
        }
        return (bool) $res;
    }
}
