<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_type_id');
            $table->unsignedBigInteger('loan_name_id');

            // Personal Details
            $table->string('name');
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('nid_number');
            $table->date('date_of_birth');
            $table->string('gender');
            $table->string('marital_status');
            $table->text('permanent_address');
            $table->text('present_address');

            // Loan Requested Amount
            $table->decimal('loan_amount', 15, 2);
            $table->integer('loan_duration'); // in months
$table->string('status')->default('pending');
            // Optional Documents
            $table->string('document1')->nullable();
            $table->string('document2')->nullable();
            $table->string('document3')->nullable();
            $table->string('document4')->nullable();
            $table->string('document5')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applies');
    }
};
