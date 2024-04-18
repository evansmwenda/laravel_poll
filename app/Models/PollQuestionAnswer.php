<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollQuestionAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['poll_question_id', 'poll_answer_id', 'answer'];
}
