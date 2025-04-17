<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignUuid('account_source_id')->after('id')->references('id')->on('accounts');
            $table->foreignUuid('account_destination_id')->after('account_source_id')->references('id')->on('accounts');
        });
        Schema::table('transaction_logs', function (Blueprint $table) {
            $table->foreign('transaction_id')->references('id')->on('transactions');
        });
        Schema::table('fraud_analisys', function (Blueprint $table) {
            $table->foreign('transaction_id')->references('id')->on('transactions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['account_source_id']);
            $table->dropForeign(['account_destination_id']);
        });
        Schema::table('transaction_logs', function (Blueprint $table) {
            $table->dropForeign(['transaction_id']);
        });
        Schema::table('fraud_analisys', function (Blueprint $table) {
            $table->dropForeign(['transaction_id']);
        });
    }
};
