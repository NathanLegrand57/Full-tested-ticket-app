<?php

namespace Tests\Feature;

use App\Models\Show;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test show can be created and relationships work.
     */
    public function test_show_creation_and_relationships(): void
    {
        $user = User::factory()->create();
        $show = Show::factory()->create();

        // Test reservations relationship
        $reservation = Reservation::create([
            'show_id' => $show->id,
            'user_id' => $user->id,
            'quantity' => 2,
            'amount' => 100.00,
            'status' => 'confirmed',
        ]);

        $this->assertDatabaseHas('reservations', [
            'show_id' => $show->id,
            'user_id' => $user->id,
            'quantity' => 2,
        ]);

        $show->refresh();
        $this->assertInstanceOf(Reservation::class, $show->reservations->first());
    }
}
