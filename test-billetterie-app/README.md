# Show Booking System - Laravel 10.10

Système de réservation de spectacles développé avec Laravel.

## 🚀 Démarrage Rapide

### Prérequis

- PHP 8.1+
- Composer
- Node.js 16+ et NPM
- Base de données (MySQL/PostgreSQL/SQLite)

### Installation

```bash
# 1. Installer les dépendances
composer install
npm install

# 2. Configurer l'environnement
cp .env.example .env
php artisan key:generate

# 3. Configurer la base de données dans .env
# DB_CONNECTION=mysql
# DB_DATABASE=show_booking
# ...

# 4. Créer la base de données et exécuter les migrations
php artisan migrate

# 5. Lier le stockage public
php artisan storage:link

# 6. (Optionnel) Remplir avec des données de test
php artisan db:seed
```

### Exécution

```bash
# Terminal 1 : Serveur Laravel
php artisan serve

# Terminal 2 : Compilation des assets (développement)
npm run dev
```

L'application sera accessible sur : **http://localhost:8000**

📖 **Guide complet** : Voir [SETUP_GUIDE.md](SETUP_GUIDE.md) pour plus de détails.

## 👤 Créer un utilisateur administrateur

## Permissions

```bash
php artisan tinker

# On crée le rôle admin avec 3 permissions pour créer, supprimer et modifier
Bouncer::allow('admin')->to('show-create');
Bouncer::allow('admin')->to('show-delete');
Bouncer::allow('admin')->to('show-edit');

# On crée le user
User::create(["firstname"=>"admin","lastname"=>"billetterie","email"=>"admin@billetterie.com","password"=>bcrypt("Not24get"),"phone_number"=>"0707070707"]);

# On assigne la variable $user au user venant d'être créé précédemment (Attention, il faut bien utiliser l'id correspondant au user souhaité dans la fonction find())
$user = \App\Models\User::find(id_du_user);

# On vérifie que c'est le bon user qui est utilisé
$user
Bouncer::assign('admin')->to($user);

# On vérifie les rôles attribués au user ainsi que ses permissions
$user->getRoles();
$user->getAbilities();

# On s'assure de sauvegarder
Bouncer::refresh()
```

## Nouvelles fonctionnalités implémentées

- **Statistiques ventes admin**
    - Route protégée `GET /admin/stats` (middleware `auth` + permission Bouncer `admin-stats` ou `show-create`).
    - Affichage du total de billets vendus, du chiffre d'affaires total, du top 3 spectacles par CA,
      des réservations par jour et des places restantes par spectacle.
    - Vue `admin/stats.blade.php` avec tableaux et graphique Chart.js.

- **Gestion d'images des spectacles**
    - Colonne `image` et `places_disponibles` sur le modèle `Show`.
    - Upload d'image avec validation, stockage dans `storage/app/public/shows`.
    - Pattern Singleton `ShowImageManager` pour centraliser la gestion des images.

- **Améliorations réservation**
    - Colonne `places_disponibles` utilisée pour vérifier le stock disponible lors du paiement.
    - `CartController@processPayment` utilise une transaction (`DB::transaction`) et décrémente le stock.
    - Pattern Factory `ReservationFactory` pour créer les réservations.
    - Pattern Observer `ReservationObserver` qui envoie automatiquement un mail `ReservationConfirmed`
      après chaque réservation créée.

- **API publique mobile**
    - Route `GET /api/shows` (middleware `auth:sanctum` + `throttle`) retournant les spectacles en JSON
      avec image et `places_disponibles`.
