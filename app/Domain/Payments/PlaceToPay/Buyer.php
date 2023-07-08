<?php

namespace App\Domain\Payments\PlaceToPay;

use App\Domain\Users\Models\User;

class Buyer
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Returns the buyer data for the placetopay request
     *
     * @return array
     */
    public function getBuyer(): array
    {
        return [
            'name' => $this->user->customer->name,
            'surname' => $this->user->customer->surname,
            'email' => $this->user->email,
            'document' => $this->user->customer->document,
            'documentType' => $this->user->customer->document_type,
            'mobile' => '+57'.$this->user->customer->phone,
            'address' => $this->getAddress(),
        ];
    }

    /**
     * Returns the address array
     *
     * @return array
     */
    private function getAddress(): array
    {
        return [
            'street' => $this->user->customer->address,
            'city' => $this->user->customer->city->name,
            'country' => $this->user->customer->city->department->name,
        ];
    }
}
