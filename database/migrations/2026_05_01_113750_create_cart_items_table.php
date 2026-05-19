<?php

use App\Models\Api\Cart\Cart;
use App\Models\Api\Product\Product;
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
        Schema::create('cart_items', function (Blueprint $table) {
            // Identity
            $table->id();
            $table->foreignIdFor(Cart::class);
            $table->foreignIdFor(Product::class);

            $table->integer('quantity');

            // Pricing snapshot
            $table->float('price', 3);
            $table->float('total_price', 3);

            $table->string('product_name');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
