<?php

namespace Database\Seeders;

use App\Models\Show;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ShowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the directory if it doesn't exist
        if (!Storage::disk('public')->exists('shows')) {
            Storage::disk('public')->makeDirectory('shows');
        }

        // Define the shows with their images
        $shows = [
            [
                'title' => 'Cyrano de Bergerac',
                'description' => 'Le chef-d\'œuvre d\'Edmond Rostand dans une production spectaculaire. L\'histoire d\'amour et de courage qui a marqué des générations. Un spectacle épique avec des dialogues mémorables et une mise en scène grandiose.',
                'show_date' => Carbon::now()->addDays(15)->setTime(20, 0),
                'duration' => 200,
                'price' => 40.00,
                'image' => 'shows/cyrano-de-bergerac.jpg',
                'places_disponibles' => 100,
            ],
            [
                'title' => 'Hamlet',
                'description' => 'La tragédie intemporelle de Shakespeare. Une interprétation moderne et captivante du prince de Danemark. Un questionnement profond sur la vengeance, la folie et la moralité.',
                'show_date' => Carbon::now()->addDays(20)->setTime(19, 30),
                'duration' => 180,
                'price' => 35.00,
                'image' => 'shows/hamlet.jpg',
                'places_disponibles' => 80,
            ],
            [
                'title' => 'Roméo et Juliette',
                'description' => 'La plus grande histoire d\'amour jamais contée. La tragédie d\'amour de Shakespeare dans une adaptation moderne et poétique. Un spectacle envoûtant sur l\'amour impossible.',
                'show_date' => Carbon::now()->addDays(25)->setTime(20, 30),
                'duration' => 160,
                'price' => 38.00,
                'image' => 'shows/romeo-et-juliette.jpg',
                'places_disponibles' => 90,
            ],
            [
                'title' => 'Antigone',
                'description' => 'Tragédie grecque de Sophocle dans une mise en scène épurée et moderne. Un questionnement sur la loi, la justice et la conscience individuelle. Un spectacle puissant et intemporel.',
                'show_date' => Carbon::now()->addDays(30)->setTime(20, 0),
                'duration' => 120,
                'price' => 32.00,
                'image' => 'shows/antigone.jpg',
                'places_disponibles' => 75,
            ],
            [
                'title' => 'Le Misanthrope',
                'description' => 'Comédie de Molière mise en scène avec une approche contemporaine. Une réflexion sur l\'hypocrisie sociale et les relations humaines. Un spectacle drôle et intelligent.',
                'show_date' => Carbon::now()->addDays(35)->setTime(19, 30),
                'duration' => 150,
                'price' => 28.00,
                'image' => 'shows/la-misanthrope.jpg',
                'places_disponibles' => 85,
            ],
            [
                'title' => 'L\'Avare',
                'description' => 'Comédie de Molière sur l\'avarice et ses conséquences. Un spectacle drôle et poignant qui résonne encore aujourd\'hui. Une mise en scène moderne d\'un classique intemporel.',
                'show_date' => Carbon::now()->addDays(40)->setTime(19, 0),
                'duration' => 90,
                'price' => 25.00,
                'image' => 'shows/l-avare.jpg',
                'places_disponibles' => 95,
            ],
        ];

        foreach ($shows as $showData) {
            // Check if the show already exists (by title)
            $show = Show::where('title', $showData['title'])->first();

            if ($show) {
                // Update the existing show
                $show->update($showData);
                $this->command->info("Spectacle mis à jour : {$showData['title']}");
            } else {
                // Create a new show
                Show::create($showData);
                $this->command->info("Spectacle créé : {$showData['title']}");
            }
        }

        $this->command->info('');
        $this->command->info('🎭 ' . count($shows) . ' spectacles traités avec succès !');
        $this->command->info('');
        $this->command->warn('Assurez-vous que les fichiers images sont présents dans : storage/app/public/shows/');
    }
}
