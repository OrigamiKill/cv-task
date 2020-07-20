<?php

declare(strict_types=1);

namespace App\Services;

class ApiKeyGenerator
{
    private const API_KEY_LIFE_TIME_DATE_INTERVAL = 'P01D';

    public const API_KEY = 'api_key';
    public const EXPIRED_DATE = 'expired_date';

    public function generate(): array
    {
        return [
            self::API_KEY => $this->getApiKey(),
            self::EXPIRED_DATE => $this->getExpiredDate(),
        ];
    }

    private function getApiKey(): string
    {
        return password_hash($this->generateRandomString(), PASSWORD_DEFAULT);
    }

    private function generateRandomString(): string
    {
        $result = 'not_very_secure_string';

        try {
            $result = random_bytes(20);
        } catch (\Exception $e) {
            //Write some logs
        }

        return $result;
    }

    private function getExpiredDate(): \DateTime
    {
        $dateTime = new \DateTime();
        $interval = new \DateInterval(self::API_KEY_LIFE_TIME_DATE_INTERVAL);

        $dateTime->add($interval);

        return $dateTime;
    }
}