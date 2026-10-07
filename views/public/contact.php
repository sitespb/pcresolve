<?php
/** @var string|null $leadSuccess */
$oldCity = old('city', 'João Pessoa');
$oldDevice = old('device_type', 'notebook');
$oldService = old('service_type', 'Diagnóstico geral de falha');
?>
<div class="bg-white py-12 lg:py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-8 space-y-12">
    <div class="max-w-3xl">
      <span class="text-xs font-bold uppercase tracking-wider text-[#D71920]">Atendimento Técnico</span>
      <h1 class="font-heading font-extrabold text-3xl sm:text-4xl text-[#202124] tracking-tight mt-2">Fale com a <?= e(setting('name')) ?></h1>
      <p class="text-sm sm:text-base text-[#697386] mt-3 leading-relaxed">
        Estamos localizados em João Pessoa com estrutura pronta para receber seu equipamento ou coletar em seu endereço.
      </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
      <div class="lg:col-span-5 space-y-6">
        <div class="bg-[#F5F6F8] p-6 rounded-2xl border border-[#E4E7EC] space-y-4">
          <h2 class="font-heading font-bold text-base text-[#202124]">Informações de Contato</h2>

          <div class="space-y-3 pt-1">
            <div class="flex items-start gap-3 p-3.5 rounded-xl bg-white border border-[#E4E7EC]">
              <?= icon('Phone', 'w-5 h-5 text-[#D71920] shrink-0 mt-0.5') ?>
              <div>
                <h4 class="font-heading font-bold text-xs text-[#202124]">Telefone Fixo</h4>
                <p class="text-xs text-[#697386] mt-0.5"><a href="tel:<?= e(digits(setting('phone'))) ?>" class="hover:text-[#D71920]"><?= e(setting('phone')) ?></a></p>
              </div>
            </div>

            <div class="flex items-start gap-3 p-3.5 rounded-xl bg-white border border-[#E4E7EC]">
              <div class="w-5 h-5 flex items-center justify-center text-[#25D366] font-bold text-sm shrink-0">W</div>
              <div>
                <h4 class="font-heading font-bold text-xs text-[#202124]">WhatsApp Técnico</h4>
                <p class="text-xs text-[#697386] mt-0.5"><?= e(setting('whatsapp')) ?></p>
                <a href="<?= e(company_wa()) ?>" target="_blank" rel="noopener noreferrer" class="text-[11px] font-semibold text-[#25D366] hover:underline mt-1 inline-block">
                  Iniciar conversa imediata &rarr;
                </a>
              </div>
            </div>

            <div class="flex items-start gap-3 p-3.5 rounded-xl bg-white border border-[#E4E7EC]">
              <?= icon('Mail', 'w-5 h-5 text-[#D71920] shrink-0 mt-0.5') ?>
              <div>
                <h4 class="font-heading font-bold text-xs text-[#202124]">E-mail Corporativo</h4>
                <p class="text-xs text-[#697386] mt-0.5"><a href="mailto:<?= e(setting('email')) ?>" class="hover:text-[#D71920]"><?= e(setting('email')) ?></a></p>
              </div>
            </div>

            <div class="flex items-start gap-3 p-3.5 rounded-xl bg-white border border-[#E4E7EC]">
              <?= icon('Clock', 'w-5 h-5 text-[#697386] shrink-0 mt-0.5') ?>
              <div>
                <h4 class="font-heading font-bold text-xs text-[#202124]">Horários de Atendimento</h4>
                <p class="text-xs text-[#697386] mt-0.5"><?= e(setting('workingHoursWeekday')) ?></p>
                <p class="text-xs text-[#697386]"><?= e(setting('workingHoursSaturday')) ?></p>
              </div>
            </div>

            <div class="flex items-start gap-3 p-3.5 rounded-xl bg-white border border-[#E4E7EC]">
              <?= icon('MapPin', 'w-5 h-5 text-[#D71920] shrink-0 mt-0.5') ?>
              <div>
                <h4 class="font-heading font-bold text-xs text-[#202124]">Endereço</h4>
                <p class="text-xs text-[#697386] mt-0.5"><?= e(setting('address')) ?></p>
                <p class="text-xs text-[#697386]">Bairro <?= e(setting('neighborhood')) ?> - <?= e(setting('city')) ?>/<?= e(setting('state')) ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div
        id="formulario"
        class="lg:col-span-7 bg-[#F5F6F8] p-6 sm:p-8 rounded-2xl border border-[#E4E7EC] shadow-sm scroll-mt-28"
        x-data="leadForm(<?= json_attr([
            'done' => $leadSuccess !== null,
            'consent' => (bool) old('consent', false),
            'requireConsent' => true,
            'missingMessage' => 'Por favor, informe seu nome e telefone.',
            'consentMessage' => 'Por favor, assinale o consentimento para prosseguir.',
        ]) ?>)"
      >
        <h2 class="font-heading font-bold text-lg text-[#202124] mb-1">Formulário de Solicitação de Atendimento</h2>
        <p class="text-xs text-[#697386] mb-6">Preencha os dados e receba resposta técnica com orientações e número de protocolo.</p>

        <?php if ($leadSuccess !== null): ?>
          <div x-show="done" class="p-6 rounded-xl bg-white border border-emerald-200 text-center space-y-4">
            <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto">
              <?= icon('CheckCircle2', 'w-6 h-6') ?>
            </div>
            <div>
              <h3 class="font-heading font-bold text-base text-emerald-950">Solicitação Protocolada com Sucesso!</h3>
              <p class="text-xs text-neutral-600 mt-1">Guarde o seu número de protocolo:</p>
              <div class="mt-2 inline-block px-3 py-1 bg-[#F5F6F8] rounded-md border border-[#E4E7EC] font-mono text-sm font-bold text-[#D71920]"><?= e($leadSuccess) ?></div>
            </div>
            <p class="text-xs text-[#697386] max-w-md mx-auto">
              Nossa equipe técnica entrará em contato em instantes através do WhatsApp fornecido com a triagem inicial do seu equipamento.
            </p>
            <button type="button" @click="done = false" class="px-5 py-2.5 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold transition-colors cursor-pointer">
              Enviar Outra Solicitação
            </button>
          </div>
        <?php endif; ?>

        <form method="post" action="/solicitar" @submit="submit" x-show="!done" <?= $leadSuccess !== null ? 'x-cloak' : '' ?> class="space-y-4">
          <?= csrf_field() ?>
          <input type="hidden" name="origem" value="contato">
          <div class="hidden" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="ct-name" class="block text-xs font-semibold text-[#202124] mb-1">Nome completo *</label>
              <input id="ct-name" type="text" name="customer_name" required maxlength="150" value="<?= e(old('customer_name')) ?>" placeholder="Ex: Carlos Oliveira" class="w-full px-3.5 py-2.5 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]">
            </div>
            <div>
              <label for="ct-phone" class="block text-xs font-semibold text-[#202124] mb-1">Telefone / WhatsApp *</label>
              <input id="ct-phone" type="tel" name="phone" required maxlength="40" value="<?= e(old('phone')) ?>" placeholder="(83) 99999-9999" class="w-full px-3.5 py-2.5 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]">
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="ct-email" class="block text-xs font-semibold text-[#202124] mb-1">E-mail (opcional)</label>
              <input id="ct-email" type="email" name="email" maxlength="190" value="<?= e(old('email')) ?>" placeholder="seu.email@exemplo.com" class="w-full px-3.5 py-2.5 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]">
            </div>
            <div>
              <label for="ct-city" class="block text-xs font-semibold text-[#202124] mb-1">Cidade *</label>
              <select id="ct-city" name="city" class="w-full px-3.5 py-2.5 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]">
                <?php foreach (['João Pessoa' => 'João Pessoa', 'Cabedelo' => 'Cabedelo', 'Bayeux' => 'Bayeux', 'Santa Rita' => 'Santa Rita', 'Conde' => 'Conde', 'Lucena' => 'Lucena', 'Outro' => 'Outro município da região'] as $value => $label): ?>
                  <option value="<?= e($value) ?>"<?= $oldCity === $value ? ' selected' : '' ?>><?= e($label) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="ct-device" class="block text-xs font-semibold text-[#202124] mb-1">Tipo de equipamento *</label>
              <select id="ct-device" name="device_type" class="w-full px-3.5 py-2.5 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]">
                <?php foreach (['notebook' => 'Notebook', 'desktop' => 'Computador Desktop', 'all-in-one' => 'All-in-One', 'macbook' => 'Apple MacBook', 'corporativo' => 'Parque corporativo / Empresa'] as $value => $label): ?>
                  <option value="<?= e($value) ?>"<?= $oldDevice === $value ? ' selected' : '' ?>><?= e($label) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label for="ct-service" class="block text-xs font-semibold text-[#202124] mb-1">Serviço desejado</label>
              <select id="ct-service" name="service_type" class="w-full px-3.5 py-2.5 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]">
                <?php foreach (lead_service_options() as $option): ?>
                  <option value="<?= e($option) ?>"<?= $oldService === $option ? ' selected' : '' ?>><?= e($option) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div>
            <label for="ct-description" class="block text-xs font-semibold text-[#202124] mb-1">Descrição detalhada do problema</label>
            <textarea id="ct-description" name="description" rows="3" maxlength="3000" placeholder="Descreva o que acontece: lentidão, travamento, tela preta, ruído, etc." class="w-full px-3.5 py-2.5 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]"><?= e(old('description')) ?></textarea>
          </div>

          <div class="flex items-start gap-2 pt-1">
            <input type="checkbox" id="consent-contact" name="consent" value="1" x-model="consent" class="mt-0.5 accent-[#D71920]">
            <label for="consent-contact" class="text-[11px] text-[#697386] leading-tight">
              Concordo com o tratamento dos dados informados para fins de contato comercial e orçamento técnico, segundo a Lei Geral de Proteção de Dados (LGPD).
            </label>
          </div>

          <button type="submit" :disabled="sending" class="w-full py-3.5 px-6 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white font-semibold text-xs tracking-wide transition-all shadow-xs flex items-center justify-center gap-2 cursor-pointer disabled:opacity-70">
            <?= icon('Send', 'w-4 h-4') ?>
            <span>Registrar Solicitação</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
