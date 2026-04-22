<?php

namespace Tests\Feature\Vibe\Stage04;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\Concerns\CreatesStage04Schema;
use Tests\TestCase;

class UserAccountTest extends TestCase
{
    use CreatesStage04Schema;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createStage04Schema();
    }

    public function test_overview_endpoint_describes_stage_scope(): void
    {
        $response = $this->getJson(route('vibe.stage04.account.overview'));

        $response
            ->assertOk()
            ->assertJsonPath('data.stage', 'Stage 04')
            ->assertJsonPath('data.scope.0', 'register');
    }

    public function test_register_creates_user_and_keeps_flow_ready_for_login_step(): void
    {
        $response = $this->postJson(route('vibe.stage04.account.register'), [
            'name' => 'Nguyen Van A',
            'email' => 'nguyenvana@example.com',
            'password' => 'secret123',
            'password_confirm' => 'secret123',
            'phone' => '0900000000',
        ]);

        $user = User::query()->where('email', 'nguyenvana@example.com')->first();

        $response
            ->assertCreated()
            ->assertJsonPath('data.next_action', 'login')
            ->assertJsonPath('data.user.email', 'nguyenvana@example.com');

        $this->assertNotNull($user);
        $this->assertGuest('web');
        $this->assertTrue(Hash::check('secret123', $user->password));
    }

    public function test_login_authenticates_active_user(): void
    {
        $user = $this->createStage04User();

        $response = $this->postJson(route('vibe.stage04.account.login'), [
            'email' => $user->email,
            'password' => 'secret123',
            'remember' => true,
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.user.email', $user->email);

        $this->assertAuthenticatedAs($user, 'web');
    }

    public function test_login_rejects_disabled_user(): void
    {
        $user = $this->createStage04User([
            'email' => 'disabled-user@example.com',
            'status' => false,
        ]);

        $response = $this->postJson(route('vibe.stage04.account.login'), [
            'email' => $user->email,
            'password' => 'secret123',
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);

        $this->assertGuest('web');
    }

    public function test_profile_requires_auth_and_can_be_updated(): void
    {
        $user = $this->createStage04User();

        $this->getJson(route('vibe.stage04.account.profile.show'))
            ->assertUnauthorized();

        $response = $this->actingAs($user, 'web')->putJson(route('vibe.stage04.account.profile.update'), [
            'name' => 'User Da Cap Nhat',
            'email' => $user->email,
            'phone' => '0988000000',
            'address' => 'Can Tho',
            'password' => 'newsecret123',
            'password_confirm' => 'newsecret123',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.user.name', 'User Da Cap Nhat')
            ->assertJsonPath('data.user.phone', '0988000000');

        $user->refresh();

        $this->assertSame('User Da Cap Nhat', $user->name);
        $this->assertSame('Can Tho', $user->address);
        $this->assertTrue(Hash::check('newsecret123', $user->password));
    }

    public function test_forgot_password_creates_reset_token_preview(): void
    {
        $user = $this->createStage04User();

        $response = $this->postJson(route('vibe.stage04.account.password.forgot'), [
            'email' => $user->email,
        ]);

        $resetRow = DB::table('password_resets')->where('email', $user->email)->first();

        $response
            ->assertOk()
            ->assertJsonPath('data.email', $user->email)
            ->assertJsonPath('data.delivery', 'demo-inline')
            ->assertJsonStructure(['data' => ['reset_payload' => ['email', 'token']]]);

        $this->assertNotNull($resetRow);
    }

    public function test_reset_password_updates_password_and_deletes_token(): void
    {
        $user = $this->createStage04User();

        DB::table('password_resets')->insert([
            'email' => $user->email,
            'token' => 'reset-token-123',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->postJson(route('vibe.stage04.account.password.reset'), [
            'email' => $user->email,
            'token' => 'reset-token-123',
            'password' => 'afterreset123',
            'password_confirm' => 'afterreset123',
        ]);

        $response->assertOk();

        $user->refresh();

        $this->assertTrue(Hash::check('afterreset123', $user->password));
        $this->assertSame(0, DB::table('password_resets')->where('email', $user->email)->count());
    }

    public function test_social_callback_creates_user_link_and_authenticates(): void
    {
        $response = $this->postJson(route('vibe.stage04.account.social.callback'), [
            'provider' => 'google',
            'provider_user_id' => 'google-user-001',
            'email' => 'social-user@example.com',
            'name' => 'Social User',
        ]);

        $user = User::query()->where('email', 'social-user@example.com')->first();

        $response
            ->assertOk()
            ->assertJsonPath('data.user_created', true)
            ->assertJsonPath('data.link_created', true)
            ->assertJsonPath('data.user.email', 'social-user@example.com');

        $this->assertNotNull($user);
        $this->assertAuthenticatedAs($user, 'web');
        $this->assertDatabaseHas('social_accounts', [
            'user_id' => $user->id,
            'provider' => 'google',
            'provider_user_id' => 'google-user-001',
        ]);
    }

    public function test_social_callback_links_existing_user_without_creating_duplicate_account(): void
    {
        $user = $this->createStage04User([
            'email' => 'existing-social@example.com',
        ]);

        $response = $this->postJson(route('vibe.stage04.account.social.callback'), [
            'provider' => 'facebook',
            'provider_user_id' => 'facebook-user-002',
            'email' => $user->email,
            'name' => 'Existing Social User',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.user_created', false)
            ->assertJsonPath('data.link_created', true)
            ->assertJsonPath('data.user.id', $user->id);

        $this->assertSame(1, User::query()->where('email', $user->email)->count());
        $this->assertDatabaseHas('social_accounts', [
            'user_id' => $user->id,
            'provider' => 'facebook',
            'provider_user_id' => 'facebook-user-002',
        ]);
    }
}
