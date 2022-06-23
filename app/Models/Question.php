<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function subCategory(){
        return $this->belongsTo(Category::class,'topic_categori_id','id')->withDefault(['name'=>'-']);
    }

    public function questionOptions(){
        return $this->hasMany(Option::class);
    }
    
}
