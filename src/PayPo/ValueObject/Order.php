<?php

declare(strict_types=1);

namespace Answear\PayPo\ValueObject;

use Answear\PayPo\Enum\ShipmentEnum;

class Order
{
    public ?string $providerId = null;
    public ?array $additionalInfo = null;
    public ?int $shipment = null;

    public function __construct(
        public string $referenceId,
        public int $amount,
        public Address $billingAddress,
        public Address $shippingAddress,
        public ?string $description = null,
    ) {
    }

    public function setShipment(ShipmentEnum $shipment): void
    {
        $this->shipment = $shipment->value;
    }
}
