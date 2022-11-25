<?php

namespace App;

use App\Models\Category;
use App\Models\Order;
use App\Models\UserCategory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function referredDistributors($user_id, $order_date)
    {
        # code...
        return $this->where('referred_by', $user_id)->whereDate('enrolled_date', '<=', $order_date)->count();
    }

    public static function getReferralName($order_id)
    {
        # code...
        $purchaser = Order::where('id', $order_id)->first()->purchaser_id;
        $referral = self::where('id', $purchaser)->first()->referred_by;

        $getUserCategoryName = UserCategory::getUserCategory($referral);

        if ($getUserCategoryName === 'Distributor') {
            # code...

            $distributorName = self::where('id', $referral)->first();

            if (empty($distributorName)) {
                # code...
                return null;
            } else {
                $distributorFullName = $distributorName->first_name . ' ' . $distributorName->last_name;
            }

            return $distributorFullName;
        }
        return null;
    }

    public static function getNumberOfDistributor($order_id)
    {
        # code...
        $count = 0;
        $getOrder = Order::where('id', $order_id)->first();
        $getPurchaserId = $getOrder->purchaser_id;
        $getPurchaser = self::where('id', $getPurchaserId)->first();
        $referralId = $getPurchaser->referred_by;

        $getreferralCategoryName = UserCategory::getUserCategory($referralId);

        if ($getreferralCategoryName === 'Distributor') {
            # code...
            $count = self::where('referred_by', '=', $referralId)
                ->where('enrolled_date', '<', $getOrder->order_date)
                ->count();
            return $count;
        }
        return $count;
    }

    public static function getcommissionPercentage($order_id, int $value)
    {
        $commission = 0;
        $getOrder = Order::where('id', $order_id)->first();
        $getPurchaserId = $getOrder->purchaser_id;
        $getreferralCategoryName = UserCategory::getUserCategory($getPurchaserId);

        if ($getreferralCategoryName === 'Customer') {
            if ((0 <= $value) && ($value <= 4)) {
                # code...
                return $commission = 5;
            } elseif ((5 <= $value) && ($value <= 10)) {
                # code...
                return $commission = 10;
            } elseif ((11 <= $value) && ($value <= 20)) {
                # code...
                return $commission = 15;
            } elseif ((21 <= $value) && ($value <= 30)) {
                # code...
                return $commission = 20;
            } elseif ((31 <= $value) || ($value > 31)) {
                # code...
                return $commission = 30;
            }
            return $commission;
        }
        return $commission;
    }
}
