<?php

namespace Tests\Feature;

use App\Models\Show;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ShowControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the index page displays shows and supports search.
     */
    public function test_index_displays_shows(): void
    {
        $show1 = Show::factory()->create(['title' => 'Concert de Rock']);
        $show2 = Show::factory()->create(['title' => 'Pièce de Théâtre']);

        $response = $this->get(route('shows.index'));

        $response->assertStatus(200);
        $response->assertViewHas('shows');
        $response->assertSee('Concert de Rock');
        $response->assertSee('Pièce de Théâtre');
    }

    /**
     * Test the search functionality on the index page.
     */
    public function test_index_search_filter(): void
    {
        $show1 = Show::factory()->create(['title' => 'Concert de Rock']);
        $show2 = Show::factory()->create(['title' => 'Pièce de Théâtre']);

        $response = $this->get(route('shows.index', ['search' => 'Rock']));

        $response->assertStatus(200);
        $response->assertSee('Concert de Rock');
        $response->assertDontSee('Pièce de Théâtre');
    }

    /**
     * Test a user can view a single show.
     */
    public function test_show_displays_single_show(): void
    {
        $show = Show::factory()->create();

        $response = $this->get(route('shows.show', $show));

        $response->assertStatus(200);
        $response->assertViewHas('show');
        $response->assertSee($show->title);
    }

    /**
     * Test authorized users can create a show.
     */
    public function test_authorized_user_can_create_show(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create();
        $admin->allow('show-create');

        $image = UploadedFile::fake()->image(name: 'show.jpg');

        $response = $this->actingAs($admin)->post(route('shows.store'), [
            'title' => 'New Show',
            'description' => 'A great show',
            'show_date' => now()->addDays(10)->toDateTimeString(),
            'image' => $image,
            'duration' => 120,
            'price' => 50.00,
            'places_disponibles' => 100,
        ]);

        $response->assertRedirect(route('shows.index'));
        $this->assertDatabaseHas('shows', ['title' => 'New Show']);
    }

    /**
     * Test unauthorized user cannot access create form.
     */
    public function test_unauthorized_user_cannot_access_create_form(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('shows.create'));

        $response->assertStatus(401);
    }

    /**
     * Test authenticated user can update a show if authorized.
     */
    public function test_authorized_user_can_update_show(): void
    {
        $admin = User::factory()->create();
        $admin->allow('show-edit');

        $show = Show::factory()->create();

        $response = $this->actingAs($admin)->put(route('shows.update', $show), [
            'title' => 'Updated Title',
            'description' => 'Updated description',
            'show_date' => now()->addDays(5)->toDateTimeString(),
            'duration' => 90,
            'price' => 40.00,
        ]);

        $response->assertRedirect(route('shows.index'));
        $this->assertDatabaseHas('shows', ['id' => $show->id, 'title' => 'Updated Title']);
    }

    /**
     * Test unauthorized user cannot update a show.
     */
    public function test_unauthorized_user_cannot_update_show(): void
    {
        $user = User::factory()->create();
        $show = Show::factory()->create(['title' => 'Original Title']);

        $response = $this->actingAs($user)->put(route('shows.update', $show), [
            'title' => 'Attempted Title Update',
            'description' => 'Should not work',
            'show_date' => now()->addDays(5)->toDateTimeString(),
            'duration' => 90,
            'price' => 40.00,
        ]);

        $response->assertStatus(401);
        $this->assertDatabaseHas('shows', ['id' => $show->id, 'title' => 'Original Title']);
    }

    /**
     * Test authorized user can delete a show.
     */
    public function test_authorized_user_can_delete_show(): void
    {
        $admin = User::factory()->create();
        $admin->allow('show-delete');

        $show = Show::factory()->create();

        $response = $this->actingAs($admin)->delete(route('shows.destroy', $show));

        $response->assertRedirect(route('shows.index'));
        $this->assertSoftDeleted('shows', ['id' => $show->id]);
    }

    /**
     * Test unauthorized user cannot delete a show.
     */
    public function test_unauthorized_user_cannot_delete_show(): void
    {
        $user = User::factory()->create();
        $show = Show::factory()->create();

        $response = $this->actingAs($user)->delete(route('shows.destroy', $show));

        $response->assertStatus(401);
        $this->assertDatabaseHas('shows', ['id' => $show->id, 'deleted_at' => null]);
    }
}
