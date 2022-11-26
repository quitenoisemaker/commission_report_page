<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //

    //category name 
    public function scopeCategoryName($query, $id)
    {
        return $query->find($id)->name;
    }
}
