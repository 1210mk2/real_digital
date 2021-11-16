<?php

namespace App\Services\Voucher\Campaign;

use App\Services\Data\OrderInputData;
use App\Services\Voucher\Campaign\Strategy\CampaignInterface;
use App\Services\Voucher\Campaign\Strategy\CampaignSpend100Get5;
use App\Services\Voucher\Campaign\Strategy\CampaignSpend300Get20;

class StrategySelector
{

    public static function detectStrategy(OrderInputData $order): ?CampaignInterface
    {

        if ($order->total >= 300) {
            return new CampaignSpend300Get20();
        }

        if ($order->total >= 100) {
            return new CampaignSpend100Get5();
        }

        return null;
    }
}