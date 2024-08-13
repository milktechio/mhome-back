<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->text('content')->nullable();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->string('image_url')->nullable();
            $table->integer('sold')->default(0);
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->boolean('active')->default(0);
            $table->uuid('user_id')->index()->null();
            $table->softdeletes();
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
        Schema::dropIfExists('products');
    }
}
