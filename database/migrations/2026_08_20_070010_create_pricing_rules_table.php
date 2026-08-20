<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricing_rules', function (Blueprint $table) {
            $table->id();
            $table->string('service');          // matches print_requests.service
            $table->string('size')->nullable(); // A4 | A5 | Letter | etc.
            $table->decimal('base_rate', 10, 2)->default(0); // per copy
            $table->decimal('material_rate', 10, 2)->default(0);
            $table->decimal('finishing_rate', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_rules');
    }
};
