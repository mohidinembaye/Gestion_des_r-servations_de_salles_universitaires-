<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\PdoResponsableRepository;
use App\View\ViewRenderer;

class AuthController
{
    public function __construct(
        private readonly ViewRenderer $view,
        private readonly PdoResponsableRepository $responsables
    ) {
    }

    public function showLoginForm(): string
    {
        $error = isset($_GET['error']) ? 'Identifiants invalides.' : null;

        return $this->view->render('auth/login', [
            'title' => 'Connexion',
            'error' => $error,
        ], false);
    }

    public function login(array $data = []): string
    {
        $email = trim((string) ($data['email'] ?? ''));
        $password = (string) ($data['password'] ?? '');

        $responsable = $this->responsables->findByEmail($email);

        if ($responsable && password_verify($password, $responsable['password'])) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['responsable_id'] = $responsable['id'];
            $_SESSION['responsable_nom'] = $responsable['nom'];

            header('Location: /salles');
            return '';
        }

        header('Location: /login?error=1');
        return '';
    }

    public function logout(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();

        header('Location: /login');
        return '';
    }
}
