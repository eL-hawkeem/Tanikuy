<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'kasir'])->default('kasir')->after('email');
            $table->index('role');
        });

        // Set existing users (especially default admin) as admin
        DB::table('users')->where('email', 'admin@tanikuy.com')->update(['role' => 'admin']);
        // If there are other existing users without role set, keep first user as admin
        $firstUser = DB::table('users')->orderBy('id')->first();
        if ($firstUser) {
            DB::table('users')->where('id', $firstUser->id)->update(['role' => 'admin']);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropColumn('role');
        });
    }
};
