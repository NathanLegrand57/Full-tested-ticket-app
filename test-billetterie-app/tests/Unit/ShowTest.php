<?php

namespace Tests\Unit;

use App\Models\Show;
use Tests\TestCase;

class ShowTest extends TestCase
{
    /**
     * Test show fillable attributes and casts.
     */
    public function test_show_fillable_and_casts(): void
    {
        $show = new Show([
            'title' => 'Test Show',
            'description' => 'A test show',
            'show_date' => '2023-12-25 20:00:00',
            'price' => 50.00,
            'duration' => 120,
            'places_disponibles' => 100,
        ]);

        $this->assertEquals('Test Show', $show->title);
        $this->assertInstanceOf(\Carbon\Carbon::class, $show->show_date);
        $this->assertEquals(50.0, $show->price);
        $this->assertEquals(120, $show->duration);
        $this->assertEquals(100, $show->places_disponibles);
    }
}
