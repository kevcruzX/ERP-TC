<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('sku');
            $table->float('sale_price',20)->default('0.0');
            $table->float('purchase_price',20)->default('0.0');
            $table->integer('quantity')->default('0');
            $table->integer('tax_id')->default('0');
            $table->integer('category_id')->default('0');
            $table->integer('unit_id')->default('0');
            $table->string('type');
            $table->text('description')->nullable();
            $table->integer('created_by')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_services');
    }
}
