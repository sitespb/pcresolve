<?php
/** @var string|null $activeNav */
$navLinks = [
    ['label' => 'Início', 'key' => 'home', 'href' => '/'],
    ['label' => 'Serviços', 'key' => 'servicos', 'href' => '/servicos'],
    ['label' => 'Sobre', 'key' => 'sobre', 'href' => '/sobre'],
    ['label' => 'Cidades Atendidas', 'key' => 'areas', 'href' => '/areas-atendidas'],
    ['label' => 'Contato', 'key' => 'contato', 'href' => '/contato'],
];
$activeNav = $activeNav ?? '';
?>
<header x-data="{ mobileMenuOpen: false }" class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-[#E4E7EC] transition-all">
  <div class="max-w-7xl mx-auto px-4 sm:px-8 h-20 flex items-center justify-between gap-4">
    <a href="/" class="shrink-0 text-left focus:outline-none cursor-pointer" aria-label="Ir para a página inicial">
      <?= partial('brand-logo', ['class' => 'h-9 sm:h-11 lg:h-12', 'eager' => true]) ?>
    </a>

    <nav class="hidden lg:flex items-center gap-7 font-medium text-sm text-[#202124]">
      <?php foreach ($navLinks as $link): $isActive = $activeNav === $link['key']; ?>
        <a href="<?= $link['href'] ?>" class="transition-colors py-1 relative cursor-pointer <?= $isActive ? 'text-[#D71920] font-bold' : 'text-[#202124] hover:text-[#D71920]' ?>"<?= $isActive ? ' aria-current="page"' : '' ?>>
          <?= e($link['label']) ?>
          <?php if ($isActive): ?>
            <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-[#D71920] rounded-full"></span>
          <?php endif; ?>
        </a>
      <?php endforeach; ?>
    </nav>

    <div class="flex items-center gap-2.5 sm:gap-3">
      <a href="/contato" class="inline-flex items-center justify-center gap-2 px-4 sm:px-5 py-2.5 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white font-semibold text-xs sm:text-sm transition-all shadow-xs hover:shadow-md cursor-pointer whitespace-nowrap">
        <span>Solicitar atendimento</span>
        <?= icon('ArrowRight', 'w-4 h-4 hidden sm:inline') ?>
      </a>

      <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 rounded-lg text-[#202124] hover:bg-[#F5F6F8] border border-[#E4E7EC]" aria-label="Abrir menu de navegação" :aria-expanded="mobileMenuOpen.toString()">
        <span x-show="!mobileMenuOpen"><?= icon('Menu', 'w-5 h-5') ?></span>
        <span x-show="mobileMenuOpen" x-cloak><?= icon('X', 'w-5 h-5') ?></span>
      </button>
    </div>
  </div>

  <div x-show="mobileMenuOpen" x-cloak @click.outside="mobileMenuOpen = false" class="lg:hidden border-t border-[#E4E7EC] bg-white px-4 py-5 shadow-xl">
    <div class="flex flex-col space-y-3 font-medium text-sm">
      <?php foreach ($navLinks as $link): ?>
        <a href="<?= $link['href'] ?>" class="text-left px-3 py-2 rounded-lg transition-colors <?= $activeNav === $link['key'] ? 'bg-[#F5F6F8] text-[#D71920] font-bold' : 'text-[#202124] hover:bg-neutral-50' ?>">
          <?= e($link['label']) ?>
        </a>
      <?php endforeach; ?>

      <div class="pt-3 border-t border-[#E4E7EC] flex flex-col gap-2">
        <a href="/contato" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-[#D71920] text-white text-xs font-bold shadow-xs">
          <span>Solicitar Orçamento Online</span>
        </a>
      </div>
    </div>
  </div>
</header>
