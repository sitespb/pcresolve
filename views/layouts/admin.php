<?php
/**
 * Layout do painel administrativo (equivalente ao <AdminLayout />).
 *
 * Variáveis definidas pela view da página:
 * @var string      $content
 * @var string      $menu          Item ativo do menu lateral
 * @var string      $adminTitle
 * @var string|null $adminSubtitle
 * @var string|null $adminAction   HTML dos botões do cabeçalho
 */
use App\Models\Lead;

$user = auth_user();
$pendingLeadsCount = Lead::pendingCount();
$menu = $menu ?? '';

$menuItems = [
    ['key' => 'dashboard', 'href' => '/painel', 'label' => 'Visão Geral (Dashboard)', 'icon' => 'LayoutDashboard'],
    ['key' => 'analytics', 'href' => '/painel/analytics', 'label' => 'Relatórios & Estatísticas', 'icon' => 'BarChart3'],
    ['key' => 'solicitacoes', 'href' => '/painel/solicitacoes', 'label' => 'Ordens de Serviço & Leads', 'icon' => 'ClipboardList', 'badge' => $pendingLeadsCount > 0 ? $pendingLeadsCount : null],
    ['key' => 'servicos', 'href' => '/painel/servicos', 'label' => 'Catálogo de Serviços', 'icon' => 'Wrench'],
    ['key' => 'cidades', 'href' => '/painel/cidades', 'label' => 'Cidades & Leva e Traz', 'icon' => 'MapPin'],
    ['key' => 'depoimentos', 'href' => '/painel/depoimentos', 'label' => 'Depoimentos & Avaliações', 'icon' => 'MessageSquare'],
    ['key' => 'faq', 'href' => '/painel/faq', 'label' => 'Perguntas Frequentes (FAQ)', 'icon' => 'HelpCircle'],
    ['key' => 'biblioteca', 'href' => '/painel/biblioteca', 'label' => 'Biblioteca de Mídia', 'icon' => 'Image'],
    ['key' => 'perfil', 'href' => '/painel/perfil', 'label' => 'Perfil do Usuário', 'icon' => 'User'],
    ['key' => 'configuracoes', 'href' => '/painel/configuracoes', 'label' => 'Configurações do Sistema', 'icon' => 'Settings'],
];
?>
<!doctype html>
<html lang="pt-BR">
<head>
<?= partial('head', ['pageTitle' => $pageTitle ?? null, 'noindex' => true]) ?>
</head>
<body class="bg-white text-[#202124] antialiased selection:bg-[#D71920] selection:text-white">
<?= partial('toasts') ?>
<div x-data="{ sidebarOpen: false }" class="min-h-screen bg-[#F5F6F8] flex flex-col md:flex-row text-[#202124]">
  <!-- Barra superior mobile -->
  <div class="md:hidden bg-white border-b border-[#E4E7EC] px-4 py-3 flex items-center justify-between z-30 print:hidden">
    <a href="/painel" aria-label="Painel"><?= partial("brand-logo", ["class" => "h-8", "eager" => true]) ?></a>
    <div class="flex items-center gap-2">
      <a href="/" target="_blank" rel="noopener" class="p-2 text-[#697386] hover:text-[#D71920]" title="Ver Site Público (nova janela)">
        <?= icon('Globe', 'w-5 h-5') ?>
      </a>
      <button type="button" @click="sidebarOpen = !sidebarOpen" class="p-2 text-[#202124] rounded-lg border border-[#E4E7EC]" aria-label="Abrir menu lateral">
        <span x-show="!sidebarOpen"><?= icon('Menu', 'w-5 h-5') ?></span>
        <span x-show="sidebarOpen" x-cloak><?= icon('X', 'w-5 h-5') ?></span>
      </button>
    </div>
  </div>

  <!-- Menu lateral -->
  <aside
    class="fixed md:sticky top-0 inset-y-0 left-0 z-40 w-64 bg-white border-r border-[#E4E7EC] flex flex-col justify-between transition-transform duration-300 max-md:-translate-x-full md:translate-x-0 print:hidden"
    :class="sidebarOpen ? 'translate-x-0! shadow-2xl' : ''"
  >
    <div class="flex flex-col flex-1 overflow-y-auto">
      <div class="p-5 border-b border-[#E4E7EC]">
        <a href="/painel" aria-label="Painel" class="block"><?= partial('brand-logo', ['class' => 'h-10', 'eager' => true]) ?></a>
        <div class="mt-2.5 flex items-center gap-2 text-[10px] font-bold uppercase tracking-wider text-[#697386]">
          <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
          <span>Painel de Gestão Técnica</span>
        </div>
      </div>

      <nav class="p-3 space-y-1">
        <span class="text-[10px] font-bold text-[#697386] uppercase tracking-wider px-3 py-1.5 block">Menu Administrativo</span>
        <?php foreach ($menuItems as $item): $active = $menu === $item['key']; ?>
          <a href="<?= $item['href'] ?>" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition-all cursor-pointer <?= $active ? 'bg-[#D71920] text-white shadow-xs' : 'text-[#202124] hover:bg-[#F5F6F8] hover:text-[#D71920]' ?>"<?= $active ? ' aria-current="page"' : '' ?>>
            <div class="flex items-center gap-2.5">
              <span class="<?= $active ? 'text-white' : 'text-[#697386]' ?>"><?= icon($item['icon'], 'w-4 h-4') ?></span>
              <span><?= e($item['label']) ?></span>
            </div>
            <?php if (!empty($item['badge'])): ?>
              <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold <?= $active ? 'bg-white text-[#D71920]' : 'bg-[#D71920] text-white' ?>"><?= (int) $item['badge'] ?></span>
            <?php endif; ?>
          </a>
        <?php endforeach; ?>
      </nav>

      <div class="p-3 pt-2">
        <a href="/" target="_blank" rel="noopener" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-[#697386] hover:text-[#202124] hover:bg-[#F5F6F8] border border-dashed border-[#E4E7EC] transition-colors cursor-pointer">
          <?= icon('Globe', 'w-4 h-4 text-[#D71920]') ?>
          <span>Ver Site Público</span>
          <?= icon('ExternalLink', 'w-3 h-3 ml-auto text-neutral-400') ?>
        </a>
      </div>
    </div>

    <!-- Cartão do usuário -->
    <div class="p-4 border-t border-[#E4E7EC] bg-white">
      <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-full bg-neutral-200 overflow-hidden border border-[#E4E7EC] shrink-0 flex items-center justify-center text-xs font-bold text-[#697386]">
          <?php if (!empty($user['avatar'])): ?>
            <img src="<?= e(image_url($user['avatar'])) ?>" alt="<?= e($user['name']) ?>" class="w-full h-full object-cover">
          <?php else: ?>
            <?= e(mb_strtoupper(mb_substr((string) $user['name'], 0, 1))) ?>
          <?php endif; ?>
        </div>
        <div class="flex flex-col min-w-0 flex-1">
          <span class="text-xs font-bold text-[#202124] truncate"><?= e($user['name']) ?></span>
          <span class="text-[10px] text-[#697386] truncate"><?= $user['role'] === 'superadmin' ? 'Superadministrador' : 'Técnico' ?></span>
        </div>
        <a href="/painel/perfil" class="text-[#697386] hover:text-[#D71920] p-1 rounded transition-colors" title="Acessar Perfil">
          <?= icon('Settings', 'w-4 h-4') ?>
        </a>
        <form method="post" action="/painel/logout" class="contents">
          <?= csrf_field() ?>
          <button type="submit" class="text-[#697386] hover:text-[#D71920] p-1 rounded transition-colors cursor-pointer" title="Sair do painel">
            <?= icon('LogOut', 'w-4 h-4') ?>
          </button>
        </form>
      </div>
    </div>
  </aside>

  <!-- Conteúdo -->
  <main class="flex-1 flex flex-col min-w-0">
    <header class="h-16 bg-white border-b border-[#E4E7EC] px-6 flex items-center justify-between gap-4 sticky top-0 z-20">
      <div class="flex items-center gap-3 min-w-0">
        <div class="min-w-0">
          <h1 class="font-heading font-extrabold text-base sm:text-lg text-[#202124] tracking-tight truncate"><?= e($adminTitle ?? '') ?></h1>
          <?php if (!empty($adminSubtitle)): ?>
            <p class="text-[11px] text-[#697386] hidden sm:block truncate"><?= e($adminSubtitle) ?></p>
          <?php endif; ?>
        </div>
      </div>

      <div class="flex items-center gap-3 shrink-0 print:hidden">
        <?= $adminAction ?? '' ?>

        <button type="button" @click="toast('Nenhuma notificação crítica no momento.', 'info')" class="relative p-2 rounded-lg border border-[#E4E7EC] text-[#697386] hover:text-[#202124] hover:bg-[#F5F6F8] transition-colors" title="Notificações">
          <?= icon('Bell', 'w-4 h-4') ?>
          <?php if ($pendingLeadsCount > 0): ?>
            <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-[#D71920] text-white text-[9px] font-bold flex items-center justify-center"><?= $pendingLeadsCount ?></span>
          <?php endif; ?>
        </button>

        <a href="/" target="_blank" rel="noopener" title="Abrir o site em nova janela" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-[#E4E7EC] bg-[#F5F6F8] hover:bg-neutral-100 text-xs font-semibold text-[#202124] transition-colors">
          <?= icon('Globe', 'w-3.5 h-3.5 text-[#D71920]') ?>
          <span>Abrir Site</span>
        </a>
      </div>
    </header>

    <div class="p-4 sm:p-6 lg:p-8 space-y-6 flex-1">
      <?= $content ?>
    </div>
  </main>
</div>
</body>
</html>
