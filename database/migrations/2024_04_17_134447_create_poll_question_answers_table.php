<?php

use App\Models\PollAnswer;
use App\Models\PollQuestion;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('poll_question_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PollQuestion::class,'poll_question_id')->constrained()->onDelete('cascade');
            $table->foreignIdFor(PollAnswer::class,'poll_answer_id')->constrained()->onDelete('cascade');
            $table->text('answer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poll_question_answers');
    }
};
