<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['id','question','type','description','data','poll_id'];

    public function poll(){
        return $this->belongsTo(Poll::class);
    }
}
