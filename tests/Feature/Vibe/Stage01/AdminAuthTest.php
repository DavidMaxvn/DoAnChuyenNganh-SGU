<?php

namespace Tests\Feature\Vibe\Stage01;

use Tests\Concerns\CreatesStage01Schema;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use CreatesStage01Schema;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createStage01Schema();
    }

    public function test_admin_login_requires_email_and_password(): void
    {
        $response = $this->postJson(route('vibe.stage01.admin.login'), []);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_admin_can_log_in_with_valid_credentials(): void
    {
        $admin = $this->createStage01Admin();

        $response = $this->postJson(route('vibe.stage01.admin.login'), [
            'email' => $admin->email,
            'password' => 'secret123',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.email', $admin->email);

        $this->assertAuthenticatedAs($admin, 'admin');
    }

    public function test_invalid_admin_credentials_are_rejected(): void
    {
        $admin = $this->createStage01Admin();

        $response = $this->postJson(route('vibe.stage01.admin.login'), [
            'email' => $admin->email,
            'password' => 'wrong-password',
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonPath('message', 'Email hoac mat khau khong dung.');
    }
}
