<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\CreerSalleDTOBuilder;
use App\Model\Salle;
use App\Repository\SalleRepositoryInterface;
use App\Validation\SalleValidator;
use App\View\ViewRenderer;

final class SalleController
{
    public function __construct(
        private SalleRepositoryInterface $salles,
        private SalleValidator $validator,
        private ViewRenderer $view
    ) {
    }

    public function index(): string
    {
        return $this->view->render('salle/index', [
            'salles' => $this->salles->lister(),
        ]);
    }

    public function show(int $id): string
    {
        return $this->view->render('salle/show', [
            'salle' => $this->salles->trouver($id),
        ]);
    }

    public function create(): string
    {
        return $this->view->render('salle/form', [
            'errors' => [],
            'values' => [],
        ]);
    }

    public function store(array $data): string
    {
        $result = $this->validator->validate($data);

        if (!$result->isValid()) {
            return $this->view->render('salle/form', [
                'errors' => $result->errors(),
                'values' => $data,
            ]);
        }

        $dto = (new CreerSalleDTOBuilder())
            ->nom($result->data()['nom'])
            ->batiment($result->data()['batiment'])
            ->capacite($result->data()['capacite'])
            ->type($result->data()['type'])
            ->active($result->data()['active'])
            ->build();

        $salle = new Salle(
            $dto->getNom(),
            $dto->getBatiment(),
            $dto->getCapacite(),
            $dto->getType(),
            $dto->isActive()
        );
        $salle->setAttribute('nom', $dto->getNom());
        $salle->setAttribute('batiment', $dto->getBatiment());
        $salle->setAttribute('capacite', $dto->getCapacite());
        $salle->setAttribute('type', $dto->getType());
        $salle->setAttribute('active', $dto->isActive());
        $this->salles->enregistrer($salle);

        header('Location: /salles');
        return '';
    }
}
