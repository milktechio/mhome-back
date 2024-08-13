<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('product_id');
            $table->uuid('user_id');
            $table->uuid('purchased_by');
            $table->uuid('variant_id')->nullable();
            $table->uuid('transaction_id')->nullable();
            $table->decimal('price', 14, 4);
            $table->string('currency');
            $table->string('currency_price')->nullable();
            $table->integer('sold')->default(1);
            $table->decimal('commission',14,4)->nullable();
            $table->decimal('total', 10, 4); //precio * sold
            $table->boolean('charged')->default(false);
            $table->enum('status', ['Pendiente', 'Pagado', 'Cancelado', 'Rembolsado', 'Completado']);
            $table->enum('buyer_status', ['Completado', 'Pagado', 'Cancelado', 'Rembolsado', 'Pendiente']);
            $table->enum('payment', ['Blockchain', 'Paypal', 'Stripe']);
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
        Schema::dropIfExists('purchases');
    }
}
