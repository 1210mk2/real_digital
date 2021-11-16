<?php
require_once 'vendor/autoload.php';

use App\System\Db\Db;
use App\Services\Data\OrderInputData;
use App\Services\Voucher\Campaign\StrategySelector;
use App\Services\Voucher\VoucherDbService;

$json = '{
  "order_id": 5,
  "customer_id": 55,
  "total": 117.23
}';

$json_parsed = json_decode($json);

$orderInput = OrderInputData::fromArray([

    'order_id'      => $json_parsed->order_id,
    'customer_id'   => $json_parsed->customer_id,
    'total'         => $json_parsed->total,
]);


$campaign = StrategySelector::detectStrategy($orderInput);

if (!$campaign) { //not applicable
    echo 0;
    die();
}

$_db            = new Db();
$voucherService = new VoucherDbService($_db);
$voucherService->addApprovedOrder($orderInput, $campaign);

$applicationsCount = $voucherService->selectExistingAprovementsCount($orderInput, $campaign);

if ($applicationsCount == 1) {
    echo $campaign->getId();
    die();
}

if ($applicationsCount == 0) {
    throw new Exception("Something wrong with Db insert");
}

echo 0;