<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryPost extends Model
{
    public $timestamps = false;  // turn off timestamps

    protected $table = 'category_post';  // inform laravel that the table name is SINGULAR

    protected $fillable = ['category_id', 'Post_id'];
    // fillable means "fill able" or can fill up these columns for MASS ASSIGNMENT (since we are saving the data using ARRAY)

    # To get the name of the category
    public function category(){
        return $this->belongsTo(Category::class);
    }
}
