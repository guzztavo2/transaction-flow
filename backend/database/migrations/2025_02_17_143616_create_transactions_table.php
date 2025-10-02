<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Transaction;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type');
            $table->decimal('amount', 8, 2);
            $table->tinyInteger('status')->default(Transaction::STATUS_PENDING);
            $table->timestamp('scheduled_at')->nullable(); 
            $table->timestamps();
            $table->index(['id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
