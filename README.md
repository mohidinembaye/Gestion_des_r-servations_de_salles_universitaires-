# Gestion des réservations de salles universitaires

Application PHP orientée objet permettant de gérer les salles universitaires et leurs réservations.
Le projet est construit sans framework complet, avec des composants spécialisés installés par Composer.

## Prérequis

- PHP 8.2 ou supérieur ;
- Composer ;
- MySQL 8.0 ou supérieur ;
- extension PHP `pdo_mysql`.

## Installation

```bash
composer install
cp .env.example .env
```

Renseigner ensuite les paramètres MySQL dans `.env`. Ce fichier reste local et ne doit jamais être versionné.

Créer la base de données dans MySQL :

```sql
CREATE DATABASE reservation_salles
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

Sélectionner la base puis exécuter le schéma :

```bash
mysql -u reservation_user -p reservation_salles < database/schema.sql
```

Tester la connexion PHP :

```bash
php database/check_connection.php
```

Ajouter les salles initiales :

```bash
php database/seed.php
```

Le seeder peut être exécuté plusieurs fois sans créer de doublons.

## Structure

- `config/` contient la configuration technique ;
- `database/` contient le schéma SQL et le seeder ;
- `public/` sera l’unique point d’entrée HTTP ;
- `routes/` contiendra les routes FastRoute ;
- `src/` contient les modèles, services, repositories, DTO et validateurs ;
- `templates/` contient les vues ;
- `tests/` contient les tests unitaires et d’intégration.

## Développement

Le projet est versionné progressivement selon les étapes du cahier des charges. Les branches et tags indiquent l’avancement de chaque étape.

## Tests

Les tests PHPUnit seront exécutés avec :

```bash
vendor/bin/phpunit
```

## Version actuelle

`v0.4.0` - modèles Eloquent, connexion MySQL, schéma SQL et données initiales.
