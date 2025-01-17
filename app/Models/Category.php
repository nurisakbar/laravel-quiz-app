<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['slug','categori_id','name'];

    public function categoryQuestions()
    {
        return $this->hasMany(Question::class);
    }
}
