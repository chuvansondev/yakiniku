<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('customers')) {
            Schema::create('customers', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('name')->nullable();
                $table->string('email')->nullable();
                $table->string('email_normalized')->nullable()->index();
                $table->string('phone', 30)->nullable();
                $table->string('phone_normalized', 30)->nullable()->index();
                $table->text('notes')->nullable();
                $table->string('source', 32)->default('booking');
                $table->timestamp('last_contact_at')->nullable()->index();
                $table->string('status', 20)->default('active');
                $table->timestamps();
            });
        }

        if (! Schema::hasColumn('bookings', 'user_id')) {
            Schema::table('bookings', function (Blueprint $table): void {
                $table->foreignId('user_id')->nullable()->after('restaurant_id')->constrained()->nullOnDelete();
            });
        }

        if (! Schema::hasColumn('bookings', 'customer_id')) {
            Schema::table('bookings', function (Blueprint $table): void {
                $table->foreignId('customer_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
                $table->index(['customer_id', 'booking_date']);
            });
        }

        if (! Schema::hasColumn('leads', 'customer_id')) {
            Schema::table('leads', function (Blueprint $table): void {
                $table->foreignId('customer_id')->nullable()->after('id')->constrained()->nullOnDelete();
                $table->index(['customer_id', 'created_at']);
            });
        }

        $events = DB::table('bookings')->select('id', 'user_id', 'customer_name as name', 'email', 'phone', 'created_at')->get()
            ->map(fn ($row) => (object) [...(array) $row, 'kind' => 'booking'])
            ->concat(DB::table('leads')->select('id', DB::raw('NULL as user_id'), 'name', 'email', 'phone', 'created_at')->get()
                ->map(fn ($row) => (object) [...(array) $row, 'kind' => 'lead']))
            ->sortBy('created_at')
            ->values();

        $identityMap = [];
        foreach ($events as $event) {
            $emailNormalized = filled($event->email) ? mb_strtolower(trim($event->email)) : null;
            $phoneNormalized = filled($event->phone) ? preg_replace('/\\D+/', '', $event->phone) : null;
            $phoneNormalized = $phoneNormalized !== '' ? $phoneNormalized : null;
            $customerId = ($emailNormalized ? ($identityMap['email:'.$emailNormalized] ?? null) : null)
                ?? ($phoneNormalized ? ($identityMap['phone:'.$phoneNormalized] ?? null) : null);

            if (! $customerId) {
                $userId = $event->user_id;
                if (! $userId && $emailNormalized) {
                    $userId = DB::table('users')->whereRaw('LOWER(email) = ?', [$emailNormalized])->value('id');
                }
                $customerId = DB::table('customers')->insertGetId([
                    'user_id' => $userId,
                    'name' => $event->name,
                    'email' => filled($event->email) ? trim($event->email) : null,
                    'email_normalized' => $emailNormalized,
                    'phone' => filled($event->phone) ? trim($event->phone) : null,
                    'phone_normalized' => $phoneNormalized,
                    'source' => $event->kind,
                    'last_contact_at' => $event->created_at,
                    'status' => 'active',
                    'created_at' => $event->created_at,
                    'updated_at' => $event->created_at,
                ]);
            } else {
                $customer = DB::table('customers')->where('id', $customerId)->first();
                DB::table('customers')->where('id', $customerId)->update([
                    'user_id' => $customer->user_id ?: $event->user_id,
                    'name' => $event->name ?: $customer->name,
                    'email' => $event->email ?: $customer->email,
                    'email_normalized' => $emailNormalized ?: $customer->email_normalized,
                    'phone' => $event->phone ?: $customer->phone,
                    'phone_normalized' => $phoneNormalized ?: $customer->phone_normalized,
                    'last_contact_at' => $event->created_at ?: $customer->last_contact_at,
                    'updated_at' => $event->created_at ?: now(),
                ]);
            }

            if ($emailNormalized) $identityMap['email:'.$emailNormalized] = $customerId;
            if ($phoneNormalized) $identityMap['phone:'.$phoneNormalized] = $customerId;

            DB::table($event->kind === 'booking' ? 'bookings' : 'leads')
                ->where('id', $event->id)
                ->update(['customer_id' => $customerId]);
        }

        $permissionId = DB::table('permissions')->where('slug', 'menu.customers.manage')->value('id');
        if (! $permissionId) {
            $permissionId = DB::table('permissions')->insertGetId([
                'name' => 'menu.customers.manage',
                'slug' => 'menu.customers.manage',
                'description' => 'Manage customer profiles and interaction history.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $adminRoleId = DB::table('roles')->where('slug', 'admin')->value('id');
        if ($adminRoleId && ! DB::table('permission_role')->where('permission_id', $permissionId)->where('role_id', $adminRoleId)->exists()) {
            DB::table('permission_role')->insert([
                'permission_id' => $permissionId,
                'role_id' => $adminRoleId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $permissionId = DB::table('permissions')->where('slug', 'menu.customers.manage')->value('id');
        if ($permissionId) {
            DB::table('permission_role')->where('permission_id', $permissionId)->delete();
            DB::table('permissions')->where('id', $permissionId)->delete();
        }

        Schema::table('leads', function (Blueprint $table): void {
            $table->dropIndex(['customer_id', 'created_at']);
            $table->dropConstrainedForeignId('customer_id');
        });

        Schema::table('bookings', function (Blueprint $table): void {
            $table->dropIndex(['customer_id', 'booking_date']);
            $table->dropConstrainedForeignId('customer_id');
        });

        Schema::dropIfExists('customers');
    }
};
