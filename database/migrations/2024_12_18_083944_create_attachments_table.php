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
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('upload_by')->unsigned();
            $table->string('path',300);
            $table->string('name',300);
            $table->string('type',100);
            $table->float('size',16,2);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('upload_by')->references('id')->on('users')->onDelete('cascade');
            $table->index('path');
            $table->index('name');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
