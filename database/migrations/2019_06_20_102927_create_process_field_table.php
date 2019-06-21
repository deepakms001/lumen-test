<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcessFieldTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('process_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('field_id')->references('id')->on('fields')->onDelete('cascade');
            $table->integer('tractor_id')->references('id')->on('travtors')->onDelete('cascade');
            $table->date('date');
            $table->double('area');
            $table->enum('status', ['Pending', 'Processed'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('process_field');
    }

}
