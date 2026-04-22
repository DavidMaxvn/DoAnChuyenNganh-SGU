<?php

namespace Tests\Concerns;

use App\Models\Admin;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

trait CreatesStage01Schema
{
    protected function createStage01Schema(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('products');
        Schema::dropIfExists('admins');
        Schema::enableForeignKeyConstraints();

        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable();
            $table->double('price')->default(0);
            $table->integer('quantity')->default(0);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('products');
            $table->timestamps();
        });
    }

    protected function createStage01Admin(array $overrides = []): Admin
    {
        return Admin::query()->create(array_merge([
            'name' => 'Stage 01 Admin',
            'email' => 'stage01-admin@example.com',
            'password' => Hash::make('secret123'),
        ], $overrides));
    }
}
