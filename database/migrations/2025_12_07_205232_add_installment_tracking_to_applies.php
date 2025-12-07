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
    Schema::table('applies', function (Blueprint $table) {
        $table->integer('paid_installments')->default(0); // how many installments already paid
        $table->decimal('paid_amount', 15, 2)->default(0); // total paid BDT
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applies', function (Blueprint $table) {
            //
        });
    }
};
