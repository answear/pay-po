<?php

declare(strict_types=1);

namespace Answear\PayPo\ValueObject;

class Address
{
    public string $street;
    public ?string $building;
    public ?string $flat;
    public string $zip;
    public string $city;
    public ?string $country;

    public function __construct(
        string $street,
        ?string $building,
        ?string $flat,
        string $zip,
        string $city,
        ?string $country = null
    ) {
        $this->street = $street;
        $this->building = $building;
        $this->flat = $flat;
        $this->zip = $zip;
        $this->city = $city;
        $this->country = $country;
    }
}
