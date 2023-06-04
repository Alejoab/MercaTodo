<?php

namespace App\Services\Payment\PlaceToPay;

use App\Models\User;

class Buyer
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getBuyer(): array
    {
        return [
            'name' => $this->user->customer->name,
            'surname' => $this->user->customer->surname,
            'email' => $this->user->email,
            'document' => $this->user->customer->document,
            'documentType' => $this->user->customer->document_type,
            'mobile' => '+57'.$this->user->customer->phone,
        ];
    }
}
