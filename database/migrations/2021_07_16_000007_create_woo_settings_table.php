<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWooSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'woo_settings',
            function (Blueprint $table) {
                $table->bigInteger('id')->unsigned()->autoIncrement();
                $table->bigInteger('organization_id')->nullable();
                $table->string('name', 255)->nullable();
                $table->string('slug', 255)->nullable();
                $table->text('value')->comment('');
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->timestamp('deleted_at')->nullable();
                $table->integer('creator_id')->nullable()->default(0);
                $table->integer('editor_id')->nullable()->default(0);
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
        Schema::dropIfExists('woo_settings');
    }
}
