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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(1)->comment("1=Active,0=Inactive");
            $table->boolean('is_deleted')->default(0)->comment("1=Active,0=Inactive");
            $table->string('name');
            $table->string('brand_name')->nullable();
            $table->string('slug')->nullable();
            $table->string('sku')->nullable();
            $table->string('unit')->nullable();
//            $table->integer('qty')->nullable();
//            $table->integer('sold_qty')->nullable();
//            $table->decimal('sell_price', 20, 2);
            $table->text('image')->nullable();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->integer("created_by")->nullable();
            $table->integer("updated_by")->nullable();
            $table->timestamps();
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
