<?php
require_once 'vendor/autoload.php';

use App\System\Db\Db;
use App\Services\Data\OrderInputData;

$json = '{
  "order_id": 2,
  "customer_id": 55,
  "total": 107.23
}';

$json_parsed = json_decode($json);

$orderInput = OrderInputData::fromArray([
    'order_id'      => $json_parsed->order_id,
    'customer_id'   => $json_parsed->customer_id,
    'total'         => $json_parsed->total,
]);
var_dump($orderInput);

$_db = new Db();

$sql = "SELECT *
FROM voucher_approve
LEFT JOIN brands b ON b.id = g.brand_id

WHERE order_id = :order_id
AND voucher_campaign = :voucher_campaign

";
$params = [
    ':order_id'             => $orderInput->order_id,
    ':voucher_campaign'     => 1,
];

$data = $_db->get($sql, $params);

var_dump($data);