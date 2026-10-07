<?php $whatsappUrl = company_wa('Olá! Gostaria de saber mais sobre os serviços da PC Resolve.'); ?>
<div class="bg-white py-12 lg:py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-8 space-y-16">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
      <div class="lg:col-span-7 space-y-6">
        <span class="text-xs font-bold uppercase tracking-wider text-[#D71920]">Institucional</span>
        <h1 class="font-heading font-extrabold text-3xl sm:text-4xl text-[#202124] tracking-tight">
          Tecnologia, rigor técnico e transparência na Grande João Pessoa
        </h1>
        <p class="text-base text-[#697386] leading-relaxed">
          A <strong><?= e(setting('name')) ?></strong> nasceu da necessidade de entregar aos paraibanos um serviço de informática que rompesse com o amadorismo e com diagnósticos nebulosos. Acreditamos que a confiança se constrói com bancadas organizadas, instrumentação de ponta e respeito irrestrito aos dados dos nossos clientes.
        </p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
          <div class="p-4 rounded-xl bg-[#F5F6F8] border border-[#E4E7EC]">
            <?= icon('ShieldCheck', 'w-6 h-6 text-[#D71920] mb-2') ?>
            <h4 class="font-heading font-bold text-sm text-[#202124]">Bancada Técnica ESD</h4>
            <p class="text-xs text-[#697386] mt-1">Proteção anti-estática em todas as etapas para evitar danos ocultos a processadores e placas-mãe.</p>
          </div>
          <div class="p-4 rounded-xl bg-[#F5F6F8] border border-[#E4E7EC]">
            <?= icon('Award', 'w-6 h-6 text-[#D71920] mb-2') ?>
            <h4 class="font-heading font-bold text-sm text-[#202124]">Garantia Formal de 90 Dias</h4>
            <p class="text-xs text-[#697386] mt-1">Emissão de Ordem de Serviço com checklist de entrada e saída conforme o CDC art. 26.</p>
          </div>
        </div>
      </div>

      <div class="lg:col-span-5">
        <div class="rounded-2xl overflow-hidden border border-[#E4E7EC] shadow-xl bg-neutral-100">
          <img src="/assets/img/bancada-diagnostico.jpg" alt="Ambiente laboratorial de manutenção técnica da PC Resolve" class="w-full aspect-[4/3] object-cover">
        </div>
      </div>
    </div>

    <div class="bg-[#F5F6F8] rounded-3xl p-8 sm:p-12 border border-[#E4E7EC]">
      <div class="max-w-3xl mb-8">
        <span class="text-xs font-bold uppercase tracking-wider text-[#D71920]">Nossos Pilares</span>
        <h2 class="font-heading font-extrabold text-2xl sm:text-3xl text-[#202124] mt-1">Como trabalhamos em cada equipamento</h2>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php foreach ([
            ['1', 'Diagnóstico Honesto', 'Antes de condenar uma placa ou cobrar por peças que não necessitam de troca, realizamos testes elétricos detalhados em bancada. Se for um capacitor ou fusível em curto, nós consertamos o componente.'],
            ['2', 'Proteção de Dados &amp; LGPD', 'Fotos, documentos de trabalho, processos jurídicos e prontuários médicos são tratados com sigilo absoluto. Não vasculhamos arquivos e seguimos diretrizes formais de privacidade.'],
            ['3', 'Materiais de Primeira Linha', 'Utilizamos pastas térmicas internacionais com alto índice de condutividade (Arctic MX-4 e MX-6), álcool isopropílico 99.8% e soldas com fluxo profissional para assegurar durabilidade extrema.'],
        ] as [$number, $heading, $text]): ?>
          <div class="bg-white p-6 rounded-2xl border border-[#E4E7EC] space-y-3">
            <div class="w-10 h-10 rounded-xl bg-[#F5F6F8] flex items-center justify-center text-[#D71920] font-bold"><?= $number ?></div>
            <h3 class="font-heading font-bold text-base text-[#202124]"><?= $heading ?></h3>
            <p class="text-xs text-[#697386] leading-relaxed"><?= $text ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="space-y-6">
      <div class="text-center max-w-2xl mx-auto">
        <span class="text-xs font-bold uppercase tracking-wider text-[#D71920]">Infraestrutura</span>
        <h2 class="font-heading font-extrabold text-2xl sm:text-3xl text-[#202124] mt-1">Laboratório Técnico em João Pessoa</h2>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php foreach ([
            ['pasta-termica.jpg', 'Aplicação de pasta térmica na placa-mãe', 'Substituição Térmica Criteriosa', 'Compostos térmicos premium para refrigeração máxima'],
            ['workstation.jpg', 'Montagem de workstation e organização de cabos', 'Cable Management &amp; Air-Flow', 'Montagem limpa e fluxo de ar calibrado para desktops'],
            ['bancada-diagnostico.jpg', 'Reparo de componentes e diagnóstico de notebooks', 'Diagnóstico de Dobradiças e Telas', 'Recuperação estrutural sem danificar carcaças'],
        ] as [$image, $alt, $heading, $text]): ?>
          <div class="rounded-2xl overflow-hidden border border-[#E4E7EC] bg-neutral-100 group">
            <img src="/assets/img/<?= $image ?>" alt="<?= $alt ?>" class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
            <div class="p-4 bg-white border-t border-[#E4E7EC]">
              <h4 class="font-heading font-bold text-xs text-[#202124]"><?= $heading ?></h4>
              <p class="text-[11px] text-[#697386] mt-0.5"><?= $text ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="bg-[#D71920] rounded-3xl p-8 sm:p-12 text-white flex flex-col md:flex-row items-center justify-between gap-6 shadow-xl">
      <div class="space-y-2 text-center md:text-left">
        <h3 class="font-heading font-extrabold text-2xl sm:text-3xl">Agende uma visita ou traga seu equipamento</h3>
        <p class="text-white/80 text-sm max-w-xl">Atendimento com hora marcada ou serviço de coleta e entrega (Leva e Traz) na Grande João Pessoa.</p>
      </div>
      <div class="flex flex-wrap items-center gap-3 shrink-0">
        <a href="<?= e($whatsappUrl) ?>" target="_blank" rel="noopener noreferrer" class="px-6 py-3.5 rounded-xl bg-white text-[#D71920] hover:bg-neutral-100 font-bold text-xs shadow-md transition-colors">
          Falar no WhatsApp
        </a>
        <a href="/contato" class="px-6 py-3.5 rounded-xl bg-[#A90F17] hover:bg-black/30 text-white font-bold text-xs transition-colors cursor-pointer">
          Enviar Formulário
        </a>
      </div>
    </div>
  </div>
</div>
