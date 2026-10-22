<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\ForeignKeyDefinition;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
    $table->id();

    $table->string('title')->nullable();

    $table->enum('type', [
        'image',
        'video'
    ])->default('image');

    $table->string('image')->nullable();

    $table->string('video_url')->nullable();

    $table->string('link')->nullable();

    $table->integer('sort_order')->default(0);

    $table->boolean('status')->default(true);

    $table->timestamps();
    $table->foreignId('delete_at')->nullable()->constrained('users')->nullOnDelete();
    $table->foreignId('delete_by')->nullable()->constrained('users')->nullOnDelete();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
