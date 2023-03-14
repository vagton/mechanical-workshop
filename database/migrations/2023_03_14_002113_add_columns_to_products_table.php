<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('qty_stock')->nullable();
            $table->unsignedInteger('min_amount')->nullable();
            $table->decimal('providers_price', 8, 2)->nullable();
            $table->string('area', 191)->nullable();
            $table->string('col', 191)->nullable();
            $table->string('lin', 191)->nullable();
        });
    }

}
