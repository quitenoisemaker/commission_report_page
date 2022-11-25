<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserCategory extends Model
{
    //
    protected $table = "user_category";

    public function user()
    {
        # code...
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        # code...
        return $this->belongsTo(Category::class, 'category_id');
    }

    public static function getUserCategory($user_id)
    {
        # code...
        $userCategoryName = self::where('user_id', $user_id)->first()->category->name;
        return $userCategoryName;
    }
}
