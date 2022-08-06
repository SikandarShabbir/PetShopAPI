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
                $table->uuid('uuid');
                $table->foreignId('category_id')->nullable()->constrained()
                    ->nullOnDelete();
                $table->string('title');
                $table->double('price', 12, 2);
                $table->text('description');
                $table->json('metadata');
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
