<?php

use App\User;
use App\Models\UserCategory;

function getCommission($orderTotal, $percentageValue)
{
    $getPercentageValue = ($percentageValue / 100) * $orderTotal;
    return $getPercentageValue;
}

function getDistributor()
{
    $getDistributorDetails = [];
    $users =  User::select('id', 'first_name', 'last_name')
        ->cursor();

    foreach ($users as $value) {
        # code...
        $getDistributorDetails[] = [
            'user_id' => $value->id,
            'name' => $value->first_name . ' ' . $value->last_name,
        ];
    }
    return $getDistributorDetails;
}
