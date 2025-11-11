// database/migrations/xxxx_xx_xx_xxxxxx_add_kode_petugas_to_users_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('users', 'kode_petugas')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('kode_petugas')->nullable()->after('email');
            });
        }
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('kode_petugas');
        });
    }
};