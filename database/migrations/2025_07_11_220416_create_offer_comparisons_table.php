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
        Schema::create('offer_comparisons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->timestamp('comparison_date');
            $table->foreignId('selected_offer_id')->nullable()->constrained('offers')->onDelete('set null');
            $table->text('criteria_notes')->nullable();
            $table->json('comparison_criteria')->nullable()->comment('Stores criteria weights and scores');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offer_comparisons');
    }
};
