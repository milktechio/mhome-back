<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->string('image_url')->nullable();
            $table->decimal('price', 14, 4);
            $table->string('currency');
            $table->integer('stock')->nullable();
            $table->uuid('created_by')->nullable();
            $table->uuid('product_id')->nullable();
            $table->boolean('active')->default(0);
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
        Schema::dropIfExists('variants');
    }
}
