<?php
/**
 * @var list<array<string, mixed>> $services
 * @var int $servicesCount
 * @var list<array<string, mixed>> $testimonials
 * @var list<array<string, mixed>> $cities
 * @var list<array<string, mixed>> $faqs
 * @var string|null $leadSuccess
 */
$whatsappUrl = company_wa();
$oldCity = old('city', 'João Pessoa');
$oldDevice = old('device_type', 'notebook');
$oldService = old('service_type', 'Diagnóstico geral de falha');
?>
<main class="bg-white">
  <!-- 1. HERO -->
  <section class="relative bg-white pt-10 pb-16 lg:py-20 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-8">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
        <div class="lg:col-span-7 flex flex-col items-start space-y-6">
          <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#F5F6F8] border border-[#E4E7EC] text-xs font-semibold text-[#697386]">
            <span class="w-2 h-2 rounded-full bg-[#D71920] animate-pulse"></span>
            <span>Assistência técnica especializada • Grande João Pessoa</span>
          </div>

          <h1 class="font-heading font-extrabold text-3xl sm:text-4xl lg:text-[46px] leading-[1.18] tracking-tight text-[#202124]">
            Assistência Técnica de Computadores e Notebooks em João Pessoa
          </h1>

          <p class="text-base sm:text-lg text-[#697386] leading-relaxed max-w-2xl">
            Manutenção, diagnóstico e suporte técnico para computadores e notebooks, com atendimento em João Pessoa e cidades da região metropolitana.
          </p>

          <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 w-full sm:w-auto pt-2">
            <a href="<?= e($whatsappUrl) ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-3 px-7 py-4 rounded-xl bg-[#D71920] hover:bg-[#A90F17] text-white font-semibold text-sm transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5 cursor-pointer">
              <?= whatsapp_svg('fill-current w-5 h-5 shrink-0') ?>
              <span>Solicitar orçamento pelo WhatsApp</span>
            </a>

            <a href="/servicos" class="inline-flex items-center justify-center gap-2 px-6 py-4 rounded-xl bg-[#F5F6F8] hover:bg-[#E4E7EC] text-[#202124] font-semibold text-sm border border-[#E4E7EC] transition-all cursor-pointer">
              <span>Conhecer nossos serviços</span>
              <?= icon('ArrowDown', 'w-4 h-4 text-[#697386]') ?>
            </a>
          </div>

          <div class="pt-3 flex flex-wrap items-center gap-6 text-xs text-[#697386]">
            <div class="flex items-center gap-1.5 font-medium">
              <?= icon('CheckCircle2', 'w-4 h-4 text-[#25D366]') ?>
              <span>Garantia de 90 dias em serviços</span>
            </div>
            <div class="flex items-center gap-1.5 font-medium">
              <?= icon('ShieldAlert', 'w-4 h-4 text-[#D71920]') ?>
              <span>Sigilo e proteção total dos dados (LGPD)</span>
            </div>
          </div>
        </div>

        <div class="lg:col-span-5 relative">
          <div class="relative rounded-2xl overflow-hidden border border-[#E4E7EC] shadow-xl bg-[#F5F6F8]">
            <img src="/assets/img/bancada-diagnostico.jpg" alt="Profissional qualificada da PC Resolve realizando diagnóstico em placa de notebook em bancada organizada" class="w-full aspect-[4/3] object-cover object-center" fetchpriority="high">
            <div class="absolute bottom-4 left-4 right-4 bg-white/95 backdrop-blur-md p-3.5 rounded-xl border border-[#E4E7EC] shadow-md flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-[#F5F6F8] border border-[#E4E7EC] flex items-center justify-center text-[#D71920]">
                  <?= icon('Sliders', 'w-5 h-5 text-[#D71920]') ?>
                </div>
                <div>
                  <p class="font-heading font-bold text-xs text-[#202124]">Bancada técnica ESD</p>
                  <p class="text-[11px] text-[#697386]">Diagnóstico preciso &amp; instrumentação calibrada</p>
                </div>
              </div>
              <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#E4E7EC] text-[#202124]">Ativo</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 2. FAIXA DE CONFIANÇA -->
  <section class="bg-[#F5F6F8] border-y border-[#E4E7EC] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-8">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="flex items-center gap-4 bg-white p-4 rounded-xl border border-[#E4E7EC] shadow-xs">
          <div class="w-12 h-12 rounded-xl bg-[#F5F6F8] flex items-center justify-center text-[#D71920] shrink-0"><?= icon('Activity', 'w-6 h-6') ?></div>
          <div>
            <h4 class="font-heading font-bold text-sm text-[#202124]">Diagnóstico técnico</h4>
            <p class="text-xs text-[#697386] mt-0.5">Análise precisa de circuitos</p>
          </div>
        </div>
        <div class="flex items-center gap-4 bg-white p-4 rounded-xl border border-[#E4E7EC] shadow-xs">
          <div class="w-12 h-12 rounded-xl bg-[#F5F6F8] flex items-center justify-center text-[#D71920] shrink-0"><?= icon('Zap', 'w-6 h-6') ?></div>
          <div>
            <h4 class="font-heading font-bold text-sm text-[#202124]">Atendimento ágil</h4>
            <p class="text-xs text-[#697386] mt-0.5">Agilidade e transparência</p>
          </div>
        </div>
        <div class="flex items-center gap-4 bg-white p-4 rounded-xl border border-[#E4E7EC] shadow-xs">
          <div class="w-12 h-12 rounded-xl bg-[#F5F6F8] flex items-center justify-center text-[#D71920] shrink-0"><?= icon('Laptop', 'w-6 h-6') ?></div>
          <div>
            <h4 class="font-heading font-bold text-sm text-[#202124]">Soluções completas</h4>
            <p class="text-xs text-[#697386] mt-0.5">Para computadores e notebooks</p>
          </div>
        </div>
        <div class="flex items-center gap-4 bg-white p-4 rounded-xl border border-[#E4E7EC] shadow-xs">
          <div class="w-12 h-12 rounded-xl bg-[#F5F6F8] flex items-center justify-center text-[#25D366] shrink-0"><?= icon('Phone', 'w-6 h-6 text-[#25D366]') ?></div>
          <div>
            <h4 class="font-heading font-bold text-sm text-[#202124]">Contato direto</h4>
            <p class="text-xs text-[#697386] mt-0.5">Fale com os técnicos no WhatsApp</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 3. CATÁLOGO DE SERVIÇOS -->
  <section id="servicos" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-8">
      <div class="text-center max-w-3xl mx-auto mb-16">
        <span class="text-xs font-bold uppercase tracking-wider text-[#D71920]">Especialidades</span>
        <h2 class="font-heading font-extrabold text-2xl sm:text-3xl lg:text-4xl text-[#202124] mt-2 mb-3">Serviços de manutenção e suporte em informática</h2>
        <p class="text-base text-[#697386]">Soluções para manter seus equipamentos seguros, rápidos e funcionando corretamente.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php foreach ($services as $service): ?>
          <div class="flex flex-col bg-[#F5F6F8] border border-[#E4E7EC] rounded-2xl overflow-hidden hover:border-[#D71920]/50 hover:shadow-md transition-all group">
            <div class="h-44 w-full overflow-hidden bg-neutral-100 relative">
              <img src="<?= e(image_url($service['image'])) ?>" alt="<?= e($service['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
              <div class="absolute top-3 left-3 w-8 h-8 rounded-lg bg-white/90 backdrop-blur-xs flex items-center justify-center shadow-xs">
                <?= service_icon($service['icon_name'], 'w-6 h-6 text-[#D71920]') ?>
              </div>
            </div>

            <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
              <div>
                <h3 class="font-heading font-bold text-base text-[#202124] mb-2 leading-snug"><?= e($service['title']) ?></h3>
                <p class="text-xs text-[#697386] leading-relaxed line-clamp-3"><?= e($service['short_desc']) ?></p>
              </div>

              <div class="pt-3 border-t border-[#E4E7EC] flex items-center justify-between">
                <a href="/servicos/<?= e($service['slug']) ?>" class="text-xs font-semibold text-[#D71920] group-hover:underline flex items-center gap-1 cursor-pointer">
                  <span>Saiba mais</span>
                  <?= icon('ArrowRight', 'w-3.5 h-3.5') ?>
                </a>
                <a href="<?= e(company_wa('Olá! Gostaria de um orçamento para: ' . $service['title'])) ?>" target="_blank" rel="noopener noreferrer" class="text-[11px] font-medium px-2.5 py-1 rounded-md bg-white border border-[#E4E7EC] hover:bg-[#D71920] hover:text-white hover:border-[#D71920] transition-colors">
                  Solicitar
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="mt-12 text-center">
        <a href="/servicos" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white border border-[#E4E7EC] hover:border-[#D71920] hover:text-[#D71920] text-[#202124] font-semibold text-xs transition-colors shadow-2xs cursor-pointer">
          <span>Ver todos os <?= (int) $servicesCount ?> serviços cadastrados</span>
          <?= icon('ArrowRight', 'w-4 h-4') ?>
        </a>
      </div>
    </div>
  </section>

  <!-- 4. DIFERENCIAIS TÉCNICOS -->
  <section id="diferenciais" class="py-20 bg-[#F5F6F8] border-y border-[#E4E7EC]">
    <div class="max-w-7xl mx-auto px-4 sm:px-8">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
        <div class="lg:col-span-5 relative">
          <div class="rounded-2xl overflow-hidden border border-[#E4E7EC] shadow-lg bg-white">
            <img src="/assets/img/pasta-termica.jpg" alt="Técnico aplicando composto térmico de precisão em bancada ESD" class="w-full aspect-[4/3] object-cover" loading="lazy">
          </div>
          <div class="absolute -bottom-4 -right-4 hidden sm:flex items-center gap-3 bg-white p-4 rounded-xl border border-[#E4E7EC] shadow-md max-w-xs">
            <?= icon('CheckCircle2', 'w-7 h-7 text-[#D71920] shrink-0') ?>
            <div>
              <p class="font-heading font-bold text-xs text-[#202124]">Padrão ESD Anti-estática</p>
              <p class="text-[11px] text-[#697386]">Proteção de chips e circuitos sensíveis</p>
            </div>
          </div>
        </div>

        <div class="lg:col-span-7 flex flex-col space-y-6">
          <span class="text-xs font-bold uppercase tracking-wider text-[#D71920]">Compromisso Técnico</span>
          <h2 class="font-heading font-extrabold text-2xl sm:text-3xl lg:text-4xl text-[#202124] leading-tight">Seu equipamento merece cuidado especializado</h2>
          <p class="text-sm sm:text-base text-[#697386] leading-relaxed">
            Trabalhamos com metodologia criteriosa e ferramentas calibradas para diagnosticar falhas com exatidão, sem substituições desnecessárias de peças.
          </p>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
            <?php foreach ([
                ['Eye', 'Atendimento transparente', 'Orçamento detalhado prévio, sem cobranças inesperadas ou custos ocultos.'],
                ['Activity', 'Diagnóstico cuidadoso', 'Análise elétrica e lógica aprofundada antes de qualquer proposta de reparo.'],
                ['MessageSquare', 'Comunicação clara', 'Linguagem acessível e explicações objetivas sobre o problema da sua máquina.'],
                ['CheckCircle2', 'Soluções adequadas', 'Foco no melhor custo-benefício e na longevidade do seu equipamento.'],
            ] as [$iconName, $heading, $text]): ?>
              <div class="bg-white p-5 rounded-xl border border-[#E4E7EC]">
                <div class="w-9 h-9 rounded-lg bg-[#F5F6F8] flex items-center justify-center text-[#D71920] mb-3">
                  <?= icon($iconName, 'w-5 h-5 text-[#D71920]') ?>
                </div>
                <h4 class="font-heading font-bold text-sm text-[#202124] mb-1"><?= $heading ?></h4>
                <p class="text-xs text-[#697386] leading-relaxed"><?= $text ?></p>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 5. COMO FUNCIONA O ATENDIMENTO -->
  <section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-8">
      <div class="text-center max-w-3xl mx-auto mb-16">
        <span class="text-xs font-bold uppercase tracking-wider text-[#D71920]">Fluxo Descomplicado</span>
        <h2 class="font-heading font-extrabold text-2xl sm:text-3xl lg:text-4xl text-[#202124] mt-2 mb-3">Atendimento simples, do primeiro contato à solução</h2>
        <p class="text-base text-[#697386]">Processo ágil e transparente para você acompanhar cada etapa do serviço com tranquilidade.</p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php foreach ([
            ['01', 'Entre em contato', 'Fale conosco pelo WhatsApp ou envie uma mensagem através do formulário digital.', 'Resposta ágil'],
            ['02', 'Explique o problema', 'Descreva o que está ocorrendo: lentidão, travamento, aquecimento ou falha ao ligar.', 'Triagem inicial'],
            ['03', 'Receba as orientações', 'Apresentamos a análise técnica, opções de solução e orçamento claro com prazo estimado.', 'Valores fixos sem surpresa'],
            ['04', 'Autorize o serviço', 'Execução do reparo em bancada com testes rigorosos de estabilidade e garantia de 90 dias.', 'Garantia de 90 dias'],
        ] as $i => [$number, $heading, $text, $badge]): ?>
          <div class="bg-[#F5F6F8] border border-[#E4E7EC] rounded-2xl p-6 flex flex-col justify-between">
            <div>
              <span class="w-10 h-10 rounded-xl <?= $i === 0 ? 'bg-[#D71920] text-white shadow-xs' : 'bg-white border border-[#E4E7EC] text-[#202124]' ?> font-heading font-extrabold text-sm flex items-center justify-center mb-4"><?= $number ?></span>
              <h3 class="font-heading font-bold text-base text-[#202124] mb-2"><?= $heading ?></h3>
              <p class="text-xs text-[#697386] leading-relaxed"><?= $text ?></p>
            </div>
            <div class="mt-6 pt-3 border-t border-[#E4E7EC] text-[11px] text-[#697386] flex items-center gap-1.5 font-medium">
              <?= icon('CheckCircle2', 'w-3.5 h-3.5 text-[#25D366]') ?>
              <span><?= $badge ?></span>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- 6. SOBRE A PC RESOLVE -->
  <section class="py-20 bg-[#F5F6F8] border-y border-[#E4E7EC]">
    <div class="max-w-7xl mx-auto px-4 sm:px-8">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
        <div class="lg:col-span-7 flex flex-col space-y-6">
          <span class="text-xs font-bold uppercase tracking-wider text-[#D71920]">Institucional</span>
          <h2 class="font-heading font-extrabold text-2xl sm:text-3xl lg:text-4xl text-[#202124] leading-tight">Tecnologia, cuidado e confiança em cada atendimento</h2>
          <div class="space-y-4 text-sm sm:text-base text-[#697386] leading-relaxed">
            <p>
              A <strong class="text-[#202124]"><?= e(setting('name')) ?></strong> atua em João Pessoa e região com a missão de oferecer uma assistência técnica diferenciada, que combina honestidade, rigor em bancada e total respeito pelos equipamentos e dados dos clientes.
            </p>
            <p>
              Entendemos que computadores e notebooks são ferramentas essenciais de estudo, trabalho e vida pessoal. Por isso, mantemos um padrão de trabalho limpo, organizado e pautado pelo diagnóstico transparente.
            </p>
          </div>
          <div class="pt-2 flex flex-wrap items-center gap-4">
            <a href="/sobre" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white font-medium text-sm transition-all shadow-xs cursor-pointer">
              <span>Conheça nossa empresa</span>
              <?= icon('ArrowRight', 'w-4 h-4') ?>
            </a>
            <a href="<?= e($whatsappUrl) ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-white border border-[#E4E7EC] hover:bg-neutral-50 text-[#202124] font-medium text-sm transition-all">
              <?= icon('MessageSquare', 'w-4 h-4 text-[#25D366]') ?>
              <span>Falar com a equipe</span>
            </a>
          </div>
        </div>

        <div class="lg:col-span-5">
          <div class="rounded-2xl overflow-hidden border border-[#E4E7EC] shadow-md bg-white">
            <img src="/assets/img/workstation.jpg" alt="Técnico em bancada com equipamento de segurança montando estação de trabalho" class="w-full aspect-[4/3] object-cover" loading="lazy">
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 7. CIDADES ATENDIDAS -->
  <section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-8">
      <div class="text-center max-w-3xl mx-auto mb-16">
        <span class="text-xs font-bold uppercase tracking-wider text-[#D71920]">Cobertura Regional</span>
        <h2 class="font-heading font-extrabold text-2xl sm:text-3xl lg:text-4xl text-[#202124] mt-2 mb-3">Assistência técnica na Grande João Pessoa</h2>
        <p class="text-base text-[#697386]">Atendimento presencial e suporte corporativo nas cidades da região metropolitana:</p>
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 gap-4 mb-8">
        <?php foreach ($cities as $city): ?>
          <div class="p-5 rounded-xl border border-[#E4E7EC] bg-[#F5F6F8] flex items-center justify-between">
            <div class="flex items-center gap-3">
              <?= icon('MapPin', 'w-5 h-5 text-[#D71920] shrink-0') ?>
              <span class="font-heading font-bold text-sm text-[#202124]"><?= e($city['name']) ?></span>
            </div>
            <span class="text-[11px] text-[#697386] font-medium hidden sm:inline truncate max-w-[130px]"><?= e(explode('(', (string) $city['coverage'])[0]) ?></span>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="p-6 rounded-2xl bg-[#F5F6F8] border border-[#E4E7EC] flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-xl bg-white border border-[#E4E7EC] flex items-center justify-center text-[#D71920] shrink-0">
            <?= icon('Truck', 'w-6 h-6 text-[#D71920]') ?>
          </div>
          <div>
            <h4 class="font-heading font-bold text-sm text-[#202124]">Serviço de Coleta e Entrega (Leva e Traz)</h4>
            <p class="text-xs text-[#697386] mt-0.5">Consulte a disponibilidade de coleta e entrega ou atendimento corporativo no seu município.</p>
          </div>
        </div>
        <a href="/areas-atendidas" class="px-5 py-2.5 rounded-lg bg-white hover:bg-neutral-100 text-[#202124] border border-[#E4E7EC] text-xs font-semibold shrink-0 transition-colors cursor-pointer">
          Consultar Regras de Coleta
        </a>
      </div>
    </div>
  </section>

  <!-- 8. AVALIAÇÕES -->
  <section class="py-20 bg-[#F5F6F8] border-y border-[#E4E7EC]">
    <div class="max-w-7xl mx-auto px-4 sm:px-8">
      <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-12 gap-4">
        <div>
          <span class="text-xs font-bold uppercase tracking-wider text-[#D71920]">Experiência</span>
          <h2 class="font-heading font-extrabold text-2xl sm:text-3xl lg:text-4xl text-[#202124] mt-2">Avaliações de clientes atendidos</h2>
        </div>
        <div class="flex items-center gap-2 bg-white px-3.5 py-1.5 rounded-lg border border-[#E4E7EC]">
          <?= icon('Star', 'w-4 h-4 text-amber-500 fill-amber-500') ?>
          <span class="font-heading font-bold text-xs text-[#202124]">Avaliações verificadas</span>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php foreach ($testimonials as $test): ?>
          <div class="bg-white p-6 rounded-2xl border border-[#E4E7EC] shadow-xs flex flex-col justify-between">
            <div>
              <div class="flex items-center gap-1 text-amber-500 mb-3" aria-label="<?= (int) $test['rating'] ?> de 5 estrelas">
                <?php for ($i = 0; $i < $test['rating']; $i++): ?>
                  <?= icon('Star', 'w-4 h-4 fill-amber-500 text-amber-500') ?>
                <?php endfor; ?>
              </div>
              <p class="text-xs text-[#697386] leading-relaxed italic">"<?= e($test['text']) ?>"</p>
            </div>
            <div class="mt-6 pt-4 border-t border-[#E4E7EC] flex items-center justify-between text-xs">
              <span class="font-semibold text-[#202124]"><?= e($test['author']) ?></span>
              <span class="text-[#697386]"><?= e($test['location']) ?></span>
            </div>
          </div>
        <?php endforeach; ?>

        <div class="bg-white p-6 rounded-2xl border border-dashed border-[#E4E7EC] flex flex-col items-center justify-center text-center">
          <div class="w-12 h-12 rounded-full bg-[#F5F6F8] flex items-center justify-center text-[#D71920] mb-3">
            <?= icon('Star', 'w-6 h-6 text-[#D71920]') ?>
          </div>
          <h4 class="font-heading font-bold text-sm text-[#202124] mb-1">Já é nosso cliente?</h4>
          <p class="text-xs text-[#697386] max-w-xs mb-4">Sua avaliação é publicada após a conclusão e entrega do equipamento.</p>
          <a href="<?= e(company_wa('Olá! Gostaria de enviar meu feedback sobre o atendimento que recebi.')) ?>" target="_blank" rel="noopener noreferrer" class="text-xs font-semibold text-[#D71920] hover:underline">
            Enviar feedback pelo WhatsApp &rarr;
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- 9. FAQ -->
  <section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-8">
      <div class="text-center mb-16">
        <span class="text-xs font-bold uppercase tracking-wider text-[#D71920]">Dúvidas Frequentes</span>
        <h2 class="font-heading font-extrabold text-2xl sm:text-3xl text-[#202124] mt-2 mb-3">Perguntas Frequentes</h2>
        <p class="text-sm text-[#697386]">Tire suas dúvidas antes de solicitar seu atendimento</p>
      </div>

      <div class="space-y-3">
        <?php foreach ($faqs as $faq): ?>
          <details class="group bg-[#F5F6F8] border border-[#E4E7EC] rounded-xl p-5 open:[&_svg]:-rotate-180 transition-all">
            <summary class="flex items-center justify-between cursor-pointer font-heading font-bold text-sm text-[#202124] list-none [&::-webkit-details-marker]:hidden">
              <span><?= e($faq['question']) ?></span>
              <?= icon('ChevronDown', 'w-4 h-4 text-[#697386] transition-transform duration-200 shrink-0') ?>
            </summary>
            <p class="text-xs text-[#697386] mt-3 leading-relaxed border-t border-[#E4E7EC]/60 pt-3"><?= e($faq['answer']) ?></p>
          </details>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- 10. FORMULÁRIO DE ORÇAMENTO -->
  <section id="contato" class="py-20 bg-[#F5F6F8] border-t border-[#E4E7EC] scroll-mt-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-8">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        <div class="lg:col-span-5 flex flex-col space-y-6">
          <div>
            <span class="text-xs font-bold uppercase tracking-wider text-[#D71920]">Fale Conosco</span>
            <h2 class="font-heading font-extrabold text-2xl sm:text-3xl text-[#202124] mt-2 mb-3">Canais de Atendimento</h2>
            <p class="text-sm text-[#697386] leading-relaxed">Estamos prontos para atender você presencialmente ou responder suas dúvidas técnicas online.</p>
          </div>

          <div class="space-y-4 pt-2">
            <div class="flex items-start gap-3 p-4 rounded-xl bg-white border border-[#E4E7EC]">
              <?= icon('Phone', 'w-5 h-5 text-[#D71920] shrink-0 mt-0.5') ?>
              <div>
                <h4 class="font-heading font-bold text-xs text-[#202124]">Telefone Fixo</h4>
                <p class="text-xs text-[#697386] mt-0.5"><?= e(setting('phone')) ?></p>
              </div>
            </div>
            <div class="flex items-start gap-3 p-4 rounded-xl bg-white border border-[#E4E7EC]">
              <div class="w-5 h-5 flex items-center justify-center text-[#25D366] font-bold text-sm shrink-0">W</div>
              <div>
                <h4 class="font-heading font-bold text-xs text-[#202124]">WhatsApp Técnico</h4>
                <p class="text-xs text-[#697386] mt-0.5"><?= e(setting('whatsapp')) ?></p>
              </div>
            </div>
            <div class="flex items-start gap-3 p-4 rounded-xl bg-white border border-[#E4E7EC]">
              <?= icon('Clock', 'w-5 h-5 text-[#697386] shrink-0 mt-0.5') ?>
              <div>
                <h4 class="font-heading font-bold text-xs text-[#202124]">Horário de Atendimento</h4>
                <p class="text-xs text-[#697386] mt-0.5"><?= e(setting('workingHoursWeekday')) ?> | <?= e(setting('workingHoursSaturday')) ?></p>
              </div>
            </div>
            <div class="flex items-start gap-3 p-4 rounded-xl bg-white border border-[#E4E7EC]">
              <?= icon('MapPin', 'w-5 h-5 text-[#D71920] shrink-0 mt-0.5') ?>
              <div>
                <h4 class="font-heading font-bold text-xs text-[#202124]">Localização</h4>
                <p class="text-xs text-[#697386] mt-0.5"><?= e(setting('address')) ?> (<?= e(setting('city')) ?> - <?= e(setting('state')) ?>)</p>
              </div>
            </div>
          </div>
        </div>

        <div
          class="lg:col-span-7 bg-white p-6 sm:p-8 rounded-2xl border border-[#E4E7EC] shadow-sm"
          x-data="leadForm(<?= json_attr([
              'done' => $leadSuccess !== null,
              'consent' => (bool) old('consent', false),
              'requireConsent' => true,
              'missingMessage' => 'Por favor, informe seu nome e telefone/WhatsApp.',
              'consentMessage' => 'É necessário concordar com o tratamento dos dados.',
          ]) ?>)"
        >
          <h3 class="font-heading font-bold text-lg text-[#202124] mb-1">Solicitar Orçamento Técnico</h3>
          <p class="text-xs text-[#697386] mb-6">Preencha os campos abaixo com os detalhes da máquina para retorno com o pré-diagnóstico.</p>

          <?php if ($leadSuccess !== null): ?>
            <div x-show="done" class="p-6 rounded-xl bg-emerald-50 border border-emerald-200 text-center space-y-4">
              <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto">
                <?= icon('CheckCircle2', 'w-6 h-6') ?>
              </div>
              <div>
                <h4 class="font-heading font-bold text-base text-emerald-950">Solicitação Registrada com Sucesso!</h4>
                <p class="text-xs text-emerald-800 mt-1">
                  Seu protocolo de atendimento é
                  <span class="font-mono font-bold bg-white px-2 py-0.5 rounded border border-emerald-300"><?= e($leadSuccess) ?></span>
                </p>
                <p class="text-xs text-emerald-700 mt-2">Nossa equipe técnica já recebeu as informações e entrará em contato via WhatsApp em poucos minutos.</p>
              </div>
              <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
                <button type="button" @click="done = false" class="px-4 py-2 rounded-lg bg-white border border-emerald-300 text-xs font-semibold text-emerald-900 hover:bg-emerald-50 transition-colors cursor-pointer">
                  Enviar Outra Solicitação
                </button>
                <?php if (is_superadmin()): ?>
                  <a href="/painel/solicitacoes" rel="nofollow" class="px-4 py-2 rounded-lg bg-emerald-700 text-white text-xs font-semibold hover:bg-emerald-800 transition-colors cursor-pointer">
                    Ver no Painel Administrativo
                  </a>
                <?php endif; ?>
              </div>
            </div>
          <?php endif; ?>

          <form method="post" action="/solicitar" @submit="submit" x-show="!done" <?= $leadSuccess !== null ? 'x-cloak' : '' ?> class="space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="origem" value="home">
            <div class="hidden" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label for="home-name" class="block text-xs font-semibold text-[#202124] mb-1.5">Nome completo *</label>
                <input id="home-name" type="text" name="customer_name" required maxlength="150" value="<?= e(old('customer_name')) ?>" placeholder="Ex: Carlos Eduardo" class="w-full px-3.5 py-2.5 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]">
              </div>
              <div>
                <label for="home-phone" class="block text-xs font-semibold text-[#202124] mb-1.5">Telefone / WhatsApp *</label>
                <input id="home-phone" type="tel" name="phone" required maxlength="40" value="<?= e(old('phone')) ?>" placeholder="(83) 99999-9999" class="w-full px-3.5 py-2.5 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]">
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label for="home-city" class="block text-xs font-semibold text-[#202124] mb-1.5">Cidade *</label>
                <select id="home-city" name="city" class="w-full px-3.5 py-2.5 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]">
                  <?php foreach (['João Pessoa' => 'João Pessoa', 'Cabedelo' => 'Cabedelo', 'Bayeux' => 'Bayeux', 'Santa Rita' => 'Santa Rita', 'Conde' => 'Conde', 'Lucena' => 'Lucena', 'Outra' => 'Outro município da região'] as $value => $label): ?>
                    <option value="<?= e($value) ?>"<?= $oldCity === $value ? ' selected' : '' ?>><?= e($label) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div>
                <label for="home-device" class="block text-xs font-semibold text-[#202124] mb-1.5">Tipo de equipamento *</label>
                <select id="home-device" name="device_type" class="w-full px-3.5 py-2.5 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]">
                  <?php foreach (['notebook' => 'Notebook', 'desktop' => 'Computador Desktop / PC Gamer', 'all-in-one' => 'All-in-One', 'macbook' => 'Apple MacBook', 'corporativo' => 'Parque de máquinas / Empresa', 'outro' => 'Outro dispositivo'] as $value => $label): ?>
                    <option value="<?= e($value) ?>"<?= $oldDevice === $value ? ' selected' : '' ?>><?= e($label) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div>
              <label for="home-service" class="block text-xs font-semibold text-[#202124] mb-1.5">Serviço desejado</label>
              <select id="home-service" name="service_type" class="w-full px-3.5 py-2.5 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]">
                <?php foreach (lead_service_options() as $option): ?>
                  <option value="<?= e($option) ?>"<?= $oldService === $option ? ' selected' : '' ?>><?= e($option) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div>
              <label for="home-description" class="block text-xs font-semibold text-[#202124] mb-1.5">Descrição do problema ou sintomas</label>
              <textarea id="home-description" name="description" rows="3" maxlength="3000" placeholder="Ex: O notebook liga mas a tela fica preta e o cooler gira muito forte após alguns segundos..." class="w-full px-3.5 py-2.5 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]"><?= e(old('description')) ?></textarea>
            </div>

            <div class="flex items-start gap-2 pt-1">
              <input type="checkbox" id="consent" name="consent" value="1" x-model="consent" class="mt-0.5 accent-[#D71920]">
              <label for="consent" class="text-[11px] text-[#697386] leading-tight">
                Concordo com o envio das informações para contato técnico e orçamento, em conformidade com as diretrizes de privacidade e LGPD.
              </label>
            </div>

            <button type="submit" :disabled="sending" class="w-full py-3.5 px-6 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white font-semibold text-xs tracking-wide transition-all shadow-xs flex items-center justify-center gap-2 cursor-pointer disabled:opacity-70">
              <?= icon('Send', 'w-4 h-4') ?>
              <span>Enviar solicitação de atendimento</span>
            </button>
          </form>
        </div>
      </div>
    </div>
  </section>
</main>
