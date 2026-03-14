<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audits', function (Blueprint $table): void {
            $table->id();
            $table->nullableMorphs('causer');
            $table->nullableMorphs('auditable');
            $table->string('event');
            $table->jsonb('old')->nullable();
            $table->jsonb('new')->nullable();
            $table->jsonb('tags')->nullable();
            $table->string('request_id')->nullable()->index();

            // if set it will be removed after X days via Scheduler and it should also be stored anonymized by default.
            $table->ipAddress()->nullable();
            $table->string('user_agent', 1023)->nullable();

            $table->timestamps();
        });
    }
};
