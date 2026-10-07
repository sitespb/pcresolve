<?php

declare(strict_types=1);

use App\Models\Setting;
use App\Support\Auth;

/* -------------------------------------------------------------------------
 | Configuração e utilidades gerais
 * ---------------------------------------------------------------------- */

function config(string $key, mixed $default = null): mixed
{
    $value = $GLOBALS['__config'] ?? [];
    foreach (explode('.', $key) as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }
        $value = $value[$segment];
    }

    return $value;
}

function e(mixed $value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** JSON seguro para atributos HTML (ex.: x-data do Alpine). */
function json_attr(mixed $value): string
{
    return e(json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT));
}

/** JSON seguro para blocos <script>. */
function json_script(mixed $value): string
{
    return (string) json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP);
}

function now(): string
{
    return date('Y-m-d H:i:s');
}

function is_https(): bool
{
    if (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off') {
        return true;
    }

    return strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https'
        || (string) ($_SERVER['SERVER_PORT'] ?? '') === '443';
}

function client_ip(): string
{
    $ip = (string) ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');

    return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
}

function digits(?string $value): string
{
    return preg_replace('/\D+/', '', (string) $value) ?? '';
}

function slugify(string $text): string
{
    $text = mb_strtolower(trim($text), 'UTF-8');
    if (class_exists(Normalizer::class)) {
        $text = (string) Normalizer::normalize($text, Normalizer::FORM_D);
        $text = preg_replace('/\p{Mn}+/u', '', $text) ?? $text;
    } else {
        $text = strtr($text, ['á' => 'a', 'à' => 'a', 'â' => 'a', 'ã' => 'a', 'é' => 'e', 'ê' => 'e', 'í' => 'i', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ú' => 'u', 'ü' => 'u', 'ç' => 'c']);
    }
    $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';

    return trim($text, '-');
}

/* -------------------------------------------------------------------------
 | Requisição / resposta
 * ---------------------------------------------------------------------- */

function request_method(): string
{
    return strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
}

function request_path(): string
{
    $path = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';
    $path = '/' . trim(rawurldecode($path), '/');

    return $path;
}

function input(string $key, mixed $default = null): mixed
{
    $value = $_POST[$key] ?? $_GET[$key] ?? $default;

    return is_string($value) ? trim($value) : $value;
}

function input_str(string $key, int $max = 255, string $default = ''): string
{
    $value = input($key, $default);
    if (!is_string($value)) {
        return $default;
    }

    // Clientes antigos podem enviar Latin-1; converte em vez de descartar o texto.
    if (!mb_check_encoding($value, 'UTF-8')) {
        $value = mb_convert_encoding($value, 'UTF-8', 'Windows-1252');
    }

    // Remove caracteres de controle (exceto quebras de linha e tab).
    $value = preg_replace('/[^\P{C}\n\r\t]/u', '', $value) ?? '';

    return mb_substr($value, 0, $max, 'UTF-8');
}

function input_money(string $key): ?float
{
    $raw = input($key);
    if ($raw === null || $raw === '') {
        return null;
    }
    $raw = str_replace(' ', '', (string) $raw);
    // Aceita "1.234,56" (pt-BR) e "1234.56".
    if (str_contains($raw, ',')) {
        $raw = str_replace(['.', ','], ['', '.'], $raw);
    }

    return is_numeric($raw) ? round((float) $raw, 2) : null;
}

function url(string $path = '/', array $query = []): string
{
    $path = '/' . ltrim($path, '/');
    if ($query !== []) {
        $path .= '?' . http_build_query($query);
    }

    return $path;
}

function absolute_url(string $path = '/'): string
{
    $base = (string) config('app.url');
    if ($base === '') {
        $host = (string) ($_SERVER['HTTP_HOST'] ?? 'localhost');
        $base = (is_https() ? 'https://' : 'http://') . $host;
    }

    return rtrim($base, '/') . '/' . ltrim($path, '/');
}

/** URL de arquivo em public/assets com versão (cache busting). */
function asset(string $path): string
{
    $path = ltrim($path, '/');
    $file = BASE_PATH . '/public/assets/' . $path;
    $version = is_file($file) ? (string) filemtime($file) : '1';

    return '/assets/' . $path . '?v=' . $version;
}

/** Imagens podem ser caminhos locais (/assets/...) ou URLs externas. */
function image_url(?string $path): string
{
    $path = (string) $path;
    if ($path === '' || preg_match('#^https?://#i', $path)) {
        return $path;
    }

    return '/' . ltrim($path, '/');
}

function redirect(string $to, int $status = 302): never
{
    header('Location: ' . $to, true, $status);
    exit;
}

function previous_url(string $fallback = '/'): string
{
    $referer = (string) ($_SERVER['HTTP_REFERER'] ?? '');
    if ($referer === '') {
        return $fallback;
    }

    $host = parse_url($referer, PHP_URL_HOST);
    if ($host !== null && $host !== ($_SERVER['HTTP_HOST'] ?? null) && $host !== parse_url((string) ($_SERVER['HTTP_HOST'] ?? ''), PHP_URL_HOST)) {
        return $fallback;
    }

    $path = parse_url($referer, PHP_URL_PATH) ?: '/';
    $query = parse_url($referer, PHP_URL_QUERY);

    return $path . ($query ? '?' . $query : '');
}

function back(): never
{
    redirect(previous_url('/'));
}

function abort(int $code = 404): never
{
    http_response_code($code);

    $titles = [
        403 => 'Acesso negado',
        404 => 'Página não encontrada',
        405 => 'Método não permitido',
        419 => 'Sessão expirada',
        500 => 'Erro interno',
    ];

    try {
        echo view('errors/error', [
            'code' => $code,
            'title' => $titles[$code] ?? 'Erro',
            'pageTitle' => ($titles[$code] ?? 'Erro') . ' | ' . setting('name', 'PC Resolve'),
        ], 'public');
    } catch (Throwable) {
        echo render_fallback_error($code, $titles[$code] ?? 'Erro');
    }

    exit;
}

function render_fallback_error(int $code, string $title): string
{
    return '<!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">'
        . '<title>' . e($title) . ' | PC Resolve</title></head>'
        . '<body style="font-family:Inter,system-ui,sans-serif;background:#F5F6F8;color:#202124;display:flex;min-height:100vh;align-items:center;justify-content:center;margin:0">'
        . '<div style="text-align:center;padding:2rem"><p style="color:#D71920;font-weight:800;font-size:3rem;margin:0">' . $code . '</p>'
        . '<h1 style="font-size:1.25rem;margin:.5rem 0">' . e($title) . '</h1>'
        . '<p style="color:#697386;font-size:.875rem">Tente novamente em instantes.</p>'
        . '<a href="/" style="display:inline-block;margin-top:1rem;background:#D71920;color:#fff;padding:.6rem 1.2rem;border-radius:.5rem;text-decoration:none;font-size:.8rem;font-weight:600">Voltar ao início</a></div></body></html>';
}

/* -------------------------------------------------------------------------
 | Sessão: CSRF, toasts, flash e old input
 * ---------------------------------------------------------------------- */

function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }

    return (string) $_SESSION['_csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
}

/** Equivalente ao showToast() da versão React; exibido no próximo carregamento de página. */
function toast(string $text, string $type = 'success'): void
{
    $_SESSION['_toasts'][] = ['text' => $text, 'type' => $type];
}

/** @return list<array{text: string, type: string}> */
function pull_toasts(): array
{
    $toasts = $_SESSION['_toasts'] ?? [];
    unset($_SESSION['_toasts']);

    return $toasts;
}

function flash(string $key, mixed $value): void
{
    $_SESSION['_flash'][$key] = $value;
}

function flash_pull(string $key, mixed $default = null): mixed
{
    $value = $_SESSION['_flash'][$key] ?? $default;
    unset($_SESSION['_flash'][$key]);

    return $value;
}

/** @param array<string, mixed> $data */
function with_old(array $data): void
{
    unset($data['_token']);
    $_SESSION['_old'] = $data;
}

function old(string $key, mixed $default = ''): mixed
{
    return $_SESSION['_old'][$key] ?? $default;
}

function has_old(): bool
{
    return !empty($_SESSION['_old']);
}

/* -------------------------------------------------------------------------
 | Views
 * ---------------------------------------------------------------------- */

/** @param array<string, mixed> $data */
function view(string $template, array $data = [], ?string $layout = null): string
{
    $__file = BASE_PATH . '/views/' . $template . '.php';
    $__layout = $layout !== null ? BASE_PATH . '/views/layouts/' . $layout . '.php' : null;

    if (!is_file($__file)) {
        throw new RuntimeException("View não encontrada: $template");
    }

    return (static function () use ($__file, $__layout, $data): string {
        extract($data, EXTR_SKIP);

        $__level = ob_get_level();
        ob_start();
        try {
            include $__file;
            $content = (string) ob_get_clean();

            if ($__layout === null) {
                return $content;
            }

            ob_start();
            include $__layout;

            return (string) ob_get_clean();
        } catch (Throwable $e) {
            while (ob_get_level() > $__level) {
                ob_end_clean();
            }
            throw $e;
        }
    })();
}

/** Renderiza uma página completa e descarta o old input já exibido. */
function render(string $template, array $data = [], string $layout = 'public'): void
{
    echo view($template, $data, $layout);
    unset($_SESSION['_old']);
}

/** @param array<string, mixed> $data */
function partial(string $name, array $data = []): string
{
    return view('partials/' . $name, $data);
}

/**
 * Ícone Lucide inline (mesmos SVGs do lucide-react).
 * Aceita o nome do componente React (ex.: "CheckCircle2") ou kebab-case ("check-circle-2").
 */
function icon(string $name, string $class = '', array $attrs = []): string
{
    static $icons = null;
    $icons ??= require BASE_PATH . '/app/Support/icons.php';

    $key = strtolower(preg_replace(['/([A-Z])([A-Z][a-z])/', '/([a-z])([A-Z0-9])/', '/([0-9])([A-Z])/'], '$1-$2', $name) ?? $name);
    $inner = $icons[$key] ?? $icons['cpu'];

    $extra = '';
    foreach ($attrs + ['aria-hidden' => 'true'] as $attr => $value) {
        $extra .= ' ' . $attr . '="' . e($value) . '"';
    }

    return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-' . e($key) . ($class !== '' ? ' ' . e($class) : '') . '"' . $extra . '>' . $inner . '</svg>';
}

/** Glifo do WhatsApp usado nos botões da versão React. */
function whatsapp_svg(string $class): string
{
    return '<svg viewBox="0 0 24 24" class="' . e($class) . '" aria-hidden="true"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.299.144.347.491 1.2.534 1.288.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.861.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.144.39-.086s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.824z"/></svg>';
}

/* -------------------------------------------------------------------------
 | Configurações da empresa
 * ---------------------------------------------------------------------- */

function settings(): array
{
    return Setting::all();
}

function setting(string $key, string $default = ''): string
{
    $all = Setting::all();

    return isset($all[$key]) ? (string) $all[$key] : $default;
}

/** Link wa.me igual ao da versão React: https://wa.me/55{numero}?text={mensagem}. */
function wa_url(string $phone, string $message): string
{
    return 'https://wa.me/55' . digits($phone) . '?text=' . rawurlencode($message);
}

function company_wa(?string $message = null): string
{
    return wa_url(setting('whatsapp'), $message ?? setting('defaultWhatsappMessage'));
}

function auth_user(): ?array
{
    return Auth::user();
}

/** Usuário logado com perfil Superadministrador (controla os atalhos do painel no site público). */
function is_superadmin(): bool
{
    return Auth::check() && (Auth::user()['role'] ?? null) === 'superadmin';
}

/* -------------------------------------------------------------------------
 | Formatação (replica o comportamento do JavaScript original)
 * ---------------------------------------------------------------------- */

/** Equivale a Number.prototype.toFixed(2): "150.00". */
function fixed2(float|int|string|null $value): string
{
    return number_format((float) $value, 2, '.', '');
}

/** Equivale a interpolar um number no JS: 120 -> "120", 120.5 -> "120.5". */
function js_number(float|int|string|null $value): string
{
    $str = number_format((float) $value, 2, '.', '');

    return rtrim(rtrim($str, '0'), '.');
}

/** Equivale a toLocaleString('pt-BR'). */
function num_br(float|int|string|null $value, int $maxDecimals = 0): string
{
    $value = (float) $value;
    $decimals = 0;
    if ($maxDecimals > 0 && floor($value) != $value) {
        $decimals = $maxDecimals;
    }
    $formatted = number_format($value, $decimals, ',', '.');
    if ($decimals > 0) {
        $formatted = rtrim(rtrim($formatted, '0'), ',');
    }

    return $formatted;
}

function format_datetime(?string $value): string
{
    if (!$value) {
        return '';
    }
    $ts = strtotime($value);

    return $ts ? date('Y-m-d H:i', $ts) : (string) $value;
}

function format_date_br(?string $value): string
{
    if (!$value) {
        return '';
    }
    $ts = strtotime($value);

    return $ts ? date('d/m/Y', $ts) : (string) $value;
}

/* -------------------------------------------------------------------------
 | Domínio
 * ---------------------------------------------------------------------- */

/** @return array<string, array{label: string, class: string}> */
function lead_statuses(): array
{
    return [
        'pendente' => ['label' => 'Pendente', 'class' => 'bg-amber-50 text-amber-800 border-amber-200'],
        'em_diagnostico' => ['label' => 'Em Diagnóstico', 'class' => 'bg-blue-50 text-blue-800 border-blue-200'],
        'aguardando_aprovacao' => ['label' => 'Aguardando Aprovação', 'class' => 'bg-purple-50 text-purple-800 border-purple-200'],
        'em_execucao' => ['label' => 'Em Execução', 'class' => 'bg-orange-50 text-orange-800 border-orange-200'],
        'concluido' => ['label' => 'Concluído', 'class' => 'bg-emerald-50 text-emerald-800 border-emerald-200'],
        'entregue' => ['label' => 'Entregue', 'class' => 'bg-neutral-100 text-neutral-800 border-neutral-300'],
    ];
}

/** @return array{label: string, class: string} */
function lead_status_badge(string $status): array
{
    $map = lead_statuses();

    return $map[$status] ?? $map['pendente'];
}

/** @return list<string> */
function device_types(): array
{
    return ['notebook', 'desktop', 'all-in-one', 'macbook', 'corporativo', 'outro'];
}

/** @return array<string, string> */
function service_categories(): array
{
    return [
        'todos' => 'Todos os Serviços',
        'hardware' => 'Hardware & Placas',
        'software' => 'Sistemas & Segurança',
        'preventiva' => 'Limpeza & Térmica',
        'corporativo' => 'Empresarial & PJ',
    ];
}

/** Mesmo mapeamento de getServiceIcon() da versão React (padrão: Cpu). */
function service_icon(string $iconName, string $class): string
{
    $known = ['Laptop', 'Cpu', 'Zap', 'Activity', 'Terminal', 'Shield', 'Fan', 'Building'];

    return icon(in_array($iconName, $known, true) ? $iconName : 'Cpu', $class);
}

/** Opções de serviço do formulário público de orçamento. */
function lead_service_options(): array
{
    return [
        'Diagnóstico geral de falha',
        'Upgrade de SSD / Memória RAM',
        'Limpeza preventiva e troca de pasta térmica',
        'Reparo de placa-mãe / Não liga',
        'Formatação e instalação de sistema',
        'Troca de tela ou teclado de notebook',
        'Suporte técnico empresarial',
    ];
}
