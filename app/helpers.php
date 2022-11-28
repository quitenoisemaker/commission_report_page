<?php

use App\User;
use App\Models\Category;
use App\Models\UserCategory;

function getCommission($orderTotal, $percentageValue)
{
    $getPercentageValue = ($percentageValue / 100) * $orderTotal;
    return $getPercentageValue;
}

function getDistributor()
{
    $distributors = User::select('id', 'first_name', 'last_name')->whereHas('userCategory', function ($query) {
        $query->where('user_category.category_id', 1);
    })->cursor();

    return $distributors;
}
