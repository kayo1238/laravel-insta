<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\ema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();  // 送った人(users table id)
            $table->foreignId('receiver_id')->constrained('users')->cascadeOnDelete();  //受け取る人(users table id)
            $table->text('body');   // massage
            $table->timestamp('read_at')->nullable();  // 未読用の既読日時(未読の間はnull)
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
