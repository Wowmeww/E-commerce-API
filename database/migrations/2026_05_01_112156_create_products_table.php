<?php

use App\Models\Api\Product\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {

            // Core identity
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();

            // Pricing
            $table->float('price', 3);
            $table->float('sale_price', 3)->nullable();
            $table->float('cost_price', 3)->nullable();

            // Inventory
            $table->integer('stock_quantity');
            $table->enum(
                'stock_status',
                ['in_stock', 'out_of_stock', 'preorder']
            )->default('in_stock');

            // Relationships
            $table->foreignIdFor(Category::class);

            // Status & visibility
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);

            $table->timestamps();

            // Media
            // thumbnail (main image)
            // images (JSON or separate table if advanced)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
