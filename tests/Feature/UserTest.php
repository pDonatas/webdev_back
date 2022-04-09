<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\MigrateFreshSeedOnce;
use Tests\TestCase;

class UserTest extends TestCase
{
    use MigrateFreshSeedOnce;

    public function setUp(): void
    {
        parent::setUp();
        $this->migrateFreshSeedOnce();
    }

    public function test_user_can_access_list()
    {
        $response = $this->get('/api/users');

        $response->assertStatus(200);
    }

    public function test_user_can_not_be_created_without_data() {
        $response = $this->postJson('/api/users');

        $response->assertStatus(422);
    }

    public function test_user_can_be_created_without_address()
    {
        $response = $this->postJson('/api/users', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@doe.com',
            'password' => 'secret'
        ]);

        $response->assertStatus(201);
    }

    public function test_user_can_be_created_with_address()
    {
        $response = $this->postJson('/api/users', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@doeman.com',
            'password' => 'secret',
            'address' => '123 Main St.'
        ]);

        $response->assertStatus(201);
    }

    public function test_user_can_not_be_created_with_same_email()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/users', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => $user->email,
            'password' => 'secret'
        ]);

        $response->assertStatus(422);
    }

    public function test_user_can_be_updated()
    {
        $user = User::factory()->create();

        $response = $this->putJson('/api/users/' . $user->id, [
            'first_name' => 'New '. $user->first_name,
            'last_name' => 'New '. $user->last_name,
            'email' => $user->email,
            'password' => 'new password'
        ]);

        $response->assertStatus(200);
    }

    public function test_user_can_be_deleted()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson('/api/users/' . $user->id);

        $response->assertStatus(204);
    }

    public function test_not_existing_user_can_not_be_deleted()
    {
        $response = $this->deleteJson('/api/users/15721');

        $response->assertStatus(404);
    }
}
