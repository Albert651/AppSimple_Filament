# Système de Réservation de Salles

Application Laravel avec Filament pour la gestion des réservations de salles.

## Fonctionnalités

### Gestion des Salles
- Création, modification et suppression de salles
- Informations : nom, capacité, tarif horaire, équipements
- Photo de la salle
- Statut de disponibilité

### Gestion des Réservations
- Création de réservations avec calcul automatique du montant
- Gestion des statuts : En attente, Confirmée, Annulée, Terminée
- Actions rapides pour confirmer/annuler
- Filtres par salle, statut et période

### Gestion des Utilisateurs
- Trois rôles : Administrateur, Gestionnaire, Utilisateur
- Accès au panel Filament pour admin et gestionnaire

### Dashboard
- Statistiques en temps réel
- Graphique des réservations par mois
- Liste des dernières réservations

## Installation

### Prérequis
- PHP 8.2+
- Composer
- MySQL 8.0+ ou MariaDB 10.5+
- Node.js 18+

### Étapes d'installation

1. **Cloner le projet**
```bash
cd reservation-salle
```

2. **Installer les dépendances PHP**
```bash
composer install
```

3. **Configurer l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configurer la base de données**
Modifier le fichier `.env` avec vos identifiants de base de données :
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=reservation_salle
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe
```

5. **Créer la base de données**
```bash
mysql -u root -p -e "CREATE DATABASE reservation_salle CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

6. **Exécuter les migrations et le seeder**
```bash
php artisan migrate
php artisan db:seed
```

7. **Installer les assets de Filament**
```bash
php artisan filament:install --panels
```

8. **Créer le lien de stockage**
```bash
php artisan storage:link
```

9. **Lancer le serveur de développement**
```bash
php artisan serve
```

## Accès à l'application

### Panel d'administration
URL : `http://localhost:8000/admin`

### Comptes de test

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Administrateur | admin@example.com | password |
| Gestionnaire | gestionnaire@example.com | password |
| Utilisateur | marie@example.com | password |

## Structure du projet

```
app/
├── Filament/
│   ├── Resources/
│   │   ├── SalleResource.php
│   │   ├── ReservationResource.php
│   │   └── UserResource.php
│   └── Widgets/
│       ├── StatsOverview.php
│       ├── ReservationsChart.php
│       └── LatestReservations.php
├── Models/
│   ├── Salle.php
│   ├── Reservation.php
│   └── User.php
└── Providers/
    └── Filament/
        └── AdminPanelProvider.php

database/
├── migrations/
│   ├── 0001_01_01_000000_create_users_table.php
│   ├── 2024_01_01_000001_create_salles_table.php
│   └── 2024_01_01_000002_create_reservations_table.php
└── seeders/
    └── DatabaseSeeder.php
```

## Modèles de données

### Salle
- `nom` : Nom de la salle
- `capacite` : Nombre de places
- `equipements` : Liste des équipements
- `description` : Description de la salle
- `tarif_heure` : Tarif horaire en Ariary
- `disponible` : Statut de disponibilité
- `image` : Photo de la salle

### Réservation
- `salle_id` : Référence à la salle
- `user_id` : Référence à l'utilisateur
- `date_debut` : Date et heure de début
- `date_fin` : Date et heure de fin
- `objet` : Objet de la réservation
- `nombre_participants` : Nombre de participants
- `statut` : en_attente, confirmee, annulee, terminee
- `notes` : Notes supplémentaires
- `montant_total` : Montant calculé

### User
- `name` : Nom complet
- `email` : Adresse email
- `password` : Mot de passe
- `telephone` : Numéro de téléphone
- `role` : admin, gestionnaire, utilisateur
- `actif` : Statut du compte

## Personnalisation

### Couleurs du thème
Modifier dans `AdminPanelProvider.php` :
```php
->colors([
    'primary' => Color::Blue,
    'danger' => Color::Red,
    // ...
])
```

### Ajout d'équipements suggérés
Modifier dans `SalleResource.php` dans le champ `equipements`.

## Licence

MIT License

