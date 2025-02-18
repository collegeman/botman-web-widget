<?php

use Collegeman\BotManWebWidget\BotManWebWidget;
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
        Schema::create(BotManWebWidget::config('database.conversations_table_name'), function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->softDeletes();
            $table->string('user_id');
            $table->string('name');
        });

        Schema::create(BotManWebWidget::config('database.messages_table_name'), function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->enum('role', ['user', 'system', 'assistant']);
            $table->softDeletes();
            $table->foreignUuid('conversation_id')->constrained(BotManWebWidget::config('database.conversations_table_name'))->cascadeOnDelete();
            $table->string('type');
            $table->string('text');
            $table->index(['conversation_id', 'created_at', 'role']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(BotManWebWidget::config('database.conversations_table_name'));
        Schema::dropIfExists(BotManWebWidget::config('database.messages_table_name'));
    }
};
