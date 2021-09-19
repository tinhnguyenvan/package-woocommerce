<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'woo_product_meta',
            function (Blueprint $table) {
                $table->bigInteger('id')->unsigned()->autoIncrement();
                $table->bigInteger('product_id')->nullable();
                $table->string('meta_key', 255)->nullable();
                $table->text('meta_value')->comment('');

                $table->index('product_id');
                $table->index('meta_key');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('woo_product_meta');
    }
}
