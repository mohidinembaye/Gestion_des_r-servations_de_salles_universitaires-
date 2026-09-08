<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\CreerSalleDTO;
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

        $dto = CreerSalleDTO::fromArray($result->data());
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

        $dto = CreerSalleDTO::fromArray($result->data());

        $this->salles->enregistrer($dto);

        header('Location: /salles?created=1');
        return '';
    }
}
