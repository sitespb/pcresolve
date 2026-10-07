<?php
/** @var list<array<string, mixed>> $services */
$index = array_map(fn (array $s) => [
    'category' => $s['category'],
    'text' => mb_strtolower($s['title'] . "\n" . $s['short_desc']),
], $services);
?>
<div class="bg-white py-12 lg:py-16" x-data="serviceFilter(<?= json_attr($index) ?>)">
  <div class="max-w-7xl mx-auto px-4 sm:px-8">
    <div class="max-w-3xl mb-12">
      <div class="inline-flex items-center gap-2 text-xs font-semibold text-[#D71920] mb-2 uppercase tracking-wider">
        <span>Especialidades Técnicas</span>
      </div>
      <h1 class="font-heading font-extrabold text-3xl sm:text-4xl text-[#202124] tracking-tight">Catálogo Completo de Serviços de Informática</h1>
      <p class="text-sm sm:text-base text-[#697386] mt-3 leading-relaxed">
        Manutenção preventiva e corretiva para computadores e notebooks com equipamentos calibrados, bancada anti-estática ESD e garantia formal de 90 dias.
      </p>
    </div>

    <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4 pb-8 mb-8 border-b border-[#E4E7EC]">
      <div class="flex items-center gap-1.5 p-1 bg-[#F5F6F8] rounded-xl border border-[#E4E7EC] overflow-x-auto">
        <?php foreach (service_categories() as $key => $label): ?>
          <button
            type="button"
            @click="category = '<?= e($key) ?>'"
            class="px-3.5 py-2 text-xs font-semibold rounded-lg transition-colors whitespace-nowrap cursor-pointer"
            :class="category === '<?= e($key) ?>' ? 'bg-white text-[#D71920] shadow-xs' : 'text-[#697386] hover:text-[#202124]'"
          ><?= e($label) ?></button>
        <?php endforeach; ?>
      </div>

      <div class="relative min-w-[260px]">
        <?= icon('Search', 'w-4 h-4 text-[#697386] absolute left-3.5 top-1/2 -translate-y-1/2') ?>
        <input type="text" x-model="query" placeholder="Buscar serviço (ex: SSD, tela, placa)..." aria-label="Buscar serviço" class="w-full pl-9 pr-3.5 py-2 text-xs rounded-xl border border-[#E4E7EC] bg-white text-[#202124] focus:outline-none focus:border-[#D71920]">
      </div>
    </div>

    <div x-show="visibleCount === 0" x-cloak class="text-center py-16 bg-[#F5F6F8] rounded-2xl border border-[#E4E7EC]">
      <p class="text-sm font-semibold text-[#202124]">Nenhum serviço encontrado com esse termo.</p>
      <p class="text-xs text-[#697386] mt-1">Tente pesquisar por outra palavra-chave ou limpe os filtros.</p>
      <button type="button" @click="category = 'todos'; query = ''" class="mt-4 px-4 py-2 rounded-lg bg-white border border-[#E4E7EC] text-xs font-semibold text-[#D71920] hover:bg-neutral-50 transition-colors">
        Limpar filtros
      </button>
    </div>

    <div x-show="visibleCount > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($services as $i => $service): ?>
        <div x-show="isVisible(<?= $i ?>)" class="bg-[#F5F6F8] border border-[#E4E7EC] rounded-2xl overflow-hidden flex flex-col justify-between hover:border-[#D71920]/40 hover:shadow-md transition-all group">
          <div>
            <div class="h-48 w-full overflow-hidden bg-neutral-200 relative">
              <img src="<?= e(image_url($service['image'])) ?>" alt="<?= e($service['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
              <div class="absolute top-3 left-3 w-8 h-8 rounded-lg bg-white/95 backdrop-blur-xs flex items-center justify-center shadow-xs">
                <?= service_icon($service['icon_name'], 'w-5 h-5 text-[#D71920]') ?>
              </div>
              <div class="absolute bottom-3 right-3 px-2.5 py-1 rounded-md bg-white/95 text-[11px] font-bold text-[#202124] shadow-xs">
                A partir de R$ <?= e(js_number($service['price_starting_at'])) ?>
              </div>
            </div>

            <div class="p-6">
              <h2 class="font-heading font-bold text-base text-[#202124] mb-2 leading-snug"><?= e($service['title']) ?></h2>
              <p class="text-xs text-[#697386] leading-relaxed mb-4"><?= e($service['short_desc']) ?></p>

              <div class="space-y-1.5 pt-2 border-t border-[#E4E7EC]/60 text-[11px] text-[#697386]">
                <div class="flex items-center gap-2">
                  <?= icon('Clock', 'w-3.5 h-3.5 text-[#D71920]') ?>
                  <span>Prazo médio: <strong class="text-[#202124]"><?= e($service['turnaround_time']) ?></strong></span>
                </div>
                <div class="flex items-center gap-2">
                  <?= icon('ShieldCheck', 'w-3.5 h-3.5 text-[#25D366]') ?>
                  <span>Garantia: <strong class="text-[#202124]"><?= (int) $service['warranty_days'] ?> dias</strong></span>
                </div>
              </div>
            </div>
          </div>

          <div class="p-6 pt-0 flex items-center justify-between gap-3">
            <a href="/servicos/<?= e($service['slug']) ?>" class="flex-1 py-2.5 px-3 rounded-lg bg-white border border-[#E4E7EC] hover:border-[#D71920] hover:text-[#D71920] text-xs font-semibold text-[#202124] transition-colors flex items-center justify-center gap-1.5 cursor-pointer">
              <span>Ver detalhes</span>
              <?= icon('ArrowRight', 'w-3.5 h-3.5') ?>
            </a>
            <a href="<?= e(company_wa('Olá! Gostaria de solicitar um orçamento para o serviço: ' . $service['title'])) ?>" target="_blank" rel="noopener noreferrer" class="py-2.5 px-4 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold transition-colors flex items-center justify-center gap-1.5">
              <span>Orçar</span>
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
