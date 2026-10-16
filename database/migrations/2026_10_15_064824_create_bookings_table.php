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
        Schema::create('bookings', function (Blueprint $table) {
    $table->id();

    $table->string('booking_code')->unique();

    $table->foreignId('restaurant_id')
          ->constrained('restaurants')
          ->restrictOnDelete();

    $table->string('customer_name');

    $table->string('phone');

    $table->string('email')->nullable();

    $table->date('booking_date');

    $table->time('booking_time');

    $table->unsignedInteger('number_of_guests');

    $table->text('note')->nullable();

    $table->enum('status', [
        'pending',
        'confirmed',
        'cancelled',
        'completed'
    ])->default('pending');

    $table->string('qr_code')->nullable();

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
        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table): void {
                if (Schema::hasColumn('bookings', 'update_by')) {
                    $table->dropConstrainedForeignId('update_by');
                }

                if (Schema::hasColumn('bookings', 'create_by')) {
                    $table->dropConstrainedForeignId('create_by');
                }
            });
        }

        Schema::dropIfExists('bookings');
    }
};
