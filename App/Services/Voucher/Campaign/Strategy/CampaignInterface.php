<?php


namespace App\Services\Voucher\Campaign\Strategy;


interface CampaignInterface
{
    public function getId(): int;
    public function getName(): string;

}