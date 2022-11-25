<?php

function getCommission($orderTotal, $percentageValue)
{
    $getPercentageValue = ($percentageValue / 100) * $orderTotal;
    //$CommissionValue =  $orderTotal + $getPercentageValue;
    return $getPercentageValue;
}
