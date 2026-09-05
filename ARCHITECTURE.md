# Analyse architecturale

## MVC

Les contrôleurs recevront les requêtes HTTP, les modèles représenteront les données Eloquent et les templates afficheront les réponses. Cette séparation limite le mélange entre affichage, accès aux données et règles métier.

## Front Controller

`public/index.php` sera l’unique point d’entrée HTTP. Il chargera Composer, construira les composants techniques et lancera le traitement de la requête.

## Router

`routes/web.php` déclarera les routes FastRoute. Le routeur associera une méthode et un chemin à une action de contrôleur.

## Validator

Les classes du dossier `src/Validation/` contrôleront les données reçues avant leur transformation en DTO. Respect\Validation permettra de composer les règles de validation.

## DTO

Les objets du dossier `src/DTO/` transporteront des données déjà validées et correctement typées entre les contrôleurs et les services. Ils ne persisteront jamais directement les données.

## ORM et Active Record

`Salle` et `Reservation` utiliseront Eloquent fourni par `illuminate/database`. Chaque modèle représente une table et peut être chargé ou persisté par Eloquent.

## Repository

Les contrats et implémentations du dossier `src/Repository/` isoleront les requêtes Eloquent. Les contrôleurs et services dépendront de contrats plutôt que d’appeler directement le Query Builder.

## Service métier

Les services du dossier `src/Service/` porteront les règles de réservation : salle active, dates cohérentes, durée maximale et absence de chevauchement.

## Injection par constructeur

Les dépendances nécessaires seront reçues dans les constructeurs. Une classe pourra donc être testée avec des doublures sans accéder globalement au conteneur.

## Conteneur d’injection

`config/container.php` configurera PHP-DI. Les interfaces recevront des implémentations explicites et les classes concrètes simples pourront utiliser l’autowiring.

## Autowiring et inversion de contrôle

L’autowiring permet au conteneur de construire les classes concrètes à partir de leurs constructeurs. L’inversion de contrôle signifie que la construction des dépendances est confiée au conteneur plutôt qu’aux classes métier.

## SOLID

- **S** : chaque classe aura une responsabilité ciblée ;
- **O** : les contrats permettront d’étendre les repositories sans modifier les services ;
- **L** : les implémentations de repository pourront remplacer leurs interfaces ;
- **I** : les interfaces resteront limitées aux opérations nécessaires ;
- **D** : les services dépendront d’abstractions injectées par constructeur.

## Limites

L’ajout de couches augmente le nombre de fichiers et demande une configuration initiale plus importante. En contrepartie, le code est plus testable et les règles métier restent indépendantes de HTTP et de MySQL.
