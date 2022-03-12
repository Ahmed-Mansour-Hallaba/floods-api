<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('floods', function (Blueprint $table) {
            $table->id();
            $table->double('lat')->nullable();
            $table->double('lng')->nullable();
            $table->boolean('is_active');
            $table->unsignedBigInteger('added_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('floods');
    }
};
