<?php

declare(strict_types=1);

use App\Controllers\Admin\AnalyticsController;
use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\CityController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\FaqController;
use App\Controllers\Admin\ProfileController;
use App\Controllers\Admin\RequestController;
use App\Controllers\Admin\ServiceController;
use App\Controllers\Admin\SettingsController;
use App\Controllers\Admin\TestimonialController;
use App\Controllers\LeadController;
use App\Controllers\SiteController;
use App\Support\Router;

/** @var Router $router */

$router->group(['csrf'], function (Router $r): void {
    // Site público
    $r->get('/', [SiteController::class, 'home']);
    $r->get('/servicos', [SiteController::class, 'services']);
    $r->get('/servicos/{slug}', [SiteController::class, 'service']);
    $r->get('/sobre', [SiteController::class, 'about']);
    $r->get('/areas-atendidas', [SiteController::class, 'areas']);
    $r->get('/contato', [SiteController::class, 'contact']);
    $r->get('/termos', [SiteController::class, 'terms']);
    $r->get('/privacidade', [SiteController::class, 'privacy']);
    $r->post('/solicitar', [LeadController::class, 'store']);
    $r->get('/sitemap.xml', [SiteController::class, 'sitemap']);
    $r->get('/robots.txt', [SiteController::class, 'robots']);
    $r->get('/site.webmanifest', [SiteController::class, 'manifest']);

    // Acesso ao painel
    $r->get('/painel/login', [AuthController::class, 'showLogin'], ['guest']);
    $r->post('/painel/login', [AuthController::class, 'login'], ['guest']);
    $r->post('/painel/logout', [AuthController::class, 'logout']);

    // Painel administrativo (requer login)
    $r->group(['auth'], function (Router $r): void {
        $r->get('/painel', [DashboardController::class, 'index']);

        $r->get('/painel/analytics', [AnalyticsController::class, 'index']);
        $r->get('/painel/analytics/exportar', [AnalyticsController::class, 'export']);

        $r->get('/painel/solicitacoes', [RequestController::class, 'index']);
        $r->post('/painel/solicitacoes', [RequestController::class, 'store']);
        $r->post('/painel/solicitacoes/{id:\d+}', [RequestController::class, 'update']);

        $r->get('/painel/servicos', [ServiceController::class, 'index']);
        $r->post('/painel/servicos', [ServiceController::class, 'store']);
        $r->post('/painel/servicos/{id:\d+}', [ServiceController::class, 'update']);
        $r->post('/painel/servicos/{id:\d+}/status', [ServiceController::class, 'toggle']);
        $r->post('/painel/servicos/{id:\d+}/excluir', [ServiceController::class, 'destroy']);

        $r->get('/painel/cidades', [CityController::class, 'index']);
        $r->post('/painel/cidades/{id:\d+}/status', [CityController::class, 'toggle']);
        $r->post('/painel/cidades/{id:\d+}/taxa', [CityController::class, 'fee']);

        $r->get('/painel/depoimentos', [TestimonialController::class, 'index']);
        $r->post('/painel/depoimentos', [TestimonialController::class, 'store']);
        $r->post('/painel/depoimentos/{id:\d+}/aprovar', [TestimonialController::class, 'approve']);
        $r->post('/painel/depoimentos/{id:\d+}/ocultar', [TestimonialController::class, 'hide']);

        $r->get('/painel/faq', [FaqController::class, 'index']);
        $r->post('/painel/faq', [FaqController::class, 'store']);
        $r->post('/painel/faq/{id:\d+}', [FaqController::class, 'update']);
        $r->post('/painel/faq/{id:\d+}/excluir', [FaqController::class, 'destroy']);

        $r->get('/painel/perfil', [ProfileController::class, 'index']);
        $r->post('/painel/perfil', [ProfileController::class, 'update']);
        $r->post('/painel/perfil/senha', [ProfileController::class, 'password']);
        $r->post('/painel/perfil/foto', [ProfileController::class, 'avatar']);
        $r->post('/painel/perfil/foto/remover', [ProfileController::class, 'removeAvatar']);

        $r->get('/painel/configuracoes', [SettingsController::class, 'index']);
        $r->post('/painel/configuracoes', [SettingsController::class, 'update']);
        $r->post('/painel/configuracoes/restaurar', [SettingsController::class, 'reset']);
    });
});
