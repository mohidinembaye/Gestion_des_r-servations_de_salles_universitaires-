<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\DTO\CreerReservationDTO;
use App\Model\Salle;
use App\Model\Reservation;
use App\Repository\EloquentReservationRepository;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use DI\ContainerBuilder;
use Illuminate\Database\Capsule\Manager as Capsule;

final class EloquentTest extends TestCase
{
    /**
     * Cette méthode s'exécute AVANT chaque test d'intégration pour connecter MySQL
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // 1. Instancier le conteneur en lisant vos définitions globales
        $builder = new ContainerBuilder();
        $builder->addDefinitions(dirname(__DIR__, 2) . '/config/container.php');
        $container = $builder->build();
        
        // 2. Extraire l'instance Capsule configurée par la factory de PHP-DI
        /** @var Capsule $capsule */
        $capsule = $container->get(Capsule::class);
        
        // 3. 💡 FORCE l'enregistrement global pour que l'Active Record d'Eloquent s'amorce sous PHPUnit
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        Reservation::query()->delete();
        Salle::query()->delete();
    }

    /**
     * Test : la création d’une salle avec Eloquent & la relation salle/réservations
     */
    public function testCreationSalleEtRelation(): void
    {
        $salle = Salle::create([
            'nom' => 'Salle Test Intégration',
            'batiment' => 'Bâtiment T',
            'capacite' => 30,
            'type' => 'cours',
            'active' => true
        ]);

        $this->assertNotNull($salle->id);

        $reservation = Reservation::create([
            'salle_id' => $salle->id,
            'responsable' => 'Professeur Test',
            'email' => 'test@univ.sn',
            'motif' => 'Recherche',
            'date_debut' => '2027-12-01 08:00:00',
            'date_fin' => '2027-12-01 10:00:00',
            'statut' => 'confirmée'
        ]);

        $this->assertCount(1, $salle->refresh()->reservations);
    }

    /**
     * Test : la recherche de chevauchement & l’annulation d’une réservation
     */
    public function testRechercheChevauchementEtAnnulation(): void
    {
        $reservationRepo = new EloquentReservationRepository();

        $salle = Salle::create([
            'nom' => 'Amphi Conflit',
            'batiment' => 'Bâtiment C',
            'capacite' => 150,
            'type' => 'amphitheatre',
            'active' => true
        ]);

        $dto = new CreerReservationDTO(
            $salle->id, 
            'Alice', 
            'alice@univ.sn', 
            'Soutenance', 
            new DateTimeImmutable('2027-11-20 14:00:00'), 
            new DateTimeImmutable('2027-11-20 16:00:00')
        );
        
        $res = $reservationRepo->enregistrer($dto);

        // Vérification du conflit
        $conflit = $reservationRepo->rechercherConflit($salle->id, new DateTimeImmutable('2027-11-20 15:00:00'), new DateTimeImmutable('2027-11-20 17:00:00'));
        $this->assertTrue($conflit);

        // Vérification de l'annulation
        $reservationRepo->annuler($res->id);
        $this->assertEquals('annulée', $res->refresh()->statut);
    }
}
