<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('payment_method_id')->nullable()->after('customer_id')->constrained('payment_methods')->onDelete('cascade');
            $table->decimal('amount', 10, 2)->nullable()->after('payment_method_id');
            $table->string('status')->default('pending')->after('amount');
        });
    }

    public function down() {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method_id', 'amount', 'status']);
        });
    }
};
