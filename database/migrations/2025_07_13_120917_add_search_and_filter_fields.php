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
        // اضافه کردن فیلدهای جدید به جدول محصولات
        Schema::table('products', function (Blueprint $table) {
            $table->string('barcode')->nullable()->after('code');
            $table->decimal('cost_price', 10, 2)->default(0)->after('price');
            $table->integer('min_stock_level')->default(10)->after('stock_quantity');
            $table->string('unit')->default('عدد')->after('min_stock_level');
            $table->date('expiry_date')->nullable()->after('unit');
            $table->string('brand')->nullable()->after('expiry_date');
            $table->json('images')->nullable()->after('brand');
        });

        // اضافه کردن فیلدهای جدید به جدول مشتریان
        Schema::table('customers', function (Blueprint $table) {
            $table->string('national_code')->nullable()->after('phone');
            $table->date('birth_date')->nullable()->after('national_code');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('birth_date');
            $table->decimal('total_purchases', 12, 2)->default(0)->after('gender');
            $table->integer('total_orders')->default(0)->after('total_purchases');
        });

        // اضافه کردن فیلدهای جدید به جدول سفارشات
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->default('cash')->after('status');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('payment_method');
            $table->decimal('tax_amount', 10, 2)->default(0)->after('discount_amount');
            $table->decimal('shipping_cost', 10, 2)->default(0)->after('tax_amount');
            $table->string('shipping_address')->nullable()->after('shipping_cost');
            $table->date('delivery_date')->nullable()->after('shipping_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['barcode', 'cost_price', 'min_stock_level', 'unit', 'expiry_date', 'brand', 'images']);
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['national_code', 'birth_date', 'gender', 'total_purchases', 'total_orders']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'discount_amount', 'tax_amount', 'shipping_cost', 'shipping_address', 'delivery_date']);
        });
    }
};
