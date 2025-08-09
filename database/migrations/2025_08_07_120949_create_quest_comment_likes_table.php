<?php

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
        Schema::create('quest_comment_likes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quest_comment_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('quest_comment_id')->references('id')->on('quest_comments');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quest_comment_likes');
    }
};
