<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('type_id');
            $table->string('title')->nullable();
            $table->text('comment')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('like')->nullable()->default('0');
            $table->string('dislike')->nullable()->default('0');
            $table->bigInteger('parent')->nullable()->default('0');
            $table->enum('type',['product','post'])->nullable();
            $table->enum('status',['SEEN','UNSEEN','Waiting'])->nullable()->default('Waiting');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
