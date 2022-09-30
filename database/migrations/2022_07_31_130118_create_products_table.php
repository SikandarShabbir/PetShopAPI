<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'products',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->double('price', 12, 2);
                $table->double('cost', 12, 2);
                $table->text('description')->nullable();
                $table->tinyInteger('units_and_info')->nullable();
                $table->string('unit')->nullable();
                $table->string('weight_per_unit')->nullable();
                $table->json('image_urls');
                $table->timestamps();
                $table->softDeletes();
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
        Schema::dropIfExists('products');
    }
};
