<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\CreerSalleDTOBuilder;
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

    public function edit(int $id): string
    {
        return $this->view->render('salle/form', [
            'salle' => $this->salles->trouver($id),
            'errors' => [],
            'values' => [],
        ]);
    }

    public function update(int $id, array $data): string
    {
        $result = $this->validator->validate($data);

        if (!$result->isValid()) {
            return $this->view->render('salle/form', [
                'salle' => $this->salles->trouver($id),
                'errors' => $result->errors(),
                'values' => $data,
            ]);
        }

        if ($this->salles->trouver($id) === null) {
            return $this->view->render('error/404');
        }

        $accepted = $result->data();
        $dto = (new CreerSalleDTOBuilder())
            ->nom($accepted['nom'])
            ->batiment($accepted['batiment'])
            ->capacite($accepted['capacite'])
            ->type($accepted['type'])
            ->active($accepted['active'])
            ->build();
        $this->salles->modifier($id, $dto);

        header('Location: /salles/' . $id);

        return '';
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

        $this->salles->enregistrer($dto);

        header('Location: /salles');
        return '';
    }
}
