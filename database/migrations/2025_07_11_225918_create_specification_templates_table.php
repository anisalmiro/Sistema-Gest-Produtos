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
        Schema::create('specification_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('field_name');
            $table->string('field_label');
            $table->enum('field_type', ['text', 'number', 'select', 'textarea', 'boolean']);
            $table->json('field_options')->nullable(); // Para campos select
            $table->string('unit')->nullable(); // Para campos numéricos (ex: cm, kg, W)
            $table->boolean('required')->default(false);
            $table->integer('order')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->unique(['category_id', 'field_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specification_templates');
    }
};
