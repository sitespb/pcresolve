<?php
/**
 * @var int    $code
 * @var string $title
 */
$isNotFound = $code === 404;
?>
<div class="bg-white py-20 lg:py-28">
  <div class="max-w-2xl mx-auto px-4 sm:px-8 text-center space-y-6">
    <div class="w-16 h-16 rounded-2xl bg-[#F5F6F8] border border-[#E4E7EC] flex items-center justify-center text-[#D71920] mx-auto">
      <?= icon($isNotFound ? 'Search' : 'AlertTriangle', 'w-8 h-8') ?>
    </div>
    <div>
      <span class="text-xs font-bold uppercase tracking-wider text-[#D71920]">Erro <?= (int) $code ?></span>
      <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-[#202124] mt-2"><?= e($title) ?></h1>
      <p class="text-sm text-[#697386] mt-3 leading-relaxed">
        <?= $isNotFound
            ? 'O endereço acessado não existe ou o serviço não está mais disponível no catálogo.'
            : 'Não foi possível concluir a solicitação. Tente novamente em instantes.' ?>
      </p>
    </div>
    <div class="flex flex-wrap items-center justify-center gap-3">
      <a href="/" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white font-semibold text-xs transition-colors shadow-xs">
        <?= icon('ArrowLeft', 'w-4 h-4') ?>
        <span>Voltar ao início</span>
      </a>
      <a href="/servicos" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-white border border-[#E4E7EC] hover:border-[#D71920] hover:text-[#D71920] text-[#202124] font-semibold text-xs transition-colors">
        <span>Ver catálogo de serviços</span>
      </a>
    </div>
  </div>
</div>
