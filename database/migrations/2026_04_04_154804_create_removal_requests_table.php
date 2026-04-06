<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('removal_requests', function (Blueprint $table): void {
            $table->id();

            $table->unsignedBigInteger('broadcaster_id');
            $table->foreignId('clip_id')->constrained()->restrictOnDelete();

            $table->unsignedInteger('status')->default(0);
            $table->text('details')->nullable();

            $table->timestamp('claimed_at')->nullable();
            $table->foreignId('claimed_by')->nullable()->constrained('users');

            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users');

            $table->timestamps();

            $table->index(['broadcaster_id', 'status']);
            $table->index('clip_id');
        });
    }
};
