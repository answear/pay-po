<?php

declare(strict_types=1);

namespace Answear\PayPo\Enum;

enum ShipmentEnum: int
{
    case Courier = 0;
    case PickupPoint = 1;
    case ParcelLocker = 2;
    case RuchPack = 3;
    case ClickCollect = 4;
}
