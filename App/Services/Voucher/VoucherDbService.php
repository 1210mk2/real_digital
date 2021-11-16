<?php

namespace App\Services\Voucher;

use App\System\Db\DbInterface;
use App\Services\Voucher\Campaign\Strategy\CampaignInterface;
use App\Services\Data\OrderInputData;

class VoucherDbService
{
    private $db;

    public function __construct(DbInterface $db)
    {
        $this->db = $db;
    }

    public function addApprovedOrder(OrderInputData $order, CampaignInterface $campaign)
    {

        $sql = "INSERT INTO voucher_approve (order_id, customer_id, campaign_id)
VALUES (:order_id, :customer_id, :campaign_id)

";
        $params = [
            ':order_id'             => $order->order_id,
            ':customer_id'          => $order->customer_id,
            ':campaign_id'          => $campaign->getId(),
        ];
        $this->db->execute($sql, $params);
    }

    public function selectExistingAprovementsCount(OrderInputData $order,  CampaignInterface $campaign): int
    {
        $sql = "SELECT COUNT(*) as cnt
FROM voucher_approve

WHERE order_id = :order_id
AND campaign_id = :campaign_id

";
        $params = [
            ':order_id'         => $order->order_id,
            ':campaign_id'      => $campaign->getId(),
        ];

        $result = $this->db->get($sql, $params);
        return $result[0]->cnt;
    }

}