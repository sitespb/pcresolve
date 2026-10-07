<?php

declare(strict_types=1);

/*
 * Instalador da PC Resolve (linha de comando).
 *
 *   php bin/install.php                       Cria tabelas, popula dados iniciais e o usuário administrador
 *   php bin/install.php --admin-password=XYZ  Define a senha do administrador criado
 *   php bin/install.php --reset-password      Gera nova senha para o administrador existente
 *
 * Pode ser executado mais de uma vez: só popula tabelas vazias e nunca apaga dados.
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require dirname(__DIR__) . '/app/bootstrap.php';

use App\Models\User;
use App\Support\Database;
use App\Support\Seeder;

$options = getopt('', ['admin-email:', 'admin-password:', 'reset-password']);

function out(string $message): void
{
    fwrite(STDOUT, $message . PHP_EOL);
}

function random_password(int $length = 14): string
{
    $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789@#%';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $alphabet[random_int(0, strlen($alphabet) - 1)];
    }

    return $password;
}

out('PC Resolve — instalação');
out(str_repeat('-', 40));

try {
    Database::pdo();
} catch (PDOException $e) {
    out('[ERRO] Não foi possível conectar ao MySQL: ' . $e->getMessage());
    out('Confira DB_HOST, DB_DATABASE, DB_USERNAME e DB_PASSWORD no arquivo .env');
    exit(1);
}

out('[ok] Conectado ao banco "' . config('db.database') . '"');

Seeder::migrate();
out('[ok] Tabelas criadas/verificadas');

$seeded = Seeder::seedMissing();
out($seeded ? '[ok] Dados iniciais inseridos em: ' . implode(', ', $seeded) : '[ok] Tabelas já possuíam dados (nada foi sobrescrito)');

foreach (['storage/logs', 'storage/sessions'] as $dir) {
    $path = BASE_PATH . '/' . $dir;
    if (!is_dir($path)) {
        mkdir($path, 0775, true);
    }
}

$seedUser = Seeder::data()['user'];
$email = mb_strtolower((string) ($options['admin-email'] ?? $seedUser['email']));
$existing = (int) Database::value('SELECT COUNT(*) FROM users');

if ($existing === 0) {
    $password = (string) ($options['admin-password'] ?? random_password());
    User::create(array_merge($seedUser, ['email' => $email]), $password);
    out('[ok] Usuário administrador criado');
    out('');
    out('    Painel:  /painel');
    out('    E-mail:  ' . $email);
    out('    Senha:   ' . $password);
    out('');
    out('    Guarde esta senha e altere-a em "Perfil do Usuário" após o primeiro acesso.');
} elseif (isset($options['reset-password'])) {
    $user = User::findByEmail($email) ?? User::find((int) Database::value('SELECT MIN(id) FROM users'));
    if ($user === null) {
        out('[ERRO] Nenhum usuário encontrado.');
        exit(1);
    }
    $password = (string) ($options['admin-password'] ?? random_password());
    User::setPassword((int) $user['id'], $password);
    Database::execute('DELETE FROM login_attempts');
    out('[ok] Nova senha definida para ' . $user['email'] . ': ' . $password);
} else {
    out('[ok] Usuário administrador já existe (use --reset-password para gerar nova senha)');
}

out(str_repeat('-', 40));
out('Instalação concluída.');
