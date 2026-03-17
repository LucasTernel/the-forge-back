<?php

namespace Tests\Feature;

use App\Models\Like;
use App\Models\Sword;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_like_a_sword()
    {
        $user = User::factory()->create();
        $sword = Sword::create([
            'name' => 'Excalibur',
            'description' => 'The sword of King Arthur',
        ]);

        $response = $this->actingAs($user)->postJson("/api/swords/{$sword->id}/like");

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Sword liked',
                     'liked' => true,
                 ]);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'sword_id' => $sword->id,
        ]);
    }

    public function test_user_can_unlike_a_sword()
    {
        $user = User::factory()->create();
        $sword = Sword::create([
            'name' => 'Excalibur',
            'description' => 'The sword of King Arthur',
        ]);

        Like::create([
            'user_id' => $user->id,
            'sword_id' => $sword->id,
        ]);

        $response = $this->actingAs($user)->postJson("/api/swords/{$sword->id}/like");

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Sword unliked',
                     'liked' => false,
                 ]);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'sword_id' => $sword->id,
        ]);
    }

    public function test_unauthenticated_user_cannot_like_a_sword()
    {
        $sword = Sword::create([
            'name' => 'Excalibur',
            'description' => 'The sword of King Arthur',
        ]);

        $response = $this->postJson("/api/swords/{$sword->id}/like");

        $response->assertStatus(401);
    }

    public function test_cannot_like_non_existent_sword()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson("/api/swords/999/like");

        $response->assertStatus(404);
    }
}
