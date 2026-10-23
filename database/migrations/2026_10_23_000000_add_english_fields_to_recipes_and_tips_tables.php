<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['recipes', 'tips'] as $name) {
            Schema::table($name, function (Blueprint $table): void {
                $table->string('title_en')->nullable();
                $table->text('short_description_en')->nullable();
                $table->text('content_en')->nullable();
            });
        }
    }

    public function down(): void
    {
        foreach (['recipes', 'tips'] as $name) {
            Schema::table($name, function (Blueprint $table): void {
                $table->dropColumn(['title_en', 'short_description_en', 'content_en']);
            });
        }
    }
};
