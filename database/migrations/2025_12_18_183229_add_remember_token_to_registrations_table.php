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
    Schema::table('registrations', function (Blueprint $table) {
        if (!Schema::hasColumn('registrations', 'remember_token')) {
            $table->rememberToken();
        }
    });
}

public function down()
{
    Schema::table('registrations', function (Blueprint $table) {
        $table->dropColumn('remember_token');
    });
}

};
