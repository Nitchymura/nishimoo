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
        Schema::create('quest_bodies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('day_number');
            $table->unsignedBigInteger('quest_id');
            $table->unsignedBigInteger('spot_id')->nullable();
            $table->unsignedBigInteger('business_id')->nullable();
            $table->string('business_title', 255)->nullable();
            $table->longText('introduction');
            $table->boolean('is_agenda')->default(true);
            $table->longText('image');
            $table->timestamps();
            $table->softDeletes();
            
            // 🔗 外部キー制約（詳細付き）
            $table->foreign('quest_id')
                ->references('id')->on('quests')
                ->onDelete('cascade');
                
            $table->foreign('spot_id')->references('id')->on('spots');
            $table->foreign('business_id')->references('id')->on('businesses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quest_bodies');
    }
};
