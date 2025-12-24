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
        $table->string('nominee_name')->nullable();
        $table->string('nominee_relation')->nullable();
        $table->string('nominee_nid')->nullable();
        $table->date('nominee_dob')->nullable();
        $table->text('nominee_address')->nullable();
    });
}

public function down()
{
    Schema::table('applies', function (Blueprint $table) {
        $table->dropColumn(['nominee_name', 'nominee_relation', 'nominee_nid', 'nominee_dob', 'nominee_address']);
    });
}

};
