<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('applies', function (Blueprint $table) {
        $table->unsignedBigInteger('user_id')->nullable()->after('id'); // nullable for old records
        $table->foreign('user_id')->references('id')->on('registrations')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('applies', function (Blueprint $table) {
        $table->dropForeign(['user_id']);
        $table->dropColumn('user_id');
    });
}

};
