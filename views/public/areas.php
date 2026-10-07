<?php /** @var list<array<string, mixed>> $cities */ ?>
<div class="bg-white py-12 lg:py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-8 space-y-16">
    <div class="max-w-3xl">
      <span class="text-xs font-bold uppercase tracking-wider text-[#D71920]">Cobertura Regional</span>
      <h1 class="font-heading font-extrabold text-3xl sm:text-4xl text-[#202124] tracking-tight mt-2">
        Assistência Técnica na Região Metropolitana de João Pessoa
      </h1>
      <p class="text-sm sm:text-base text-[#697386] mt-3 leading-relaxed">
        Atendimento presencial em laboratório, suporte corporativo in-loco para empresas e serviço de coleta e entrega (Leva e Traz) em domicílio.
      </p>
    </div>

    <div class="bg-[#F5F6F8] rounded-2xl p-6 sm:p-8 border border-[#E4E7EC] flex flex-col md:flex-row items-center justify-between gap-6 shadow-xs">
      <div class="flex items-start sm:items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-white border border-[#E4E7EC] flex items-center justify-center text-[#D71920] shrink-0 shadow-xs">
          <?= icon('Truck', 'w-7 h-7 text-[#D71920]') ?>
        </div>
        <div>
          <h2 class="font-heading font-bold text-lg text-[#202124]">Serviço de Coleta &amp; Entrega Técnica (Leva e Traz)</h2>
          <p class="text-xs sm:text-sm text-[#697386] mt-1 leading-relaxed max-w-2xl">
            Não pode se deslocar até o laboratório? Coletamos seu notebook ou computador em casa ou no escritório com termo de recebimento lacrado e levamos até nossa bancada ESD. Após os testes de estabilidade, entregamos de volta.
          </p>
        </div>
      </div>
      <a href="<?= e(company_wa('Olá! Gostaria de agendar o serviço de Leva e Traz para meu equipamento.')) ?>" target="_blank" rel="noopener noreferrer" class="px-6 py-3 rounded-xl bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-bold shrink-0 transition-colors shadow-xs">
        Agendar Coleta
      </a>
    </div>

    <div class="space-y-6">
      <h2 class="font-heading font-bold text-xl text-[#202124]">Cidades e Polos de Atendimento</h2>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($cities as $city): ?>
          <div class="bg-white border border-[#E4E7EC] rounded-2xl p-6 flex flex-col justify-between hover:border-[#D71920]/40 hover:shadow-md transition-all group">
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                  <div class="w-8 h-8 rounded-lg bg-[#F5F6F8] flex items-center justify-center text-[#D71920]">
                    <?= icon('MapPin', 'w-4 h-4 text-[#D71920]') ?>
                  </div>
                  <h3 class="font-heading font-bold text-base text-[#202124]"><?= e($city['name']) ?></h3>
                </div>
                <?php if ($city['delivery_available']): ?>
                  <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">Leva e Traz Ativo</span>
                <?php endif; ?>
              </div>

              <div>
                <span class="text-[11px] font-bold text-[#697386] uppercase tracking-wider block mb-1">Bairros / Região:</span>
                <p class="text-xs text-[#202124] leading-relaxed"><?= e($city['coverage']) ?></p>
              </div>

              <p class="text-xs text-[#697386] pt-1"><?= e($city['notes']) ?></p>
            </div>

            <div class="pt-4 mt-4 border-t border-[#E4E7EC] flex items-center justify-between text-xs">
              <span class="text-[#697386]">
                Taxa Coleta:
                <strong class="text-[#202124]"><?= $city['delivery_fee'] == 0 ? 'Grátis acima de R$150' : 'R$ ' . number_format($city['delivery_fee'], 2, ',', '.') ?></strong>
              </span>
              <a href="<?= e(company_wa('Olá! Gostaria de consultar atendimento na cidade de ' . $city['name'] . '.')) ?>" target="_blank" rel="noopener noreferrer" class="text-[#D71920] font-semibold hover:underline flex items-center gap-1">
                <span>Consultar</span>
                <?= icon('ArrowRight', 'w-3 h-3') ?>
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
