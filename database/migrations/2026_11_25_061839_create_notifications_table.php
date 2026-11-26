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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');  //通知を受け取る人(投稿の持ち主)
            $table->unsignedBigInteger('sender_id');    //アクションした人(いいね、コメントした人)
            $table->unsignedBigInteger('post_id');  //対象の投稿
            $table->string('type');  //like or coment
            $table->boolean('is_read')->default(false); //fales=未読, true=既読
            $table->timestamps();
        // 投稿者が元データを削除したら、通知も削除させる
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');     
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};



