<?php


namespace App\Services\Voucher\Campaign\Strategy;


class CampaignSpend300Get20 implements CampaignInterface
{

    public function getId(): int
    {
        return 2;
    }

    public function getName(): string
    {
        return 'get a 20 for >300';
    }

}