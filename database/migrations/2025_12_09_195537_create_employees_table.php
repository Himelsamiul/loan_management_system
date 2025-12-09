<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id(); // primary key
            $table->string('id_card_number')->unique(); // manually input
            $table->string('name');
            $table->string('designation');
            $table->string('role');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->enum('status', ['active','deactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
