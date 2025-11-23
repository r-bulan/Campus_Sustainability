<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInitiativesTable extends Migration
{
    public function up()
    {
        Schema::create('initiatives', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->integer('impact_score')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('initiatives');
    }
}
