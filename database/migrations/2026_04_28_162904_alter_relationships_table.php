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
        Schema::table('role_user', function (Blueprint $table) {
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('role_id')->references('id')->on('roles')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('permission_role', function (Blueprint $table) {
            $table->foreignId('role_id')->references('id')->on('roles')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('permission_id')->references('id')->on('permissions')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('post_id')->references('id')->on('posts')->onDelete('cascade')->onUpdate('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
