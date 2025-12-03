<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Salle;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Créer un admin
        $admin = User::create([
            'name' => 'Administrateur',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'telephone' => '0123456789',
            'role' => User::ROLE_ADMIN,
            'actif' => true,
        ]);

        // Créer un gestionnaire
        $gestionnaire = User::create([
            'name' => 'Jean Gestionnaire',
            'email' => 'gestionnaire@example.com',
            'password' => Hash::make('password'),
            'telephone' => '0234567890',
            'role' => User::ROLE_GESTIONNAIRE,
            'actif' => true,
        ]);

        // Créer des utilisateurs
        $users = [];
        $users[] = User::create([
            'name' => 'Marie Dupont',
            'email' => 'marie@example.com',
            'password' => Hash::make('password'),
            'telephone' => '0345678901',
            'role' => User::ROLE_UTILISATEUR,
            'actif' => true,
        ]);

        $users[] = User::create([
            'name' => 'Pierre Martin',
            'email' => 'pierre@example.com',
            'password' => Hash::make('password'),
            'telephone' => '0456789012',
            'role' => User::ROLE_UTILISATEUR,
            'actif' => true,
        ]);

        $users[] = User::create([
            'name' => 'Sophie Bernard',
            'email' => 'sophie@example.com',
            'password' => Hash::make('password'),
            'telephone' => '0567890123',
            'role' => User::ROLE_UTILISATEUR,
            'actif' => true,
        ]);

        // Créer des salles
        $salles = [];
        $salles[] = Salle::create([
            'nom' => 'Salle de Conférence A',
            'capacite' => 50,
            'equipements' => ['Vidéoprojecteur', 'Écran', 'WiFi', 'Climatisation', 'Microphone'],
            'description' => 'Grande salle de conférence équipée pour les présentations et séminaires.',
            'tarif_heure' => 75.00,
            'disponible' => true,
        ]);

        $salles[] = Salle::create([
            'nom' => 'Salle de Réunion B',
            'capacite' => 15,
            'equipements' => ['Écran TV', 'Tableau blanc', 'WiFi', 'Visioconférence'],
            'description' => 'Salle de réunion moderne pour petits groupes.',
            'tarif_heure' => 35.00,
            'disponible' => true,
        ]);

        $salles[] = Salle::create([
            'nom' => 'Salle Formation C',
            'capacite' => 25,
            'equipements' => ['Vidéoprojecteur', 'Ordinateurs', 'WiFi', 'Tableau interactif'],
            'description' => 'Salle équipée pour les formations avec postes informatiques.',
            'tarif_heure' => 55.00,
            'disponible' => true,
        ]);

        $salles[] = Salle::create([
            'nom' => 'Espace Créatif D',
            'capacite' => 20,
            'equipements' => ['Mobilier modulable', 'WiFi', 'Paperboard', 'Post-it'],
            'description' => 'Espace flexible idéal pour les brainstormings et ateliers créatifs.',
            'tarif_heure' => 45.00,
            'disponible' => true,
        ]);

        $salles[] = Salle::create([
            'nom' => 'Auditorium E',
            'capacite' => 100,
            'equipements' => ['Vidéoprojecteur HD', 'Système audio', 'Microphones', 'Scène', 'Climatisation'],
            'description' => 'Grand auditorium pour événements et conférences importantes.',
            'tarif_heure' => 150.00,
            'disponible' => true,
        ]);

        // Créer des réservations
        $objets = [
            'Réunion d\'équipe',
            'Formation interne',
            'Présentation client',
            'Workshop innovation',
            'Séminaire annuel',
            'Entretiens recrutement',
            'Conférence presse',
            'Team building',
        ];

        $statuts = [
            Reservation::STATUT_EN_ATTENTE,
            Reservation::STATUT_CONFIRMEE,
            Reservation::STATUT_CONFIRMEE,
            Reservation::STATUT_TERMINEE,
        ];

        // Réservations passées
        for ($i = 0; $i < 10; $i++) {
            $salle = $salles[array_rand($salles)];
            $user = $users[array_rand($users)];
            $dateDebut = now()->subDays(rand(1, 30))->setHour(rand(8, 16))->setMinute(0)->setSecond(0);
            $duree = rand(1, 4);
            $dateFin = (clone $dateDebut)->addHours($duree);

            Reservation::create([
                'salle_id' => $salle->id,
                'user_id' => $user->id,
                'date_debut' => $dateDebut,
                'date_fin' => $dateFin,
                'objet' => $objets[array_rand($objets)],
                'nombre_participants' => rand(5, $salle->capacite),
                'statut' => Reservation::STATUT_TERMINEE,
                'montant_total' => $duree * $salle->tarif_heure,
            ]);
        }

        // Réservations futures
        for ($i = 0; $i < 15; $i++) {
            $salle = $salles[array_rand($salles)];
            $user = $users[array_rand($users)];
            $dateDebut = now()->addDays(rand(1, 30))->setHour(rand(8, 16))->setMinute(0)->setSecond(0);
            $duree = rand(1, 4);
            $dateFin = (clone $dateDebut)->addHours($duree);

            Reservation::create([
                'salle_id' => $salle->id,
                'user_id' => $user->id,
                'date_debut' => $dateDebut,
                'date_fin' => $dateFin,
                'objet' => $objets[array_rand($objets)],
                'nombre_participants' => rand(5, $salle->capacite),
                'statut' => $statuts[array_rand($statuts)],
                'montant_total' => $duree * $salle->tarif_heure,
            ]);
        }
    }
}
