<?php

declare(strict_types=1);

namespace Answear\PayPo\ValueObject;

use Answear\PayPo\Enum\ShipmentEnum;

class Order
{
    public string $referenceId;
    public ?string $providerId = null;
    public int $amount;
    public ?string $description = null;
    public ?array $additionalInfo = null;
    public ?int $shipment = null;
    public Address $billingAddress;
    public Address $shippingAddress;

    public function __construct(
        string $referenceId,
        int $amount,
        ?string $description,
        Address $billingAddress,
        Address $shippingAddress
    ) {
        $this->referenceId = $referenceId;
        $this->amount = $amount;
        $this->description = $description;
        $this->billingAddress = $billingAddress;
        $this->shippingAddress = $shippingAddress;
    }

    public function setShipment(ShipmentEnum $shipment): void
    {
        $this->shipment = $shipment->getValue();
    }
}
