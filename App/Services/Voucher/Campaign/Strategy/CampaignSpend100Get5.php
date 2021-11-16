<?php


namespace App\Services\Voucher\Campaign\Strategy;


class CampaignSpend100Get5 implements CampaignInterface
{

    public function getId(): int
    {
        return 1;
    }

    public function getName(): string
    {
        return 'get a 5 for >100';
    }

}