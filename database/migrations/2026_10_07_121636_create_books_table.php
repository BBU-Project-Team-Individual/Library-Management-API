<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tblBook', function (Blueprint $table) {
            $table->increments('BookID');
            $table->string('BookTitle', 255);
            $table->unsignedInteger('BookTypeID');
            $table->date('PublishDate')->nullable();
            $table->unsignedInteger('NumOfPages')->nullable();
            $table->unsignedInteger('NumOfCopies')->nullable();
            $table->string('Edition', 100)->nullable();
            $table->string('Publisher', 150)->nullable();
            $table->string('BookSource', 255)->nullable();
            $table->text('Remark')->nullable();
            $table->foreign('BookTypeID')->references('BookTypeID')->on('tblBookType')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblBook');
    }
};
