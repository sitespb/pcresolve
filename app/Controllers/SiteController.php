<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\City;
use App\Models\Faq;
use App\Models\Service;
use App\Models\Testimonial;

final class SiteController
{
    public function home(): void
    {
        render('public/home', [
            'activeNav' => 'home',
            'services' => Service::active(8),
            'servicesCount' => Service::countActive(),
            'testimonials' => Testimonial::approved(2),
            'cities' => City::active(),
            'faqs' => Faq::all(),
            'leadSuccess' => LeadController::pullSuccess('home'),
        ]);
    }

    public function services(): void
    {
        render('public/services', [
            'activeNav' => 'servicos',
            'pageTitle' => 'Catálogo de Serviços de Informática | ' . setting('name'),
            'pageDescription' => 'Manutenção preventiva e corretiva para computadores e notebooks com bancada anti-estática ESD e garantia formal de 90 dias em João Pessoa.',
            'services' => Service::active(),
        ]);
    }

    public function service(string $slug): void
    {
        $service = Service::findActiveBySlug($slug);
        if ($service === null) {
            abort(404);
        }

        render('public/service', [
            'activeNav' => 'servico-detalhe',
            'pageTitle' => $service['title'] . ' em João Pessoa | ' . setting('name'),
            'pageDescription' => $service['short_desc'],
            'ogImage' => $service['image'],
            'service' => $service,
            'leadSuccess' => LeadController::pullSuccess('servico'),
        ]);
    }

    public function about(): void
    {
        render('public/about', [
            'activeNav' => 'sobre',
            'pageTitle' => 'Sobre a ' . setting('name') . ' | Assistência Técnica em João Pessoa',
            'pageDescription' => 'Tecnologia, rigor técnico e transparência na Grande João Pessoa: bancada ESD, garantia formal de 90 dias e proteção de dados.',
        ]);
    }

    public function areas(): void
    {
        render('public/areas', [
            'activeNav' => 'areas',
            'pageTitle' => 'Cidades Atendidas na Grande João Pessoa | ' . setting('name'),
            'pageDescription' => 'Atendimento em João Pessoa, Cabedelo, Bayeux, Santa Rita, Conde e região, com serviço de coleta e entrega (Leva e Traz).',
            'cities' => City::active(),
        ]);
    }

    public function contact(): void
    {
        render('public/contact', [
            'activeNav' => 'contato',
            'pageTitle' => 'Contato e Orçamento | ' . setting('name'),
            'pageDescription' => 'Fale com a ' . setting('name') . ': telefone, WhatsApp técnico, e-mail e formulário de solicitação de atendimento em João Pessoa.',
            'leadSuccess' => LeadController::pullSuccess('contato'),
        ]);
    }

    public function terms(): void
    {
        render('public/legal', [
            'activeNav' => 'termos',
            'type' => 'termos',
            'pageTitle' => 'Termos de Uso | ' . setting('name'),
        ]);
    }

    public function privacy(): void
    {
        render('public/legal', [
            'activeNav' => 'privacidade',
            'type' => 'privacidade',
            'pageTitle' => 'Política de Privacidade (LGPD) | ' . setting('name'),
        ]);
    }

    public function sitemap(): void
    {
        $paths = ['/', '/servicos', '/sobre', '/areas-atendidas', '/contato', '/termos', '/privacidade'];
        foreach (Service::active() as $service) {
            $paths[] = '/servicos/' . $service['slug'];
        }

        header('Content-Type: application/xml; charset=UTF-8');
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($paths as $path) {
            echo '  <url><loc>' . e(absolute_url($path)) . '</loc></url>' . "\n";
        }
        echo '</urlset>' . "\n";
    }

    /** Manifesto do app (ícones do favicon); o nome acompanha o "Nome Fantasia" das configurações. */
    public function manifest(): void
    {
        header('Content-Type: application/manifest+json; charset=UTF-8');
        header('Cache-Control: public, max-age=86400');

        echo json_encode([
            'name' => setting('name', 'PC Resolve'),
            'short_name' => setting('name', 'PC Resolve'),
            'icons' => [
                ['src' => '/web-app-manifest-192x192.png', 'sizes' => '192x192', 'type' => 'image/png', 'purpose' => 'maskable'],
                ['src' => '/web-app-manifest-512x512.png', 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'maskable'],
            ],
            'start_url' => '/',
            'theme_color' => '#D71920',
            'background_color' => '#ffffff',
            'display' => 'standalone',
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function robots(): void
    {
        header('Content-Type: text/plain; charset=UTF-8');
        // O painel não é listado aqui de propósito (o robots.txt é público e revelaria o endereço);
        // ele já envia "X-Robots-Tag: noindex" e <meta name="robots" content="noindex">.
        echo "User-agent: *\n";
        echo "Allow: /\n";
        echo "\n";
        echo 'Sitemap: ' . absolute_url('/sitemap.xml') . "\n";
    }
}
