<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Setting;
use App\Models\User;
use App\Support\Auth;
use App\Support\ImageProcessor;
use Throwable;

final class ProfileController
{
    /** Pasta pública onde ficam as fotos de perfil. */
    private const AVATAR_DIR = 'assets/img/avatars';

    public function index(): void
    {
        $user = Auth::user();
        $logins = User::recentLogins((int) $user['id'], 3);

        // "Último acesso" = login anterior ao atual (ou o atual, se for o primeiro).
        $previous = $logins[1] ?? $logins[0] ?? null;

        render('admin/profile', [
            'menu' => 'perfil',
            'pageTitle' => 'Perfil do Usuário | ' . setting('name'),
            'user' => $user,
            'logins' => $logins,
            'lastLogin' => User::describeLogin($previous['created_at'] ?? $user['last_login_at'], $previous['user_agent'] ?? null),
            'currentSessionHash' => hash('sha256', session_id()),
            'imageConfig' => Setting::imageConfig(),
        ], 'admin');
    }

    /** Envio da foto de perfil (arrastar e soltar ou seleção de arquivo). */
    public function avatar(): void
    {
        $user = Auth::user();
        $file = $_FILES['avatar'] ?? null;

        if (!is_array($file)) {
            toast('Selecione uma imagem para enviar.', 'warning');
            redirect('/painel/perfil');
        }

        try {
            $processor = new ImageProcessor(Setting::imageConfig());
            $result = $processor->handleUpload(
                $file,
                BASE_PATH . '/public/' . self::AVATAR_DIR,
                'user-' . $user['id'] . '-' . bin2hex(random_bytes(4))
            );
        } catch (Throwable $e) {
            toast($e->getMessage(), 'error');
            redirect('/painel/perfil');
        }

        $previous = (string) $user['avatar'];
        User::updateProfile((int) $user['id'], [
            'avatar' => self::AVATAR_DIR . '/' . basename($result['path']),
        ]);
        self::deleteAvatarFile($previous);

        toast(sprintf(
            'Foto atualizada! Imagem otimizada para %d × %d px e %s.',
            $result['width'],
            $result['height'],
            ImageProcessor::humanSize($result['bytes'])
        ), 'success');
        redirect('/painel/perfil');
    }

    public function removeAvatar(): void
    {
        $user = Auth::user();
        $previous = (string) $user['avatar'];

        if ($previous === '') {
            redirect('/painel/perfil');
        }

        User::updateProfile((int) $user['id'], ['avatar' => '']);
        self::deleteAvatarFile($previous);

        toast('Foto de perfil removida.', 'info');
        redirect('/painel/perfil');
    }

    /** Apaga o arquivo anterior, se for um avatar enviado pelo painel. */
    private static function deleteAvatarFile(string $path): void
    {
        $path = ltrim($path, '/');
        if ($path === '' || !str_starts_with($path, self::AVATAR_DIR . '/')) {
            return; // Caminho externo ou imagem de demonstração: não remove.
        }

        $full = BASE_PATH . '/public/' . $path;
        if (is_file($full)) {
            @unlink($full);
        }
    }

    public function update(): void
    {
        $user = Auth::user();
        $name = input_str('name', 150);
        $email = mb_strtolower(input_str('email', 190));
        $role = input_str('role', 20);

        if ($name === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            toast('Informe nome e um e-mail corporativo válido.', 'warning');
            redirect('/painel/perfil');
        }

        if (User::emailTaken($email, (int) $user['id'])) {
            toast('Este e-mail já está em uso por outro usuário.', 'error');
            redirect('/painel/perfil');
        }

        User::updateProfile((int) $user['id'], [
            'name' => $name,
            'email' => $email,
            'phone' => input_str('phone', 40),
            'role' => array_key_exists($role, User::ROLES) ? $role : $user['role'],
            'department' => input_str('department', 150),
            'notify_email_new_lead' => input('notify_email_new_lead') !== null ? 1 : 0,
            'notify_whatsapp_alerts' => input('notify_whatsapp_alerts') !== null ? 1 : 0,
            'notify_browser_sound' => input('notify_browser_sound') !== null ? 1 : 0,
        ]);

        toast('Perfil de usuário atualizado com sucesso!');
        redirect('/painel/perfil');
    }

    public function password(): void
    {
        $user = Auth::user();
        $current = (string) ($_POST['current_password'] ?? '');
        $new = (string) ($_POST['new_password'] ?? '');
        $confirm = (string) ($_POST['confirm_password'] ?? '');

        if ($current === '') {
            toast('Digite a senha atual.', 'warning');
            redirect('/painel/perfil');
        }
        if (mb_strlen($new) < 6) {
            toast('A nova senha deve possuir pelo menos 6 caracteres.', 'warning');
            redirect('/painel/perfil');
        }
        if ($new !== $confirm) {
            toast('A confirmação de senha não confere.', 'error');
            redirect('/painel/perfil');
        }

        $full = User::findByEmail((string) $user['email']);
        if ($full === null || !password_verify($current, (string) $full['password_hash'])) {
            toast('A senha atual está incorreta.', 'error');
            redirect('/painel/perfil');
        }

        User::setPassword((int) $user['id'], $new);
        session_regenerate_id(true);

        toast('Senha alterada com sucesso!', 'success');
        redirect('/painel/perfil');
    }
}
