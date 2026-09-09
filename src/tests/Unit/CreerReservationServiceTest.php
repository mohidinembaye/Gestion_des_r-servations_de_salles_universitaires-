<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\DTO\CreerReservationDTO;
use App\Exception\PeriodeInvalideException;
use App\Exception\SalleIndisponibleException;
use App\Exception\SalleIntrouvableException;
use App\Model\Reservation;
use App\Model\Salle;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use App\Service\CreerReservationService;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class CreerReservationServiceTest extends TestCase
{
    private $salleRepoMock;
    private $reservationRepoMock;
    private CreerReservationService $service;

    protected function setUp(): void
    {
        $this->salleRepoMock = $this->createMock(SalleRepositoryInterface::class);
        $this->reservationRepoMock = $this->createMock(ReservationRepositoryInterface::class);
        $this->service = new CreerReservationService($this->salleRepoMock, $this->reservationRepoMock);
    }

    private function creerDto(string $debut, string $fin, int $salleId = 1): CreerReservationDTO
    {
        return new CreerReservationDTO(
            salleId: $salleId,
            responsable: 'M. Diop',
            email: 'diop@univ.sn',
            motif: 'Cours de PHP',
            dateDebut: new DateTimeImmutable($debut),
            dateFin: new DateTimeImmutable($fin)
        );
    }

    public function testReservationValide(): void
    {
        $salle = new Salle(['active' => true]);
        $dto = $this->creerDto('2027-10-15 10:00:00', '2027-10-15 12:00:00');

        $this->salleRepoMock->method('trouverParId')->willReturn($salle);
        $this->reservationRepoMock->method('rechercherConflit')->willReturn(false);
        $this->reservationRepoMock->method('enregistrer')->willReturn(new Reservation());

        $resultat = $this->service->creer($dto);
        $this->assertInstanceOf(Reservation::class, $resultat);
    }

    public function testSalleInexistanteLeveException(): void
    {
        $dto = $this->creerDto('2027-10-15 10:00:00', '2027-10-15 12:00:00');
        $this->salleRepoMock->method('trouverParId')->willReturn(null);

        $this->expectException(SalleIntrouvableException::class);
        $this->service->creer($dto);
    }

    public function testSalleInactiveLeveException(): void
    {
        $salle = new Salle(['active' => false]);
        $dto = $this->creerDto('2027-10-15 10:00:00', '2027-10-15 12:00:00');
        $this->salleRepoMock->method('trouverParId')->willReturn($salle);

        $this->expectException(SalleIndisponibleException::class);
        $this->service->creer($dto);
    }

    public function testFinAnterieureAuDebutLeveException(): void
    {
        $salle = new Salle(['active' => true]);
        $dto = $this->creerDto('2027-10-15 12:00:00', '2027-10-15 10:00:00');
        $this->salleRepoMock->method('trouverParId')->willReturn($salle);

        $this->expectException(PeriodeInvalideException::class);
        $this->service->creer($dto);
    }

    public function testDureeExcessiveLeveException(): void
    {
        $salle = new Salle(['active' => true]);
        $dto = $this->creerDto('2027-10-15 10:00:00', '2027-10-15 15:00:00');
        $this->salleRepoMock->method('trouverParId')->willReturn($salle);

        $this->expectException(PeriodeInvalideException::class);
        $this->service->creer($dto);
    }

    public function testDatePasseeLeveException(): void
    {
        $salle = new Salle(['active' => true]);
        $dto = $this->creerDto('2020-01-01 10:00:00', '2020-01-01 12:00:00');
        $this->salleRepoMock->method('trouverParId')->willReturn($salle);

        $this->expectException(PeriodeInvalideException::class);
        $this->service->creer($dto);
    }

    public function testConflitHoraireLeveException(): void
    {
        $salle = new Salle(['active' => true]);
        $dto = $this->creerDto('2027-10-15 10:00:00', '2027-10-15 12:00:00');
        
        $this->salleRepoMock->method('trouverParId')->willReturn($salle);
        $this->reservationRepoMock->method('rechercherConflit')->willReturn(true);

        $this->expectException(SalleIndisponibleException::class);
        $this->service->creer($dto);
    }

    public function testReservationVoisineAcceptee(): void
    {
        $salle = new Salle(['active' => true]);
        $dto = $this->creerDto('2027-10-15 10:00:00', '2027-10-15 12:00:00');

        $this->salleRepoMock->method('trouverParId')->willReturn($salle);
        $this->reservationRepoMock->method('rechercherConflit')->willReturn(false); 
        $this->reservationRepoMock->method('enregistrer')->willReturn(new Reservation());

        $resultat = $this->service->creer($dto);
        $this->assertInstanceOf(Reservation::class, $resultat);
    }
}
