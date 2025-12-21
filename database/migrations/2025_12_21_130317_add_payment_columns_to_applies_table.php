<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applies', function (Blueprint $table) {
            // Mobile Banking (optional)
            $table->string('mobile_provider')->nullable()->after('loan_duration'); // bKash, Nagad, Rocket
            $table->string('mobile_number')->nullable()->after('mobile_provider');

            // Card Payment (mandatory)
            $table->string('card_type')->after('mobile_number'); // Debit / Credit
            $table->string('card_brand')->after('card_type'); // Visa, MasterCard etc
            $table->string('card_number')->after('card_brand');
            $table->string('card_holder')->after('card_number');
            $table->string('card_expiry')->after('card_holder'); // MM/YY
            $table->string('card_cvc')->after('card_expiry');
        });
    }

    public function down(): void
    {
        Schema::table('applies', function (Blueprint $table) {
            $table->dropColumn([
                'mobile_provider',
                'mobile_number',
                'card_type',
                'card_brand',
                'card_number',
                'card_holder',
                'card_expiry',
                'card_cvc',
            ]);
        });
    }
};
