<?php

declare(strict_types=1);

namespace Answear\PayPo\ValueObject;

class Customer
{
    public string $name;
    public string $surname;
    public string $email;
    public string $phone;

    public function __construct(string $name, string $surname, string $email, string $phone)
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->phone = $phone;
    }
}
