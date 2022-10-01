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
                $table->boolean('unitsAndInfo')->default(false);
                $table->string('unit')->nullable();
                $table->string('weightPerUnit')->nullable();
                $table->json('imageUrls');
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
