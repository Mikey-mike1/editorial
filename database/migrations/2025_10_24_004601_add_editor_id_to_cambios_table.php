<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('cambios', function (Blueprint $table) {
            $table->foreignId('editor_id')->nullable()->constrained('editores')->after('estado');
        });
    }

    public function down(): void
    {
        Schema::table('cambios', function (Blueprint $table) {
            $table->dropForeign(['editor_id']);
            $table->dropColumn('editor_id');
        });
    }
};

