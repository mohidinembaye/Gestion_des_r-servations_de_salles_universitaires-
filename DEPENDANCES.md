# Documentation des dépendances

Ce document décrit en détail toutes les dépendances utilisées par le projet **Gestion des réservations de salles universitaires**, leur rôle précis dans l'architecture, ainsi que la manière dont elles sont configurées et utilisées dans le code.

Le projet est géré par **Composer** (gestionnaire de dépendances PHP). La liste exacte des paquets et de leurs versions figées est disponible dans `composer.lock`. Les contraintes de version voulues par l'équipe sont déclarées dans `composer.json`.

## Sommaire

- [Prérequis système](#prérequis-système)
- [Dépendances de production (`require`)](#dépendances-de-production-require)
  - [nikic/fast-route](#nikicfast-route)
  - [respect/validation](#respectvalidation)
  - [illuminate/database](#illuminatedatabase)
  - [php-di/php-di](#php-diphp-di)
  - [vlucas/phpdotenv](#vlucasphpdotenv)
- [Dépendances de développement (`require-dev`)](#dépendances-de-développement-require-dev)
  - [phpunit/phpunit](#phpunitphpunit)
- [Dépendances transitives (installées automatiquement)](#dépendances-transitives-installées-automatiquement)
- [Tableau récapitulatif des versions verrouillées](#tableau-récapitulatif-des-versions-verrouillées)
- [Commandes utiles](#commandes-utiles)

## Prérequis système

Ces éléments ne sont pas des dépendances Composer mais des prérequis nécessaires à l'exécution du projet et à l'installation des paquets ci-dessous :

| Prérequis | Version | Rôle |
|---|---|---|
| PHP | ≥ 8.2 | Interpréteur nécessaire pour exécuter l'application et les dépendances (certaines dépendances, comme `illuminate/database` 12.x, exigent PHP 8.2+). |
| Composer | 2.x | Gestionnaire de dépendances PHP utilisé pour installer/mettre à jour les paquets listés dans `composer.json`. |
| MySQL | ≥ 8.0 | Système de gestion de base de données relationnelle utilisé en production/développement via `illuminate/database`. |
| Extension PHP `pdo_mysql` | - | Pilote PDO requis par Eloquent (`illuminate/database`) pour se connecter à MySQL. |

## Dépendances de production (`require`)

Ces paquets sont indispensables au fonctionnement de l'application en production. Ils sont déclarés dans `composer.json` :

```json
"require": {
    "nikic/fast-route": "^1.3",
    "respect/validation": "^2.4",
    "illuminate/database": "^12.0",
    "php-di/php-di": "^7.0",
    "vlucas/phpdotenv": "^5.7"
}
```

### nikic/fast-route

- **Version verrouillée** : `1.3.1`
- **Rôle** : Routeur HTTP rapide. Il associe une méthode HTTP et un chemin d'URL à une action de contrôleur.
- **Utilisation dans le projet** :
  - `routes/routes.php` déclare les routes de l'application (associations méthode/chemin → contrôleur).
  - `src/Container/container.php` construit un `FastRoute\Dispatcher` via `FastRoute\simpleDispatcher(...)`, enregistré dans le conteneur d'injection de dépendances.
  - `config/Router.php` utilise ce dispatcher pour analyser chaque requête entrante (`public/index.php`) et invoquer la méthode du contrôleur correspondante.
- **Pourquoi ce choix** : le projet n'utilise pas de framework complet (pas de Symfony/Laravel complet) ; FastRoute apporte uniquement la brique de routage, cohérente avec l'approche « composants spécialisés » décrite dans `README.md`.

### respect/validation

- **Version verrouillée** : `2.5.0` (dépendance transitive `respect/stringifier` en `1.0.0`)
- **Rôle** : Bibliothèque de validation de données permettant de composer des règles de validation (chaînables) pour vérifier les données reçues (formulaires, requêtes HTTP) avant de les transformer en DTO.
- **Utilisation dans le projet** :
  - `src/Validation/SalleValidator.php` et `src/Validation/ReservationValidator.php` définissent les règles de validation propres à chaque entité (salle, réservation).
  - Ces validateurs sont injectés automatiquement (autowiring) via PHP-DI (`SalleValidator::class => autowire()` et `ReservationValidator::class => autowire()` dans `src/Container/container.php`).
  - Les contrôleurs (`src/Controller/`) font appel à ces validateurs avant de construire les DTO (`src/DTO/`), garantissant que seules des données valides et typées circulent vers la couche métier.

### illuminate/database

- **Version verrouillée** : `v12.69.1`
- **Rôle** : Composant ORM (Object-Relational Mapping) extrait du framework Laravel, utilisé ici de façon autonome via le pattern **Capsule Manager**. Il fournit l'implémentation Active Record (Eloquent) ainsi que le Query Builder.
- **Utilisation dans le projet** :
  - `config/database.php` instancie `Illuminate\Database\Capsule\Manager`, configure la connexion MySQL (driver, host, port, base, identifiants, charset `utf8mb4`), l'enregistre comme connexion globale (`setAsGlobal()`) et démarre Eloquent (`bootEloquent()`).
  - `src/Container/container.php` enregistre `Manager::class` comme une fabrique (factory) dans le conteneur PHP-DI, en lisant les variables d'environnement `DB_DRIVER`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.
  - `src/Model/Salle.php` et `src/Model/Reservation.php` sont des modèles Eloquent (Active Record) qui représentent respectivement les tables `salles` et `reservations`.
  - `src/Repository/EloquentSalleRepository.php` et `src/Repository/EloquentReservationRepository.php` encapsulent les requêtes Eloquent derrière des interfaces (`SalleRepositoryInterface`, `ReservationRepositoryInterface`), afin d'isoler la couche persistance du reste de l'application (pattern Repository).
- **Dépendances transitives notables apportées par ce paquet** :
  - `illuminate/collections`, `illuminate/conditionable`, `illuminate/container`, `illuminate/contracts`, `illuminate/macroable`, `illuminate/reflection`, `illuminate/support` : sous-composants internes de l'écosystème Laravel nécessaires au fonctionnement d'Eloquent.
  - `nesbot/carbon` : extension de `DateTime` utilisée en interne par Eloquent pour la gestion des dates/heures (`created_at`, `updated_at`, casts de dates).
  - `doctrine/inflector` : utilisé pour la pluralisation/singularisation automatique des noms de tables.
  - `symfony/translation`, `symfony/translation-contracts` : utilisés en interne par certains messages de validation/traduction d'Illuminate.

### php-di/php-di

- **Version verrouillée** : `7.1.1` (dépendance `php-di/invoker` en `2.3.7`)
- **Rôle** : Conteneur d'injection de dépendances (IoC container) conforme à la norme **PSR-11**. Il permet l'autowiring (construction automatique des classes à partir de leurs constructeurs) et la définition explicite de liaisons interface → implémentation.
- **Utilisation dans le projet** :
  - `src/Container/ContainerFactory.php` encapsule la création du conteneur : instancie un `DI\ContainerBuilder`, lui ajoute le fichier de définitions `src/Container/container.php`, puis appelle `build()`.
  - `src/Container/container.php` définit les liaisons du projet :
    - `SalleRepositoryInterface::class => autowire(EloquentSalleRepository::class)`
    - `ReservationRepositoryInterface::class => autowire(EloquentReservationRepository::class)`
    - `DisponibiliteStrategyInterface::class => autowire(ReservationDisponibiliteStrategy::class)`
    - `Manager::class` et `Dispatcher::class` via des fabriques (`DI\factory`).
  - `public/index.php` récupère le conteneur (via `ContainerFactory`) et l'utilise pour instancier les contrôleurs avec toutes leurs dépendances résolues automatiquement (inversion de contrôle).
- **Bénéfice architectural** : permet le respect des principes SOLID (notamment l'inversion de dépendance) puisque les services et contrôleurs dépendent d'abstractions (interfaces) injectées par constructeur, plutôt que d'instancier eux-mêmes leurs dépendances.

### vlucas/phpdotenv

- **Version verrouillée** : `v5.7.0`
- **Rôle** : Charge les variables d'environnement définies dans le fichier `.env` vers `$_ENV` / `$_SERVER` (et éventuellement `getenv()`), afin de garder la configuration sensible (identifiants MySQL, etc.) hors du code versionné.
- **Utilisation dans le projet** :
  - `src/Container/container.php` appelle `Dotenv::createImmutable($root)->safeLoad()` (où `$root` est la racine du projet), rendant disponibles les variables `APP_ENV`, `APP_DEBUG`, `DB_DRIVER`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` déclarées dans `.env` (voir `.env.example` pour le modèle).
  - `safeLoad()` est utilisé plutôt que `load()` afin de ne pas provoquer d'erreur si le fichier `.env` est absent (par exemple en environnement de production où les variables sont injectées autrement, ex. conteneur Docker).
- **Dépendances transitives notables** :
  - `graham-campbell/result-type`, `phpoption/phpoption` : structures fonctionnelles (Option/Result) utilisées en interne par phpdotenv pour la gestion des erreurs de parsing.
  - `symfony/polyfill-ctype`, `symfony/polyfill-mbstring` : polyfills garantissant la disponibilité de certaines fonctions PHP natives même si les extensions correspondantes ne sont pas installées.

## Dépendances de développement (`require-dev`)

Ces paquets ne sont utilisés que pendant le développement et les tests ; ils ne sont pas nécessaires pour exécuter l'application en production.

```json
"require-dev": {
    "phpunit/phpunit": "^11.0"
}
```

### phpunit/phpunit

- **Version verrouillée** : `11.5.56`
- **Rôle** : Framework de tests unitaires et d'intégration standard de l'écosystème PHP.
- **Utilisation dans le projet** :
  - Les tests sont organisés dans `tests/Unit/` (tests unitaires isolés, par exemple des services ou validateurs avec des doublures) et `tests/Integration/` (tests couvrant l'interaction entre plusieurs couches, par exemple repository + base de données).
  - Exécution via le binaire généré par Composer :
    ```bash
    vendor/bin/phpunit
    ```
- **Dépendances de test transitives notables** :
  - `sebastian/*` (comparator, diff, exporter, global-state, object-enumerator, object-reflector, recursion-context, type, environment, complexity, lines-of-code, code-unit, code-unit-reverse-lookup, cli-parser, version) : bibliothèques internes utilisées par PHPUnit pour la comparaison de valeurs, l'analyse de complexité du code, le calcul de couverture, etc.
  - `phpunit/php-code-coverage` : génère les rapports de couverture de code.
  - `phpunit/php-file-iterator`, `phpunit/php-invoker`, `phpunit/php-text-template`, `phpunit/php-timer` : utilitaires internes (itération de fichiers, invocation avec timeout, moteur de templates simple, mesure du temps d'exécution).
  - `myclabs/deep-copy` : clonage profond d'objets, utilisé notamment pour isoler les instances entre tests.
  - `nikic/php-parser` : analyse syntaxique du code PHP, utilisée par les outils de couverture de code.
  - `phar-io/manifest`, `phar-io/version`, `theseer/tokenizer`, `staabm/side-effects-detector` : utilitaires internes de PHPUnit (lecture de manifestes PHAR, gestion de versions, tokenisation XML).

## Dépendances transitives (installées automatiquement)

Composer installe également des dépendances qui ne sont pas déclarées explicitement dans `composer.json` mais qui sont requises par les paquets ci-dessus. Elles sont résolues et figées automatiquement dans `composer.lock`. Elles n'ont pas vocation à être utilisées directement dans le code applicatif (`src/`), mais leur présence est nécessaire au bon fonctionnement des dépendances directes.

| Paquet | Version | Apporté par | Rôle |
|---|---|---|---|
| `brick/math` | 0.14.8 | `nesbot/carbon` | Arithmétique de précision arbitraire (calculs de dates avancés). |
| `carbonphp/carbon-doctrine-types` | 3.2.0 | `nesbot/carbon` | Types Carbon compatibles Doctrine (non utilisé directement ici, mais requis par le paquet). |
| `doctrine/inflector` | 2.1.0 | `illuminate/support` | Pluralisation/singularisation des noms (tables Eloquent). |
| `graham-campbell/result-type` | v1.2.0 | `vlucas/phpdotenv` | Type « Result » pour la gestion fonctionnelle des erreurs. |
| `illuminate/collections` | v12.69.1 | `illuminate/database` | Collections manipulables (map, filter, etc.) utilisées en interne par Eloquent. |
| `illuminate/conditionable` | v12.69.1 | `illuminate/database` | Trait `when()`/`unless()` pour un code conditionnel fluide. |
| `illuminate/container` | v12.69.1 | `illuminate/database` | Conteneur IoC interne de Laravel (utilisé par Eloquent, indépendant de PHP-DI). |
| `illuminate/contracts` | v12.69.1 | `illuminate/database` | Interfaces/contrats partagés de l'écosystème Illuminate. |
| `illuminate/macroable` | v12.69.1 | `illuminate/database` | Trait permettant d'ajouter des méthodes dynamiquement aux classes Illuminate. |
| `illuminate/reflection` | v12.69.1 | `illuminate/database` | Utilitaires de réflexion PHP internes. |
| `illuminate/support` | v12.69.1 | `illuminate/database` | Fonctions utilitaires générales de Laravel (helpers, Str, Arr, etc.). |
| `laravel/serializable-closure` | v2.0.16 | `illuminate/database` | Sérialisation sécurisée de closures (utilisé en interne par Illuminate). |
| `nesbot/carbon` | 3.13.2 | `illuminate/database` | Extension de `DateTime`, utilisée par Eloquent pour les timestamps et casts de dates. |
| `php-di/invoker` | 2.3.7 | `php-di/php-di` | Invocation générique et extensible de callables (utilisé en interne par PHP-DI). |
| `phpoption/phpoption` | 1.10.0 | `vlucas/phpdotenv` | Type « Option » pour la gestion fonctionnelle des valeurs optionnelles. |
| `psr/clock` | 1.0.0 | `nesbot/carbon` | Interface standard PSR pour la lecture de l'horloge système. |
| `psr/container` | 2.0.2 | `php-di/php-di` | Interface standard PSR-11 pour les conteneurs d'injection de dépendances. |
| `psr/simple-cache` | 3.0.0 | `illuminate/*` | Interface standard PSR-16 de cache simple. |
| `respect/stringifier` | 1.0.0 | `respect/validation` | Conversion de n'importe quelle valeur en chaîne de caractères (messages d'erreur de validation). |
| `symfony/clock` | v7.4.8 | `nesbot/carbon` | Découplage de l'horloge système pour faciliter les tests. |
| `symfony/deprecation-contracts` | v3.7.1 | plusieurs | Convention standard pour signaler les dépréciations. |
| `symfony/polyfill-ctype` | v1.37.0 | `vlucas/phpdotenv` | Polyfill des fonctions `ctype_*` si l'extension n'est pas installée. |
| `symfony/polyfill-mbstring` | v1.38.2 | `vlucas/phpdotenv` | Polyfill des fonctions `mbstring` si l'extension n'est pas installée. |
| `symfony/polyfill-php80` | v1.37.0 | plusieurs | Rétroportage de fonctionnalités PHP 8.0+ pour compatibilité. |
| `symfony/polyfill-php83` | v1.41.0 | plusieurs | Rétroportage de fonctionnalités PHP 8.3+. |
| `symfony/polyfill-php84` | v1.38.1 | plusieurs | Rétroportage de fonctionnalités PHP 8.4+. |
| `symfony/polyfill-php85` | v1.41.0 | plusieurs | Rétroportage de fonctionnalités PHP 8.5+. |
| `symfony/translation` | v7.4.17 | `illuminate/*` | Internationalisation utilisée en interne par certains messages Illuminate. |
| `symfony/translation-contracts` | v3.7.1 | `symfony/translation` | Interfaces abstraites de traduction. |
| `voku/portable-ascii` | 2.1.1 | `illuminate/support` | Fonctions ASCII performantes utilisées par les helpers `Str::` d'Illuminate. |

## Tableau récapitulatif des versions verrouillées

Extrait complet et fidèle du fichier `composer.lock` (paquets de production puis paquets de développement) :

### Paquets de production

| Paquet | Version |
|---|---|
| brick/math | 0.14.8 |
| carbonphp/carbon-doctrine-types | 3.2.0 |
| doctrine/inflector | 2.1.0 |
| graham-campbell/result-type | v1.2.0 |
| illuminate/collections | v12.69.1 |
| illuminate/conditionable | v12.69.1 |
| illuminate/container | v12.69.1 |
| illuminate/contracts | v12.69.1 |
| illuminate/database | v12.69.1 |
| illuminate/macroable | v12.69.1 |
| illuminate/reflection | v12.69.1 |
| illuminate/support | v12.69.1 |
| laravel/serializable-closure | v2.0.16 |
| nesbot/carbon | 3.13.2 |
| nikic/fast-route | 1.3.1 |
| php-di/invoker | 2.3.7 |
| php-di/php-di | 7.1.1 |
| phpoption/phpoption | 1.10.0 |
| psr/clock | 1.0.0 |
| psr/container | 2.0.2 |
| psr/simple-cache | 3.0.0 |
| respect/stringifier | 1.0.0 |
| respect/validation | 2.5.0 |
| symfony/clock | v7.4.8 |
| symfony/deprecation-contracts | v3.7.1 |
| symfony/polyfill-ctype | v1.37.0 |
| symfony/polyfill-mbstring | v1.38.2 |
| symfony/polyfill-php80 | v1.37.0 |
| symfony/polyfill-php83 | v1.41.0 |
| symfony/polyfill-php84 | v1.38.1 |
| symfony/polyfill-php85 | v1.41.0 |
| symfony/translation | v7.4.17 |
| symfony/translation-contracts | v3.7.1 |
| vlucas/phpdotenv | v5.7.0 |
| voku/portable-ascii | 2.1.1 |

### Paquets de développement

| Paquet | Version |
|---|---|
| myclabs/deep-copy | 1.14.0 |
| nikic/php-parser | v5.8.0 |
| phar-io/manifest | 2.0.4 |
| phar-io/version | 3.2.1 |
| phpunit/php-code-coverage | 11.0.12 |
| phpunit/php-file-iterator | 5.1.1 |
| phpunit/php-invoker | 5.0.1 |
| phpunit/php-text-template | 4.0.1 |
| phpunit/php-timer | 7.0.1 |
| phpunit/phpunit | 11.5.56 |
| sebastian/cli-parser | 3.0.2 |
| sebastian/code-unit | 3.0.3 |
| sebastian/code-unit-reverse-lookup | 4.0.1 |
| sebastian/comparator | 6.3.3 |
| sebastian/complexity | 4.0.1 |
| sebastian/diff | 6.0.2 |
| sebastian/environment | 7.2.1 |
| sebastian/exporter | 6.3.2 |
| sebastian/global-state | 7.0.2 |
| sebastian/lines-of-code | 3.0.1 |
| sebastian/object-enumerator | 6.0.1 |
| sebastian/object-reflector | 4.0.1 |
| sebastian/recursion-context | 6.0.3 |
| sebastian/type | 5.1.3 |
| sebastian/version | 5.0.2 |
| staabm/side-effects-detector | 1.0.5 |
| theseer/tokenizer | 1.3.1 |

> Note : ce tableau reflète l'état exact de `composer.lock` au moment de la rédaction de ce document. En cas de mise à jour des dépendances (`composer update`), il devra être régénéré.

## Commandes utiles

```bash
# Installer toutes les dépendances (production + développement) selon composer.lock
composer install

# Appliquer les migrations en attente
php database/migrate.php

# Annuler le dernier lot de migrations
php database/migrate.php down

# Installer uniquement les dépendances de production (à utiliser en déploiement)
composer install --no-dev --optimize-autoloader

# Mettre à jour une dépendance précise en respectant les contraintes de composer.json
composer update nikic/fast-route

# Lister les paquets installés avec leur version exacte
composer show

# Afficher l'arborescence des dépendances d'un paquet (ex. illuminate/database)
composer show illuminate/database --tree

# Vérifier les vulnérabilités connues dans les dépendances installées
composer audit

# Lancer la suite de tests PHPUnit
vendor/bin/phpunit
```

## Voir aussi

- `composer.json` : contraintes de version voulues par l'équipe.
- `composer.lock` : versions exactes installées et arbre complet des dépendances.
- `ARCHITECTURE.md` : explication du rôle architectural (MVC, Repository, DI, SOLID) dans lequel s'inscrivent ces dépendances.
- `README.md` : procédure d'installation et de configuration de l'environnement.
