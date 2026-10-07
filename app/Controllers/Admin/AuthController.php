<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Support\Auth;

final class AuthController
{
    public function showLogin(): void
    {
        header('X-Robots-Tag: noindex, nofollow');
        render('admin/login', [
            'pageTitle' => 'Acesso Restrito | ' . setting('name', 'PC Resolve'),
        ], 'auth');
    }

    public function login(): void
    {
        $email = input_str('email', 190);
        $password = (string) ($_POST['password'] ?? '');

        if (Auth::tooManyAttempts(client_ip())) {
            with_old(['email' => $email]);
            toast('Muitas tentativas de acesso. Aguarde 15 minutos e tente novamente.', 'error');
            redirect('/painel/login');
        }

        if ($email === '' || $password === '' || !Auth::attempt($email, $password)) {
            with_old(['email' => $email]);
            toast('E-mail ou senha inválidos.', 'error');
            redirect('/painel/login');
        }

        $user = Auth::user();
        $firstName = explode(' ', (string) ($user['name'] ?? ''))[0];
        toast('Bem-vindo(a) de volta, ' . $firstName . '!', 'success');

        $intended = (string) ($_SESSION['intended_url'] ?? '/painel');
        unset($_SESSION['intended_url']);
        if (!str_starts_with($intended, '/painel') || str_starts_with($intended, '//')) {
            $intended = '/painel';
        }

        redirect($intended);
    }

    public function logout(): void
    {
        Auth::logout();
        toast('Sessão encerrada com segurança.', 'info');
        redirect('/painel/login');
    }
}
