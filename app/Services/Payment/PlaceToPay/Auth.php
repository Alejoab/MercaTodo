<?php

namespace App\Services\Payment\PlaceToPay;


use Illuminate\Support\Str;

use function date;

class Auth
{
    private string $nonce;
    private string $seed;

    public function __construct()
    {
        $this->nonce = Str::random();
        $this->seed = date('c');
    }

    /**
     * Return the auth array for the placetopay request
     *
     * @return array
     */
    public function getAuth(): array
    {
        return [
            'login' => $this->getLogin(),
            'tranKey' => $this->getTranKey(),
            'nonce' => base64_encode($this->nonce),
            'seed' => $this->seed,
        ];
    }

    /**
     * Return the login key
     *
     * @return string
     */
    private function getLogin(): string
    {
        return config('placetopay.login');
    }

    /**
     * Return the tranKey
     *
     * @return string
     */
    private function getTranKey(): string
    {
        return base64_encode(hash('sha256', $this->nonce.$this->seed.config('placetopay.tranKey'), true));
    }

}
