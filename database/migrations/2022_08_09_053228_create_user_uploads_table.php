<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('user_uploads', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->foreignId('user_id')
        ->constrained('users')
        ->cascadeOnDelete()
        ->cascadeOnUpdate();
      $table->foreignId('upload_id')
        ->constrained('unique_uploads')
        ->cascadeOnDelete()
        ->cascadeOnUpdate();
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
    Schema::dropIfExists('user_uploads');
  }
};
