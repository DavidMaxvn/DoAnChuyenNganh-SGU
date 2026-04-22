<?php

namespace Tests\Concerns;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

trait CreatesStage04Schema
{
    protected function createStage04Schema(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('social_accounts');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('users');
        Schema::enableForeignKeyConstraints();

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('status')->default(1);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->string('token');
            $table->timestamps();
        });

        Schema::create('social_accounts', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('provider_user_id');
            $table->string('provider');
            $table->timestamps();
        });
    }

    protected function createStage04User(array $overrides = []): User
    {
        return User::query()->create(array_merge([
            'name' => 'Stage 04 User',
            'email' => 'stage04-user@example.com',
            'password' => Hash::make('secret123'),
            'phone' => '0123456789',
            'address' => 'Ho Chi Minh City',
            'status' => true,
        ], $overrides));
    }
}
