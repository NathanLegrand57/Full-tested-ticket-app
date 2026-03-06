<?php

namespace Tests\Feature;

use App\Models\UserFavorite;
use App\Models\Show;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserFavoriteFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user can favorite a show.
     */
    public function test_user_can_favorite_show(): void
    {
        $user = User::factory()->create();
        $show = Show::factory()->create();

        $favorite = UserFavorite::create([
            'user_id' => $user->id,
            'show_id' => $show->id,
        ]);

        $this->assertDatabaseHas('user_favorites', [
            'user_id' => $user->id,
            'show_id' => $show->id,
        ]);

        $this->assertInstanceOf(User::class, $favorite->user);
        $this->assertInstanceOf(Show::class, $favorite->show);
    }
}
