<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('print_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('quotation_id')->nullable()->constrained()->onDelete('set null');
            $table->string('assigned_branch')->nullable();
            $table->date('estimated_completion')->nullable();
            $table->string('status')->default('submitted');
            // submitted | quotation | payment | branch_recommended | production | completed | ready_for_pickup | claimed
            $table->string('payment_status')->default('pending'); // pending | submitted | confirmed | rejected
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
