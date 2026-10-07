<?php
/**
 * @var array<string, string> $settings
 * @var string $tab
 * @var bool $allowReset
 * @var array<string, mixed> $imageConfig
 * @var list<string> $supportedFormats
 */
use App\Support\ImageProcessor;
$adminTitle = 'Configurações Gerais do Sistema';
$adminSubtitle = 'Defina os parâmetros corporativos da empresa, identificadores de SEO e integrações';

$s = fn (string $key): string => e($settings[$key] ?? '');
$tabs = [
    'empresa' => ['Dados da Empresa', 'Building'],
    'seo' => ['SEO & Rastreamento (Google)', 'Globe'],
    'whatsapp' => ['Mensagens & WhatsApp', 'MessageSquare'],
    'imagens' => ['Imagens & Upload', 'Eye'],
    'privacidade' => ['Privacidade & LGPD', 'ShieldCheck'],
];
$input = 'w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white focus:outline-none focus:border-[#D71920]';
?>
<div class="bg-white rounded-2xl border border-[#E4E7EC] shadow-2xs overflow-hidden" x-data="{ activeTab: '<?= e($tab) ?>' }">
  <div class="flex items-center gap-2 border-b border-[#E4E7EC] px-6 pt-3 bg-[#F5F6F8] overflow-x-auto">
    <?php foreach ($tabs as $key => [$label, $iconName]): ?>
      <button type="button" @click="activeTab = '<?= $key ?>'" class="flex items-center gap-2 px-4 py-3 text-xs font-semibold border-b-2 transition-all cursor-pointer whitespace-nowrap" :class="activeTab === '<?= $key ?>' ? 'border-[#D71920] text-[#D71920] bg-white rounded-t-xl shadow-2xs' : 'border-transparent text-[#697386] hover:text-[#202124]'">
        <span><?= icon($iconName, 'w-4 h-4') ?></span>
        <span><?= e($label) ?></span>
      </button>
    <?php endforeach; ?>
  </div>

  <!--
    Guarda de envio: as 5 abas vivem no mesmo formulário e as inativas ficam com display:none.
    Um campo inválido escondido faz o navegador cancelar o envio sem aviso ("not focusable"),
    então abrimos a aba do campo com problema e mostramos a mensagem.
  -->
  <form
    method="post"
    action="/painel/configuracoes"
    class="p-6 space-y-6 text-xs"
    id="settings-form"
    @submit="
      const invalido = Array.from($el.elements).find(el => el.willValidate && !el.checkValidity());
      if (invalido) {
        $event.preventDefault();
        const aba = invalido.closest('[data-tab]');
        if (aba) { activeTab = aba.dataset.tab; }
        $nextTick(() => invalido.reportValidity());
      }
    "
  >
    <?= csrf_field() ?>
    <input type="hidden" name="tab" :value="activeTab" value="<?= e($tab) ?>">

    <!-- Empresa -->
    <div class="space-y-4" data-tab="empresa" x-show="activeTab === 'empresa'"<?= $tab !== 'empresa' ? ' x-cloak' : '' ?>>
      <div class="pb-3 border-b border-[#E4E7EC]">
        <h3 class="font-heading font-bold text-base text-[#202124]">Identificação Institucional &amp; Comercial</h3>
        <p class="text-xs text-[#697386]">Essas informações são refletidas no cabeçalho, rodapé e nos termos do site</p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-semibold text-[#202124] mb-1" for="s-name">Nome Fantasia *</label>
          <input id="s-name" type="text" name="name" required maxlength="255" value="<?= $s('name') ?>" class="<?= $input ?>">
        </div>
        <div>
          <label class="block text-xs font-semibold text-[#202124] mb-1" for="s-corporate">Razão Social</label>
          <input id="s-corporate" type="text" name="corporateName" maxlength="255" value="<?= $s('corporateName') ?>" class="<?= $input ?>">
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
          <label class="block text-xs font-semibold text-[#202124] mb-1" for="s-cnpj">CNPJ</label>
          <input id="s-cnpj" type="text" name="cnpj" maxlength="40" value="<?= $s('cnpj') ?>" class="<?= $input ?>">
        </div>
        <div>
          <label class="block text-xs font-semibold text-[#202124] mb-1" for="s-phone">Telefone Fixo *</label>
          <input id="s-phone" type="text" name="phone" required maxlength="40" value="<?= $s('phone') ?>" class="<?= $input ?>">
        </div>
        <div>
          <label class="block text-xs font-semibold text-[#202124] mb-1" for="s-whatsapp">WhatsApp Comercial *</label>
          <input id="s-whatsapp" type="text" name="whatsapp" required maxlength="40" value="<?= $s('whatsapp') ?>" class="<?= $input ?>">
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="sm:col-span-2">
          <label class="block text-xs font-semibold text-[#202124] mb-1" for="s-address">Endereço Completo</label>
          <input id="s-address" type="text" name="address" maxlength="255" value="<?= $s('address') ?>" class="<?= $input ?>">
        </div>
        <div>
          <label class="block text-xs font-semibold text-[#202124] mb-1" for="s-locality">Bairro / Cidade</label>
          <input id="s-locality" type="text" name="locality" maxlength="255" value="<?= $s('neighborhood') ?> - <?= $s('city') ?>/<?= $s('state') ?>" title="Formato: Bairro - Cidade/UF" class="<?= $input ?>">
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-semibold text-[#202124] mb-1" for="s-email">E-mail de Contato</label>
          <input id="s-email" type="email" name="email" maxlength="190" value="<?= $s('email') ?>" class="<?= $input ?>">
        </div>
        <div>
          <label class="block text-xs font-semibold text-[#202124] mb-1" for="s-zip">CEP</label>
          <input id="s-zip" type="text" name="zipCode" maxlength="20" value="<?= $s('zipCode') ?>" class="<?= $input ?>">
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-semibold text-[#202124] mb-1" for="s-weekday">Horário de Atendimento (Segunda a Sexta)</label>
          <input id="s-weekday" type="text" name="workingHoursWeekday" maxlength="255" value="<?= $s('workingHoursWeekday') ?>" class="<?= $input ?>">
        </div>
        <div>
          <label class="block text-xs font-semibold text-[#202124] mb-1" for="s-saturday">Horário de Atendimento (Sábado)</label>
          <input id="s-saturday" type="text" name="workingHoursSaturday" maxlength="255" value="<?= $s('workingHoursSaturday') ?>" class="<?= $input ?>">
        </div>
      </div>

      <div>
        <label class="block text-xs font-semibold text-[#202124] mb-1" for="s-about">Resumo Institucional (Bio)</label>
        <textarea id="s-about" name="aboutText" rows="3" maxlength="3000" class="<?= $input ?>"><?= $s('aboutText') ?></textarea>
      </div>
    </div>

    <!-- SEO -->
    <div class="space-y-4" data-tab="seo" x-show="activeTab === 'seo'"<?= $tab !== 'seo' ? ' x-cloak' : '' ?>>
      <div class="pb-3 border-b border-[#E4E7EC]">
        <h3 class="font-heading font-bold text-base text-[#202124]">SEO &amp; Rastreamento Google</h3>
        <p class="text-xs text-[#697386]">Configure os identificadores oficiais do Google Search Console, Google Analytics 4 e Google Tag Manager</p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
          <label class="block text-xs font-semibold text-[#202124] mb-1" for="s-ga4">Google Analytics 4 ID</label>
          <input id="s-ga4" type="text" name="ga4MeasurementId" maxlength="40" placeholder="G-XXXXXXXXXX" value="<?= $s('ga4MeasurementId') ?>" class="<?= $input ?> font-mono">
        </div>
        <div>
          <label class="block text-xs font-semibold text-[#202124] mb-1" for="s-gtm">Google Tag Manager Container</label>
          <input id="s-gtm" type="text" name="gtmContainerId" maxlength="40" placeholder="GTM-XXXXXXX" value="<?= $s('gtmContainerId') ?>" class="<?= $input ?> font-mono">
        </div>
        <div>
          <label class="block text-xs font-semibold text-[#202124] mb-1" for="s-ads">Google Ads Conversion ID</label>
          <input id="s-ads" type="text" name="googleAdsConversionId" maxlength="40" placeholder="AW-1122334455" value="<?= $s('googleAdsConversionId') ?>" class="<?= $input ?> font-mono">
        </div>
      </div>

      <div>
        <label class="block text-xs font-semibold text-[#202124] mb-1" for="s-meta-title">Meta Title Padrão (Título no Google)</label>
        <input id="s-meta-title" type="text" name="metaTitleDefault" maxlength="255" value="<?= $s('metaTitleDefault') ?>" class="<?= $input ?>">
      </div>

      <div>
        <label class="block text-xs font-semibold text-[#202124] mb-1" for="s-meta-desc">Meta Description Padrão</label>
        <textarea id="s-meta-desc" name="metaDescriptionDefault" rows="2" maxlength="3000" class="<?= $input ?>"><?= $s('metaDescriptionDefault') ?></textarea>
      </div>
    </div>

    <!-- WhatsApp -->
    <div class="space-y-4" data-tab="whatsapp" x-show="activeTab === 'whatsapp'"<?= $tab !== 'whatsapp' ? ' x-cloak' : '' ?>>
      <div class="pb-3 border-b border-[#E4E7EC]">
        <h3 class="font-heading font-bold text-base text-[#202124]">Comunicação &amp; Automação WhatsApp</h3>
        <p class="text-xs text-[#697386]">Personalize os modelos de texto enviados aos clientes em cada estágio do atendimento</p>
      </div>

      <div>
        <label class="block text-xs font-semibold text-[#202124] mb-1" for="s-wa-msg">Mensagem Padrão do Botão Flutuante</label>
        <input id="s-wa-msg" type="text" name="defaultWhatsappMessage" maxlength="255" value="<?= $s('defaultWhatsappMessage') ?>" class="<?= $input ?>">
      </div>

      <div class="p-4 rounded-xl bg-[#F5F6F8] border border-[#E4E7EC] space-y-2">
        <span class="font-bold text-[#202124] block">💡 Dica de Comunicação:</span>
        <p class="text-[#697386]">
          Ao clicar no botão "Notificar no WhatsApp" dentro do painel de Ordens de Serviço, o sistema preencherá automaticamente o nome do cliente, protocolo e o valor aprovado do reparo.
        </p>
      </div>
    </div>

    <!-- Imagens & Upload -->
    <div class="space-y-5" data-tab="imagens" x-show="activeTab === 'imagens'"<?= $tab !== 'imagens' ? ' x-cloak' : '' ?>>
      <div class="pb-3 border-b border-[#E4E7EC]">
        <h3 class="font-heading font-bold text-base text-[#202124]">Tratamento de Imagens Enviadas</h3>
        <p class="text-xs text-[#697386]">
          Toda imagem enviada pelo painel é redimensionada e comprimida automaticamente com estas regras,
          para o site carregar rápido e economizar espaço no servidor
        </p>
      </div>

      <?php if ($supportedFormats === []): ?>
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-900">
          <span class="font-bold block">A extensão GD do PHP não está disponível neste servidor.</span>
          O envio de imagens ficará indisponível até que ela seja habilitada.
        </div>
      <?php endif; ?>

      <!-- Formatos aceitos -->
      <div>
        <span class="block text-xs font-semibold text-[#202124] mb-2">Formatos aceitos no envio *</span>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          <?php foreach (ImageProcessor::FORMATS as $key => $label):
              $available = in_array($key, $supportedFormats, true);
              $checked = in_array($key, (array) $imageConfig['allowed_formats'], true); ?>
            <label class="flex items-start gap-2.5 p-3 rounded-xl border transition-colors <?= $available ? 'border-[#E4E7EC] bg-white hover:border-[#D71920]/40 cursor-pointer' : 'border-neutral-200 bg-neutral-50 opacity-60 cursor-not-allowed' ?>">
              <input type="checkbox" name="imgAllowedFormats[]" value="<?= e($key) ?>" <?= $checked ? 'checked' : '' ?> <?= $available ? '' : 'disabled' ?> class="mt-0.5 accent-[#D71920]">
              <span>
                <span class="font-bold text-[#202124] block"><?= e($label) ?></span>
                <span class="text-[10px] text-[#697386]">
                  <?php if (!$available): ?>
                    Não suportado pelo servidor
                  <?php else: ?>
                    <?= match ($key) {
                        'jpg' => 'Fotos em geral; melhor compressão',
                        'webp' => 'Moderno, arquivos menores',
                        default => 'Permite transparência; arquivos maiores',
                    } ?>
                  <?php endif; ?>
                </span>
              </span>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Dimensões e peso -->
      <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div>
          <label class="block text-xs font-semibold text-[#202124] mb-1" for="s-img-w">Largura máxima (px)</label>
          <input id="s-img-w" type="number" name="imgMaxWidth" min="100" max="6000" step="10" value="<?= (int) $imageConfig['max_width'] ?>" class="<?= $input ?>">
        </div>
        <div>
          <label class="block text-xs font-semibold text-[#202124] mb-1" for="s-img-h">Altura máxima (px)</label>
          <input id="s-img-h" type="number" name="imgMaxHeight" min="100" max="6000" step="10" value="<?= (int) $imageConfig['max_height'] ?>" class="<?= $input ?>">
        </div>
        <div>
          <label class="block text-xs font-semibold text-[#202124] mb-1" for="s-img-target">Peso alvo (KB)</label>
          <input id="s-img-target" type="number" name="imgTargetKb" min="20" max="5000" step="10" value="<?= (int) $imageConfig['target_kb'] ?>" class="<?= $input ?>">
          <span class="text-[10px] text-[#697386] mt-1 block">A qualidade é reduzida até atingir este peso</span>
        </div>
        <div>
          <label class="block text-xs font-semibold text-[#202124] mb-1" for="s-img-upload">Envio máximo (KB)</label>
          <input id="s-img-upload" type="number" name="imgMaxUploadKb" min="100" max="51200" step="1" value="<?= (int) $imageConfig['max_upload_kb'] ?>" class="<?= $input ?>">
          <span class="text-[10px] text-[#697386] mt-1 block">Tamanho do arquivo antes do tratamento</span>
        </div>
      </div>

      <!-- Qualidade, formato de saída e limite de megapixels -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div x-data="{ q: <?= (int) $imageConfig['quality'] ?> }">
          <label class="block text-xs font-semibold text-[#202124] mb-1" for="s-img-q">
            Qualidade inicial: <span class="font-mono text-[#D71920]" x-text="q + '%'"></span>
          </label>
          <input id="s-img-q" type="range" name="imgQuality" min="40" max="100" step="1" x-model="q" class="w-full accent-[#D71920]">
          <span class="text-[10px] text-[#697386] block">Maior = melhor imagem e arquivo mais pesado</span>
        </div>
        <div>
          <label class="block text-xs font-semibold text-[#202124] mb-1" for="s-img-out">Formato de saída</label>
          <select id="s-img-out" name="imgOutputFormat" class="<?= $input ?>">
            <?php foreach ([
                'auto' => 'Manter o formato enviado',
                'jpg' => 'Converter tudo para JPEG',
                'webp' => 'Converter tudo para WebP',
                'png' => 'Converter tudo para PNG',
            ] as $value => $label): ?>
              <option value="<?= e($value) ?>"<?= $imageConfig['output_format'] === $value ? ' selected' : '' ?>><?= e($label) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="block text-xs font-semibold text-[#202124] mb-1" for="s-img-mp">Limite de megapixels</label>
          <input id="s-img-mp" type="number" name="imgMaxMegapixels" min="1" max="200" step="1" value="<?= (int) $imageConfig['max_megapixels'] ?>" class="<?= $input ?>">
          <span class="text-[10px] text-[#697386] mt-1 block">Evita travar o servidor com imagens gigantes</span>
        </div>
      </div>

      <!-- Opções -->
      <div class="space-y-2 pt-1">
        <label class="flex items-start gap-2 cursor-pointer">
          <input type="checkbox" name="imgAutoRotate" value="1"<?= $imageConfig['auto_rotate'] ? ' checked' : '' ?> class="mt-0.5 accent-[#D71920]">
          <span class="text-[#202124]">
            Corrigir automaticamente a orientação das fotos
            <span class="text-[10px] text-[#697386] block">Endireita fotos de celular que chegam deitadas (tag EXIF)</span>
          </span>
        </label>
        <label class="flex items-start gap-2 cursor-pointer">
          <input type="checkbox" name="imgPngToJpg" value="1"<?= $imageConfig['png_to_jpg'] ? ' checked' : '' ?> class="mt-0.5 accent-[#D71920]">
          <span class="text-[#202124]">
            Converter PNG pesado em JPEG
            <span class="text-[10px] text-[#697386] block">Só quando o PNG ultrapassa o peso alvo; a transparência é substituída por fundo branco</span>
          </span>
        </label>
      </div>

      <div class="p-4 rounded-xl bg-[#F5F6F8] border border-[#E4E7EC] space-y-2">
        <span class="font-bold text-[#202124] block">💡 Como funciona:</span>
        <p class="text-[#697386]">
          A imagem enviada é reduzida até caber em <strong><?= (int) $imageConfig['max_width'] ?>×<?= (int) $imageConfig['max_height'] ?> px</strong>
          (sempre mantendo a proporção, sem ampliar imagens pequenas) e depois comprimida a partir de
          <strong><?= (int) $imageConfig['quality'] ?>%</strong> de qualidade, diminuindo de 10 em 10 até ficar com
          <strong><?= (int) $imageConfig['target_kb'] ?> KB</strong> ou menos. O arquivo original não é guardado.
        </p>
      </div>
    </div>

    <!-- Privacidade -->
    <div class="space-y-4" data-tab="privacidade" x-show="activeTab === 'privacidade'"<?= $tab !== 'privacidade' ? ' x-cloak' : '' ?>>
      <div class="pb-3 border-b border-[#E4E7EC]">
        <h3 class="font-heading font-bold text-base text-[#202124]">Privacidade, Garantia &amp; LGPD</h3>
        <p class="text-xs text-[#697386]">Termos legais de garantia de 90 dias e política de retenção de dados</p>
      </div>

      <div>
        <label class="block text-xs font-semibold text-[#202124] mb-1" for="s-warranty">Termo Padrão de Garantia Técnica</label>
        <textarea id="s-warranty" name="warrantyTerm" rows="3" maxlength="3000" class="<?= $input ?>"><?= $s('warrantyTerm') ?></textarea>
      </div>

      <div>
        <label class="block text-xs font-semibold text-[#202124] mb-1" for="s-mission">Missão da Empresa</label>
        <textarea id="s-mission" name="mission" rows="2" maxlength="3000" class="<?= $input ?>"><?= $s('mission') ?></textarea>
      </div>
    </div>

    <div class="pt-4 border-t border-[#E4E7EC] flex flex-wrap items-center justify-between gap-4">
      <button
        type="submit"
        form="reset-form"
        <?= $allowReset ? '' : 'disabled' ?>
        class="px-4 py-2 rounded-lg border border-neutral-300 text-neutral-600 hover:bg-neutral-50 transition-colors inline-flex items-center gap-1.5 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
        title="<?= $allowReset ? 'Apaga e recria os dados de demonstração' : 'Desativado neste ambiente (ALLOW_DEMO_RESET=false no .env)' ?>"
      >
        <?= icon('RotateCcw', 'w-3.5 h-3.5') ?>
        <span>Restaurar Padrão de Demonstração</span>
      </button>

      <button type="submit" class="px-6 py-2.5 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold shadow-xs transition-colors inline-flex items-center gap-2 cursor-pointer">
        <?= icon('Save', 'w-3.5 h-3.5') ?>
        <span>Salvar Configurações</span>
      </button>
    </div>
  </form>

  <form method="post" action="/painel/configuracoes/restaurar" id="reset-form" class="hidden" onsubmit="return confirm('Tem certeza que deseja restaurar os dados do sistema para a demonstração inicial?')">
    <?= csrf_field() ?>
  </form>
</div>
