<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Show;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user can see their reservation history.
     */
    public function test_index_shows_user_reservations(): void
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('reservations.history'));

        $response->assertOk();
        $response->assertViewHas('reservations');
        $response->assertSee($reservation->show->title);
    }

    /**
     * Test user can see specific reservation details.
     */
    public function test_show_displays_reservation_details(): void
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('reservations.show', $reservation));

        $response->assertOk();
        $response->assertViewHas('reservation');
        $this->assertEquals($reservation->id, $response->viewData('reservation')->id);
    }

    /**
     * Test user cannot see someone else's reservation.
     */
    public function test_user_cannot_view_others_reservation(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $reservation = Reservation::factory()->create(['user_id' => $user1->id]);

        $response = $this->actingAs($user2)->get(route('reservations.show', $reservation));

        $response->assertForbidden();
    }

    /**
     * Test user can cancel their reservation.
     */
    public function test_destroy_cancels_reservation(): void
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete(route('reservations.destroy', $reservation));

        $response->assertRedirect(route('reservations.history'));
        $this->assertSoftDeleted('reservations', ['id' => $reservation->id]);
    }

    /**
     * Test unauthorized user cannot cancel someone else's reservation.
     */
    public function test_user_cannot_cancel_others_reservation(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $reservation = Reservation::factory()->create(['user_id' => $user1->id]);

        $response = $this->actingAs($user2)->delete(route('reservations.destroy', $reservation));

        $response->assertForbidden();
        $this->assertDatabaseHas('reservations', ['id' => $reservation->id, 'deleted_at' => null]);
    }
}
