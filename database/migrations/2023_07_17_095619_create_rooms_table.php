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
        Schema::create('rooms', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->smallIncrements('id');
            $table->tinyInteger('number');
            $table->tinyInteger('floor');
            $table->decimal('price',12,2);
            $table->string('remarks',250)->nullable();
            $table->boolean('is_available')->default(1);
            $table->boolean('is_active')->default(1);
            $table->smallInteger('room_type')->unsigned()->index();
            $table->foreign('room_type')->references('id')->on('dropdowns')->onDelete('cascade');
            $table->smallInteger('bed_type')->unsigned()->index();
            $table->foreign('bed_type')->references('id')->on('dropdowns')->onDelete('cascade');
            $table->smallInteger('building_id')->unsigned()->index();
            $table->foreign('building_id')->references('id')->on('buildings')->onDelete('cascade');
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
        Schema::dropIfExists('rooms');
    }
};
