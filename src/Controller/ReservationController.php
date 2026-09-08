<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\CreerReservationDTOBuilder;
use App\DTO\CreerReservationDTO;
use App\Exception\ReservationIntrouvableException;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use App\Service\AnnulerReservationService;
use App\Service\CreerReservationService;
use App\Validation\ReservationValidator;
use App\View\ViewRenderer;
use DateTimeImmutable;

final class ReservationController
{
    public function __construct(
        private ReservationRepositoryInterface $reservations,
        private SalleRepositoryInterface $salles,
        private ReservationValidator $validator,
        private CreerReservationService $creation,
        private AnnulerReservationService $annulation,
        private ViewRenderer $view
    ) {
    }

    public function index(?int $salleId = null): string
    {
        return $this->view->render('reservation/index', [
            'reservations' => $this->reservations->lister($salleId),
            'salles' => $this->salles->lister(),
            'salleId' => $salleId,
        ]);
    }

    public function show(int $id): string
    {
        $reservation = $this->reservations->trouver($id);

        if ($reservation === null) {
            throw new ReservationIntrouvableException('La réservation demandée est introuvable.');
        }

        return $this->view->render('reservation/show', [
            'reservation' => $reservation,
        ]);
    }

    public function create(): string
    {
        return $this->view->render('reservation/form', [
            'salles' => $this->salles->lister(),
            'errors' => [],
            'values' => [],
        ]);
    }

    public function store(array $data): string
{
    $result = $this->validator->validate($data);

    if (!$result->isValid()) {
        return $this->view->render('reservation/form', [
            'salles' => $this->salles->lister(),
            'errors' => $result->errors(),
            'values' => $data,
        ]);
    }

    $dto = CreerReservationDTO::fromArray($result->data());

    try {
        $this->creation->executer($dto);
    } catch (\InvalidArgumentException $e) {
        return $this->view->render('reservation/form', [
            'salles' => $this->salles->lister(),
            'errors' => ['date_debut' => $e->getMessage()],
            'values' => $data,
        ]);
    }

    header('Location: /reservations');

    return '';
}

    public function cancel(int $id): string
    {
        $this->annulation->executer($id);
        header('Location: /reservations');

        return '';
    }
}
