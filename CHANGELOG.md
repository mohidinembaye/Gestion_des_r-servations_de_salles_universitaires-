# Changelog

Toutes les évolutions importantes du projet sont consignées dans ce fichier.

## [v0.0.0] - 2026-09-05

- Initialisation du dépôt Git.
- Création du fichier `README.md`.
- Création du fichier `CHANGELOG.md`.
- Ajout du fichier `.gitignore`.

## [v0.1.0] - 2026-09-05

- Initialisation du projet Composer.
- Configuration de l’autoloading PSR-4 `App\\` vers `src/`.
- Installation des dépendances imposées et génération de `composer.lock`.

## [v0.2.0] - 2026-09-05

- Ajout de la configuration Eloquent avec `Capsule\\Manager`.
- Ajout de la configuration `.env.example`.
- Ajout du schéma SQL manuel dans `database/schema.sql`.
- Ajout du script de vérification de connexion MySQL.

## [v0.3.0] - 2026-09-05

- Création des modèles `Salle` et `Reservation`.
- Ajout des propriétés privées typées, constructeurs et getters.
- Ajout des relations entre les salles et les réservations.

## [v0.4.0] - 2026-09-05

- Ajout du seeder des cinq salles initiales.
- Prévention des doublons lors des exécutions répétées du seeder.
