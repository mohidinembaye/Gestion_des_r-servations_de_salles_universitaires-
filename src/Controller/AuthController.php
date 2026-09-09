<?php

declare(strict_types=1);

namespace App\Controller;

use App\Models\Responsable;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthController
{
    public function showLoginForm(): ResponseInterface
    {
        // Remplacez par votre moteur de template (ex: Twig) ou un rendu HTML simple
        $html = '
        <form method="POST" action="/login">
            <label>Email: <input type="email" name="email" required></label><br>
            <label>Mot de passe: <input type="password" name="password" required></label><br>
            <button type="submit">Se connecter</button>
        </form>';

        return new HtmlResponse($html);
    }

    public function login(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        /** @var Responsable|null $responsable */
        $responsable = Responsable::where('email', $email)->first();

        if ($responsable && password_verify($password, $responsable->password)) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['responsable_id'] = $responsable->id;
            $_SESSION['responsable_nom'] = $responsable->nom;

            return new RedirectResponse('/salles'); // Redirection après succès
        }

        return new RedirectResponse('/login?error=1');
    }

    public function logout(): ResponseInterface
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();

        return new RedirectResponse('/login');
    }
}