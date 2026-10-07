<?php
/**
 * @var array<string, mixed> $service
 * @var string|null $leadSuccess
 */
$whatsappUrl = company_wa('Olá! Gostaria de agendar o serviço: ' . $service['title']);
$oldCity = old('city', 'João Pessoa');
$oldDevice = old('device_type', 'notebook');
?>
<div class="bg-white py-10 lg:py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-8">
    <div class="mb-8">
      <a href="/servicos" class="inline-flex items-center gap-2 text-xs font-semibold text-[#697386] hover:text-[#D71920] transition-colors cursor-pointer">
        <?= icon('ArrowLeft', 'w-4 h-4') ?>
        <span>Voltar ao catálogo de serviços</span>
      </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
      <div class="lg:col-span-8 space-y-8">
        <div>
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#F5F6F8] text-xs font-semibold text-[#D71920] mb-3">
            <?= icon('Sliders', 'w-3.5 h-3.5') ?>
            <span>Bancada Técnica ESD Certificada</span>
          </div>
          <h1 class="font-heading font-extrabold text-2xl sm:text-4xl text-[#202124] tracking-tight"><?= e($service['title']) ?></h1>
          <p class="text-sm sm:text-base text-[#697386] mt-3 leading-relaxed"><?= e($service['short_desc']) ?></p>
        </div>

        <div class="rounded-2xl overflow-hidden border border-[#E4E7EC] shadow-md bg-neutral-100 relative">
          <img src="<?= e(image_url($service['image'])) ?>" alt="<?= e($service['title']) ?>" class="w-full aspect-[16/9] object-cover" fetchpriority="high">
          <div class="absolute bottom-4 left-4 right-4 bg-white/95 backdrop-blur-md p-4 rounded-xl border border-[#E4E7EC] flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-4">
              <div class="flex items-center gap-1.5 text-xs text-[#202124] font-medium">
                <?= icon('Clock', 'w-4 h-4 text-[#D71920]') ?>
                <span>Prazo: <strong><?= e($service['turnaround_time']) ?></strong></span>
              </div>
              <div class="flex items-center gap-1.5 text-xs text-[#202124] font-medium">
                <?= icon('ShieldCheck', 'w-4 h-4 text-[#25D366]') ?>
                <span>Garantia: <strong><?= (int) $service['warranty_days'] ?> dias</strong></span>
              </div>
            </div>
            <div class="text-xs font-bold text-[#D71920]">
              Investimento estimado a partir de R$ <?= e(js_number($service['price_starting_at'])) ?>
            </div>
          </div>
        </div>

        <div class="space-y-4">
          <h2 class="font-heading font-bold text-lg text-[#202124]">Sobre o procedimento técnico</h2>
          <p class="text-sm text-[#697386] leading-relaxed"><?= nl2br(e($service['full_desc'])) ?></p>
        </div>

        <?php if (!empty($service['highlights'])): ?>
          <div class="bg-[#F5F6F8] p-6 rounded-2xl border border-[#E4E7EC] space-y-4">
            <h3 class="font-heading font-bold text-sm text-[#202124] uppercase tracking-wider">Diferenciais e Padrão de Execução</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <?php foreach ($service['highlights'] as $highlight): ?>
                <div class="flex items-start gap-2.5 text-xs text-[#202124]">
                  <?= icon('CheckCircle2', 'w-4 h-4 text-[#25D366] shrink-0 mt-0.5') ?>
                  <span><?= e($highlight) ?></span>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>

        <?php if (!empty($service['recommended_for'])): ?>
          <div class="bg-white p-6 rounded-2xl border border-[#E4E7EC] space-y-4">
            <h3 class="font-heading font-bold text-sm text-[#202124] uppercase tracking-wider flex items-center gap-2">
              <?= icon('AlertCircle', 'w-4 h-4 text-[#D71920]') ?>
              <span>Sintomas mais comuns que demandam esse serviço</span>
            </h3>
            <ul class="space-y-2 text-xs text-[#697386]">
              <?php foreach ($service['recommended_for'] as $item): ?>
                <li class="flex items-center gap-2">
                  <span class="w-1.5 h-1.5 rounded-full bg-[#D71920] shrink-0"></span>
                  <span><?= e($item) ?></span>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>
      </div>

      <div id="solicitar" class="lg:col-span-4 sticky top-28 space-y-6 scroll-mt-28">
        <div
          class="bg-[#F5F6F8] p-6 rounded-2xl border border-[#E4E7EC] shadow-sm"
          x-data="leadForm(<?= json_attr([
              'done' => $leadSuccess !== null,
              'requireConsent' => false,
              'missingMessage' => 'Por favor, informe seu nome e WhatsApp.',
          ]) ?>)"
        >
          <h3 class="font-heading font-bold text-base text-[#202124] mb-1">Solicitar este serviço</h3>
          <p class="text-xs text-[#697386] mb-5">Receba retorno com estimativa de custo e orientações de bancada.</p>

          <?php if ($leadSuccess !== null): ?>
            <div x-show="done" class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-center space-y-3">
              <?= icon('CheckCircle2', 'w-8 h-8 text-emerald-600 mx-auto') ?>
              <p class="text-xs font-bold text-emerald-950">Solicitação enviada com sucesso!</p>
              <p class="text-xs text-emerald-800">Protocolo: <strong class="font-mono"><?= e($leadSuccess) ?></strong></p>
              <button type="button" @click="done = false" class="text-xs text-emerald-900 underline font-semibold cursor-pointer">Fazer novo pedido</button>
            </div>
          <?php endif; ?>

          <form method="post" action="/solicitar" @submit="submit" x-show="!done" <?= $leadSuccess !== null ? 'x-cloak' : '' ?> class="space-y-3.5">
            <?= csrf_field() ?>
            <input type="hidden" name="origem" value="servico">
            <input type="hidden" name="servico" value="<?= e($service['slug']) ?>">
            <div class="hidden" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

            <div>
              <label for="srv-name" class="block text-xs font-semibold text-[#202124] mb-1">Seu nome completo *</label>
              <input id="srv-name" type="text" name="customer_name" required maxlength="150" value="<?= e(old('customer_name')) ?>" placeholder="Ex: Carlos Oliveira" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]">
            </div>

            <div>
              <label for="srv-phone" class="block text-xs font-semibold text-[#202124] mb-1">WhatsApp para retorno *</label>
              <input id="srv-phone" type="tel" name="phone" required maxlength="40" value="<?= e(old('phone')) ?>" placeholder="(83) 99999-9999" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]">
            </div>

            <div class="grid grid-cols-2 gap-2">
              <div>
                <label for="srv-city" class="block text-xs font-semibold text-[#202124] mb-1">Cidade</label>
                <select id="srv-city" name="city" class="w-full px-2.5 py-2 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124]">
                  <?php foreach (['João Pessoa', 'Cabedelo', 'Bayeux', 'Santa Rita', 'Outra'] as $value): ?>
                    <option value="<?= e($value) ?>"<?= $oldCity === $value ? ' selected' : '' ?>><?= e($value) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div>
                <label for="srv-device" class="block text-xs font-semibold text-[#202124] mb-1">Dispositivo</label>
                <select id="srv-device" name="device_type" class="w-full px-2.5 py-2 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124]">
                  <?php foreach (['notebook' => 'Notebook', 'desktop' => 'PC Desktop', 'macbook' => 'MacBook', 'corporativo' => 'Empresa'] as $value => $label): ?>
                    <option value="<?= e($value) ?>"<?= $oldDevice === $value ? ' selected' : '' ?>><?= e($label) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div>
              <label for="srv-description" class="block text-xs font-semibold text-[#202124] mb-1">Observações adicionais (opcional)</label>
              <textarea id="srv-description" name="description" rows="2" maxlength="3000" placeholder="Ex: Marca do aparelho, ano ou defeito específico..." class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]"><?= e(old('description')) ?></textarea>
            </div>

            <button type="submit" :disabled="sending" class="w-full py-3 px-4 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold transition-colors flex items-center justify-center gap-2 cursor-pointer shadow-xs disabled:opacity-70">
              <?= icon('Send', 'w-3.5 h-3.5') ?>
              <span>Solicitar Orçamento</span>
            </button>
          </form>

          <div class="mt-4 pt-4 border-t border-[#E4E7EC] text-center">
            <span class="text-[11px] text-[#697386]">Prefere falar agora com o técnico?</span>
            <a href="<?= e($whatsappUrl) ?>" target="_blank" rel="noopener noreferrer" class="mt-2 inline-flex items-center justify-center gap-2 w-full py-2.5 px-3 rounded-lg bg-[#25D366] hover:bg-[#20ba59] text-white text-xs font-semibold transition-colors">
              <?= icon('Phone', 'w-3.5 h-3.5') ?>
              <span>Chamar no WhatsApp</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
