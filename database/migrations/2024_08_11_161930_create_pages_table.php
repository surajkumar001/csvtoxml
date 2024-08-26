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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->string('file_name');
            $table->text('file_path');
            $table->string('url');
            $table->text('header');
            $table->text('footer');
            $table->text('body');
            $table->text('body_2');
            $table->text('json_data');
            $table->text('xml_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
