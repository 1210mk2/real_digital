<?php

namespace App\Services\Data;

use App\Entity\Data\CommonData;

class OrderInputData extends CommonData
{
    public int      $order_id;
    public int      $customer_id;
    public float    $total;

    protected function __construct(object $obj)
    {
        $this->order_id    = $obj->order_id;
        $this->customer_id = $obj->customer_id;
        $this->total       = $obj->total;
    }


}