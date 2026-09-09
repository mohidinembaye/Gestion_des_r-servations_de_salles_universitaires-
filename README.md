Partie 1 : Composer

Quel est le rôle de Composer ?
C'est le gestionnaire de dépendances officiel de PHP. Il automatise le téléchargement des bibliothèques, gère les versions et génère un système de chargement automatique (PSR-4) pour éviter d'écrire des require partout.

Quelle est la différence entre require et require-dev ?

    require : Packages indispensables au fonctionnement de l'application (installés partout, y compris en production).

    require-dev : Outils utiles uniquement pour le développement ou les tests locaux (non déployés en production).

Pourquoi faut-il versionner composer.lock ?
Il fige les versions exactes des dépendances installées. Cela garantit que toute l'équipe et le serveur de production utilisent rigoureusement le même code, évitant ainsi les bugs de compatibilité.

Pourquoi ne versionne-t-on pas vendor/ ?
Ce dossier contient des milliers de fichiers lourds inutiles dans Git. Le fichier composer.lock suffit pour que n'importe qui puisse recréer le dossier à l'identique avec la commande composer install.

Partie 2 : ORM Eloquent

Quel rôle joue Capsule\Manager ?
Il initialise et centralise tous les composants de base de données de Laravel (connexions, requêtes) pour les rendre utilisables en dehors du framework.

Pourquoi Eloquent peut-il fonctionner sans Laravel ?
Parce que ses composants sont conçus de manière modulaire. Le package illuminate/database est totalement autonome sur Packagist.

Où doit se trouver le démarrage de l’ORM ?
Au tout début du cycle de vie de l'application, dans le point d'entrée unique (public/index.php), avant toute manipulation de données.

Quelle est la différence entre un ORM et du SQL écrit à la main ?

    SQL (PDO) : Requêtes textuelles brutes, verbeuses et sujettes aux erreurs.

    ORM (Eloquent) : Manipulation d'objets et de méthodes PHP (Salle::find(1)). L'ORM génère le SQL en arrière-plan.

Partie 3 : Relations et Modèles Eloquent

Quel type de relation avez-vous utilisé ?
Une relation One-to-Many (Un-vers-Plusieurs) : HasMany dans Salle (une salle a plusieurs réservations) et BelongsTo dans Reservation (une réservation appartient à une seule salle).

Pourquoi déclarer $fillable ?
C'est une liste blanche qui sécurise l'insertion de données en autorisant uniquement les champs spécifiés (ex: via un formulaire), évitant les injections malveillantes.

Pourquoi convertir active en booléen ?
MySQL stocke les booléens sous forme d'entiers (0 ou 1). L'ORM les convertit automatiquement en true ou false en PHP.

Pourquoi convertir les dates en objets ?
MySQL renvoie les dates sous forme de simples chaînes de texte. L'ORM les transforme en objets (\DateTime) pour pouvoir faire des calculs et des comparaisons facilement.

Partie 4 : Migrations et Seeders

Quelle est la différence entre migration et seeder ?

    Migration : Crée la structure (tables, colonnes, types de données, contraintes).

    Seeder : Remplit les tables avec des données initiales (ex: 5 salles de test).

Pourquoi les données initiales doivent-elles être reproductibles ?
Pour permettre à n'importe quel développeur de recréer un environnement de test identique en une seule commande.

Comment empêcher les doublons ?
En combinant une contrainte d'unicité (unique()) au niveau de la base de données (MySQL) et une vérification préalable dans le code PHP.

Partie 5 : Validation et Services

Pourquoi séparer la validation syntaxique des règles métier ?
Pour des raisons de performance et de responsabilité unique. La validation se fait en mémoire (ultra-rapide) pour rejeter les erreurs avant d'interroger la base de données.

Pourquoi créer une interface de validation ?
Pour l'interopérabilité. Le contrôleur dépend d'un contrat (ValidatorInterface) et non d'une classe spécifique, facilitant les tests et les modifications futures.

Pourquoi le validateur ne doit-il pas enregistrer les données ?
Un validateur est une fonction pure : il vérifie la donnée et s'arrête. L'enregistrement en base est le rôle exclusif du Service.

Comment retourner plusieurs erreurs en une seule fois ?
En utilisant un tableau accumulateur qui collecte toutes les erreurs de saisie au lieu de bloquer l'exécution dès la première anomalie.


Partie 6 : DTO (Data Transfer Object)

Quelle est la différence entre un DTO et un modèle Eloquent ?

    DTO : Simple objet de transport de données, immuable et sans logique.

    Modèle Eloquent : Entité liée à la base de données qui gère les requêtes SQL et l'ORM.

Pourquoi le DTO ne doit-il pas appeler save() ?
Pour respecter le principe de responsabilité unique (SOLID). Le DTO sert uniquement à transporter de la donnée, pas à interagir avec la base de données.

À quel moment transforme-t-on les chaînes en dates ?
Pendant la validation syntaxique. Cela garantit que dès que le DTO est créé, ses dates sont déjà parfaitement typées.

Le DTO doit-il contenir la règle de chevauchement ?
Non. Le chevauchement est une règle métier complexe qui nécessite une requête en base de données. Cette tâche revient au Service.


Partie 7 : Repositories

Eloquent constitue-t-il déjà un accès aux données ?
Oui. Chaque modèle Eloquent embarque nativement toute la logique pour dialoguer avec MySQL (Salle::all(), $salle->save()).

Pourquoi ajouter un Repository au-dessus d’Eloquent ?
Pour isoler l'ORM et respecter le principe de responsabilité unique, évitant de propager du code technique dans les contrôleurs.

Cette abstraction est-elle toujours nécessaire ?
Non. Sur de petits projets CRUD, cela peut être du code superflu (sur-ingénierie). Elle devient utile lorsque l'application grandit.

Quel avantage apporte-t-elle ?
Un découplage total et une testabilité parfaite. Si l'on change de base de données ou d'ORM, le code métier reste intact.


Partie 8 : Services Métier

Pourquoi ces règles ne sont-elles pas dans le contrôleur ?
Pour respecter le principe de responsabilité unique. Le contrôleur gère uniquement la requête HTTP, tandis que le Service centralise la logique métier.

Pourquoi le service dépend-il d’une interface de Repository ?
Pour respecter l'inversion des dépendances (SOLID - DIP). Le service ne dépend pas d'une techno de stockage précise, mais d'un contrat.

Quelle exception doit être levée en cas de conflit ?
Une exception spécifique comme SalleIndisponibleException, car la demande est valide mais la ressource est occupée.

Comment tester le service sans MySQL ?
Grâce aux Mocks (ou simulateurs). En test unitaires, on remplace le vrai dépôt par un faux dépôt en mémoire qui implémente la même interface.


Partie 10 : Routage (FastRoute)

Pourquoi FastRoute ne construit-il pas lui-même le contrôleur ?
Parce qu'il n'est qu'un aiguilleur d'URL. C'est le conteneur de dépendances qui fabrique les objets et leurs dépendances.

Quelle est la différence entre 404 et 405 ?

    404 : L'URL demandée n'existe pas.

    405 : L'URL existe, mais la méthode HTTP utilisée (GET, POST...) n'est pas autorisée sur cette route.

Pourquoi contraindre {id} avec \d+ ?
Pour forcer l'identifiant à n'être composé que de chiffres, bloquant ainsi les erreurs de saisie et les tentatives d'injection.

Quel composant doit interpréter le handler retourné ?
Le Dispatcher (répartiteur), qui demande au conteneur de créer le contrôleur et exécute la bonne méthode.


Partie 11 : Conteneur et Injection de Dépendances

Quelle est la différence entre injection et conteneur ?
L'injection est un principe de conception (on fournit ses outils à une classe au lieu qu'elle les crée elle-même). Le conteneur est l'outil automatisé qui gère et distribue ces objets.

Qu’est-ce que l’autowiring ?
C'est la capacité du conteneur à deviner tout seul les dépendances d'une classe en lisant le type des arguments de son constructeur.

Pourquoi les interfaces nécessitent-elles une définition ?
Parce qu'une interface n'est qu'un contrat et ne peut pas être instanciée directement avec new. Il faut indiquer au conteneur quelle classe concrète lui associer.

Pourquoi limiter $container->get() au point d’entrée ?
Pour éviter l'anti-pattern du Service Locator. Le conteneur doit agir en coulisses au démarrage (index.php), puis l'autowiring prend le relais.

Quel anti-pattern apparaît si toutes les classes interrogent le conteneur ?
Le Service Locator. Les classes deviennent dépendantes du conteneur lui-même, rendant le code difficile à tester et à réutiliser.