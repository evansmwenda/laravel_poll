<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Poll extends Model
{
    use HasFactory,HasSlug;

    public $fillable = ['user_id','title','status','slug','description','expiry_date'];

    public function getSlugOptions() :SlugOptions  {
        return SlugOptions::create()
        ->generateSlugsFrom('title')
        ->saveSlugsTo('slug');
    }
}
