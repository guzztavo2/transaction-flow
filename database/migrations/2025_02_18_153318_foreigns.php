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
        Schema::table('accounts', function (Blueprint $table) {
            $table->foreign('user_id')->references("id")->on("users");
        });
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign("account_source_id")->references("id")->on("accounts");
            $table->foreign("account_destination_id")->references("id")->on("accounts");
        });
        Schema::table('transaction_logs', function (Blueprint $table) {
            $table->foreign("transaction_id")->references("id")->on("transactions");
        });
        Schema::table('fraud_analisys', function (Blueprint $table) {
            $table->foreign("transaction_id")->references("id")->on("transactions");
        });
        Schema::table('deposits', function (Blueprint $table) {
            $table->foreign("account_id")->references("id")->on("accounts");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropForeign('user_id')->references("id")->on("users");
        });
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign("account_source")->references("id")->on("accounts");
            $table->dropForeign("account_destination")->references("id")->on("accounts");
        });
        Schema::table('transaction_logs', function (Blueprint $table) {
            $table->dropForeign("transaction_id")->references("id")->on("transactions");
        });
        Schema::table('fraud_analisys', function (Blueprint $table) {
            $table->dropForeign("transaction_id")->references("id")->on("transactios");
        });
        Schema::table('deposits', function (Blueprint $table) {
            $table->dropForeign("account_id")->references("id")->on("accounts");
        });
    }
};
