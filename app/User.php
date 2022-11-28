<?php

namespace App;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
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

    public function userCategory()
    {
        return $this->hasOne(UserCategory::class, 'user_id');
    }

    public static function getReferralName($order_id)
    {

        $purchaser = Order::where('id', $order_id)->first()->purchaser_id;
        $referral = self::select('first_name', 'last_name', 'referred_by')->where('id', $purchaser)->first();

        $getUserCategoryName = UserCategory::getUserCategory($referral->referred_by);

        if ($getUserCategoryName === Category::categoryName(1)) {
            # if user is a distributor 

            $distributorName = $referral->referred_by;

            # check if user has a name
            if (empty($distributorName)) {

                return null;
            } else {
                $distributorFullName = $referral->first_name . ' ' . $referral->last_name;
            }

            return $distributorFullName;
        }
        return null;
    }

    public static function getNumberOfReferredDistributor($order_id)
    {
        $count = 0;
        $getOrder = Order::select('purchaser_id', 'order_date')->where('id', $order_id)->first();
        $getPurchaserId = $getOrder->purchaser_id;
        $getPurchaser = self::where('id', $getPurchaserId)->first();
        $referralId = $getPurchaser->referred_by;

        $getreferralCategoryName = UserCategory::getUserCategory($referralId);

        if ($getreferralCategoryName === Category::categoryName(1)) {
            # count number of referree  
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
                return $commission = 5;
            } elseif ((5 <= $value) && ($value <= 10)) {
                return $commission = 10;
            } elseif ((11 <= $value) && ($value <= 20)) {
                return $commission = 15;
            } elseif ((21 <= $value) && ($value <= 30)) {
                return $commission = 20;
            } elseif ((31 <= $value) || ($value > 31)) {
                return $commission = 30;
            }
            return $commission;
        }
        return $commission;
    }
}
