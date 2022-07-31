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
            'jwt_tokens',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->text('unique_id');
                $table->foreignId('user_id')->nullable()->constrained()
                    ->nullOnDelete();
                $table->string('token_title');
                $table->json('restrictions')->nullable();
                $table->json('permissions')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamp('last_used_at')->nullable();
                $table->timestamp('refreshed_at')->nullable();
                $table->timestamps();
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
        Schema::dropIfExists('jwt_tokens');
    }
};
