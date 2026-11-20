<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];
    # To get how many post used the categories (ADMIN SIDE)
    public function categoryPost(){
        return $this->hasMany(CategoryPost::class);
    }
}
