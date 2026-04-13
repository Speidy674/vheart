<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $isSqlite = DB::getDriverName() === 'sqlite';

        if (! $isSqlite) {
            DB::unprepared('CREATE SEQUENCE tags_order_seq');
        }

        Schema::table('tags', function (Blueprint $table) use ($isSqlite) {
            $default = $isSqlite
                ? DB::raw('(SELECT COALESCE(MAX(`order`), 0) + 1 FROM tags)')
                : DB::raw("nextval('tags_order_seq')");

            $table->unsignedBigInteger('order')->default($default);
            $table->index(['order', 'id']);
        });
    }
};
