<?php
/**
 * Biblioteca de mídia (visão em miniaturas e em lista).
 *
 * @var list<array<string, mixed>> $items
 * @var array<string, mixed>       $stats
 * @var string                     $view    Visão inicial: miniaturas|lista
 * @var string                     $filter  Filtro inicial: todas|orfas|em-uso
 * @var array<string, mixed>       $imageConfig
 * @var string                     $uploadHint
 */
use App\Controllers\Admin\MediaController;
use App\Support\MediaLibrary;

$adminTitle = 'Biblioteca de Mídia';
$adminSubtitle = 'Todas as imagens do site em um só lugar: peso, dimensões, onde estão anexadas e quais estão sobrando';
$aceita = MediaController::acceptAttr();

ob_start(); ?>
<button type="button" @click="$dispatch('abrir-envio')" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold shadow-xs transition-colors cursor-pointer">
  <?= icon('Upload', 'w-3.5 h-3.5') ?>
  <span>Enviar Imagens</span>
</button>
<?php $adminAction = ob_get_clean();

// Índice de busca: nome do arquivo + pasta + onde está anexada.
$indice = array_map(function (array $i): string {
    $usos = implode(' ', array_map(fn (array $u): string => $u['label'] . ' ' . $u['title'], $i['usage']));

    return mb_strtolower($i['filename'] . ' ' . $i['folder'] . ' ' . $usos);
}, $items);
?>
<div
  class="space-y-6"
  x-data="mediaPage(<?= json_attr([
      'view' => $view,
      'filter' => $filter,
      'rows' => $indice,
      'orphans' => array_map(fn (array $i): bool => (bool) $i['orphan'], $items),
      'items' => array_map(fn (array $i): array => [
          'path' => $i['path'],
          'url' => $i['url'],
          'filename' => $i['filename'],
          'dimensions' => $i['dimensions'],
          'size_label' => $i['size_label'],
          'modified_label' => $i['modified_label'],
          'ext' => $i['ext'],
          'megapixels' => $i['megapixels'],
          'usage' => $i['usage'],
          'in_code' => (bool) $i['in_code'],
          'orphan' => (bool) $i['orphan'],
          'deletable' => (bool) $i['deletable'],
      ], $items),
  ]) ?>)"
  @abrir-envio.window="uploadOpen = true"
  @keydown.escape.window="uploadOpen = false; detail = null"
>
  <!-- 1. Números do acervo -->
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="bg-white p-5 rounded-2xl border border-[#E4E7EC] shadow-2xs space-y-2">
      <div class="flex items-center justify-between text-xs text-[#697386]">
        <span>Imagens no Acervo</span>
        <span class="w-8 h-8 rounded-lg bg-[#F5F6F8] text-[#D71920] flex items-center justify-center"><?= icon('Image', 'w-4 h-4') ?></span>
      </div>
      <div class="font-heading font-extrabold text-2xl text-[#202124] font-mono-numbers"><?= (int) $stats['total'] ?></div>
      <p class="text-[11px] text-[#697386]">Arquivos em <?= e(MediaLibrary::DIR) ?></p>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-[#E4E7EC] shadow-2xs space-y-2">
      <div class="flex items-center justify-between text-xs text-[#697386]">
        <span>Espaço Ocupado</span>
        <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center"><?= icon('Sliders', 'w-4 h-4') ?></span>
      </div>
      <div class="font-heading font-extrabold text-2xl text-[#202124] font-mono-numbers"><?= e($stats['size_label']) ?></div>
      <p class="text-[11px] text-[#697386]">Soma de todos os arquivos</p>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-[#E4E7EC] shadow-2xs space-y-2">
      <div class="flex items-center justify-between text-xs text-[#697386]">
        <span>Em Uso</span>
        <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center"><?= icon('CheckCircle2', 'w-4 h-4') ?></span>
      </div>
      <div class="font-heading font-extrabold text-2xl text-emerald-700 font-mono-numbers"><?= (int) $stats['in_use'] ?></div>
      <p class="text-[11px] text-[#697386]">Anexadas a algum cadastro ou ao código</p>
    </div>

    <div class="bg-white p-5 rounded-2xl border border-[#E4E7EC] shadow-2xs space-y-2">
      <div class="flex items-center justify-between text-xs text-[#697386]">
        <span>Órfãs</span>
        <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center"><?= icon('Unlink', 'w-4 h-4') ?></span>
      </div>
      <div class="font-heading font-extrabold text-2xl <?= (int) $stats['orphans'] > 0 ? 'text-amber-700' : 'text-[#202124]' ?> font-mono-numbers"><?= (int) $stats['orphans'] ?></div>
      <p class="text-[11px] text-[#697386]">Nenhum cadastro usa — podem ser removidas</p>
    </div>
  </div>

  <!-- 2. Barra de ferramentas: visão, filtro e busca -->
  <div class="bg-white rounded-2xl border border-[#E4E7EC] p-4 shadow-2xs">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
      <div class="relative flex-1 max-w-md">
        <?= icon('Search', 'w-4 h-4 text-[#697386] absolute left-3.5 top-1/2 -translate-y-1/2') ?>
        <input type="text" x-model="search" placeholder="Buscar por nome do arquivo ou onde está anexada..." aria-label="Buscar imagens" class="w-full pl-9 pr-3.5 py-2 text-xs rounded-xl border border-[#E4E7EC] bg-white text-[#202124] focus:outline-none focus:border-[#D71920]">
      </div>

      <div class="flex flex-wrap items-center gap-2">
        <!-- Filtro -->
        <div class="flex items-center gap-1 p-1 bg-[#F5F6F8] rounded-xl border border-[#E4E7EC]">
          <?php foreach (['todas' => 'Todas', 'em-uso' => 'Em uso', 'orfas' => 'Órfãs'] as $chave => $rotulo): ?>
            <button type="button" @click="filter = '<?= $chave ?>'" class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-colors whitespace-nowrap cursor-pointer" :class="filter === '<?= $chave ?>' ? 'bg-white text-[#D71920] shadow-2xs' : 'text-[#697386] hover:text-[#202124]'"><?= $rotulo ?></button>
          <?php endforeach; ?>
        </div>

        <!-- Visão: miniaturas ou lista -->
        <div class="flex items-center gap-1 p-1 bg-[#F5F6F8] rounded-xl border border-[#E4E7EC]">
          <button type="button" @click="view = 'miniaturas'" title="Visão em miniaturas" aria-label="Visão em miniaturas" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg transition-colors cursor-pointer" :class="view === 'miniaturas' ? 'bg-white text-[#D71920] shadow-2xs' : 'text-[#697386] hover:text-[#202124]'">
            <?= icon('LayoutGrid', 'w-3.5 h-3.5') ?><span class="hidden sm:inline">Miniaturas</span>
          </button>
          <button type="button" @click="view = 'lista'" title="Visão em lista" aria-label="Visão em lista" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg transition-colors cursor-pointer" :class="view === 'lista' ? 'bg-white text-[#D71920] shadow-2xs' : 'text-[#697386] hover:text-[#202124]'">
            <?= icon('List', 'w-3.5 h-3.5') ?><span class="hidden sm:inline">Lista</span>
          </button>
        </div>
      </div>
    </div>

    <p class="text-[11px] text-[#697386] mt-3" x-show="visibleCount !== <?= count($items) ?>" x-cloak>
      Exibindo <span class="font-semibold text-[#202124]" x-text="visibleCount"></span> de <?= count($items) ?> imagens.
    </p>
  </div>

  <?php if ($items === []): ?>
    <div class="bg-white rounded-2xl border border-[#E4E7EC] p-10 text-center shadow-2xs space-y-2">
      <div class="flex justify-center text-[#697386]"><?= icon('Image', 'w-8 h-8') ?></div>
      <p class="text-xs text-[#697386]">Nenhuma imagem na biblioteca ainda. Use <strong>Enviar Imagens</strong> para começar.</p>
    </div>
  <?php endif; ?>

  <!-- 3a. Visão em miniaturas -->
  <div x-show="view === 'miniaturas'"<?= $view !== 'miniaturas' ? ' x-cloak' : '' ?> class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
    <?php foreach ($items as $i => $item): ?>
      <div x-show="isVisible(<?= $i ?>)" class="bg-white rounded-2xl border border-[#E4E7EC] shadow-2xs overflow-hidden flex flex-col group">
        <button type="button" @click="open(<?= $i ?>)" class="relative block w-full aspect-square bg-[#F5F6F8] overflow-hidden cursor-pointer" title="Ver detalhes de <?= e($item['filename']) ?>">
          <img src="<?= e($item['url']) ?>" alt="<?= e($item['filename']) ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy">
          <?php if ($item['orphan']): ?>
            <span class="absolute top-2 left-2 inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-800 border border-amber-200">
              <?= icon('Unlink', 'w-3 h-3') ?> Órfã
            </span>
          <?php elseif ($item['in_code']): ?>
            <span class="absolute top-2 left-2 inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#F5F6F8] text-[#D71920] border border-[#E4E7EC]">
              <?= icon('Lock', 'w-3 h-3') ?> No código
            </span>
          <?php else: ?>
            <span class="absolute top-2 left-2 inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
              <?= icon('CheckCircle2', 'w-3 h-3') ?> <?= (int) $item['usage_count'] ?> uso<?= (int) $item['usage_count'] === 1 ? '' : 's' ?>
            </span>
          <?php endif; ?>
        </button>

        <div class="p-3 space-y-1.5 flex-1 flex flex-col">
          <div class="font-bold text-xs text-[#202124] truncate" title="<?= e($item['filename']) ?>"><?= e($item['filename']) ?></div>
          <div class="flex items-center justify-between text-[10px] text-[#697386] font-mono-numbers">
            <span><?= e($item['dimensions']) ?></span>
            <span class="font-semibold"><?= e($item['size_label']) ?></span>
          </div>
          <div class="pt-2 mt-auto border-t border-[#E4E7EC] flex items-center justify-between gap-2">
            <button type="button" @click="open(<?= $i ?>)" class="text-[11px] font-semibold text-[#D71920] hover:underline cursor-pointer">Detalhes</button>
            <?php if ($item['deletable']): ?>
              <form method="post" action="/painel/biblioteca/excluir" onsubmit="return confirm(<?= e(json_script('Apagar definitivamente "' . $item['filename'] . '"? O arquivo será removido do servidor.')) ?>)">
                <?= csrf_field() ?>
                <input type="hidden" name="path" value="<?= e($item['path']) ?>">
                <button type="submit" class="p-1.5 rounded-lg border border-[#E4E7EC] hover:bg-red-50 text-red-600 transition-colors cursor-pointer" title="Apagar imagem">
                  <?= icon('Trash2', 'w-3.5 h-3.5') ?>
                </button>
              </form>
            <?php else: ?>
              <span class="p-1.5 text-neutral-300" title="Em uso: não pode ser apagada"><?= icon('Trash2', 'w-3.5 h-3.5') ?></span>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- 3b. Visão em lista -->
  <div x-show="view === 'lista'"<?= $view !== 'lista' ? ' x-cloak' : '' ?> class="bg-white rounded-2xl border border-[#E4E7EC] shadow-2xs overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead>
          <tr class="bg-[#F5F6F8] border-b border-[#E4E7EC] text-[#697386] uppercase tracking-wider text-[10px]">
            <th class="py-3 px-4 font-bold">Imagem</th>
            <th class="py-3 px-4 font-bold">Arquivo</th>
            <th class="py-3 px-4 font-bold">Dimensões</th>
            <th class="py-3 px-4 font-bold">Peso</th>
            <th class="py-3 px-4 font-bold">Anexada em</th>
            <th class="py-3 px-4 font-bold">Enviada</th>
            <th class="py-3 px-4 font-bold text-right">Ações</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[#E4E7EC]">
          <tr x-show="visibleCount === 0"<?= $items !== [] ? ' x-cloak' : '' ?>>
            <td colspan="7" class="py-12 text-center text-[#697386]">Nenhuma imagem encontrada para o filtro selecionado.</td>
          </tr>
          <?php foreach ($items as $i => $item): ?>
            <tr x-show="isVisible(<?= $i ?>)" class="hover:bg-[#F5F6F8]/60 transition-colors">
              <td class="py-3 px-4">
                <button type="button" @click="open(<?= $i ?>)" class="block w-12 h-12 rounded-lg overflow-hidden border border-[#E4E7EC] bg-[#F5F6F8] cursor-pointer" title="Ver detalhes">
                  <img src="<?= e($item['url']) ?>" alt="<?= e($item['filename']) ?>" class="w-full h-full object-cover" loading="lazy">
                </button>
              </td>
              <td class="py-3 px-4 max-w-[260px]">
                <div class="font-bold text-[#202124] truncate" title="<?= e($item['filename']) ?>"><?= e($item['filename']) ?></div>
                <div class="text-[10px] font-mono text-[#697386] truncate"><?= e($item['path']) ?></div>
              </td>
              <td class="py-3 px-4 font-mono text-[#697386] whitespace-nowrap"><?= e($item['dimensions']) ?></td>
              <td class="py-3 px-4 font-mono font-bold text-[#202124] whitespace-nowrap"><?= e($item['size_label']) ?></td>
              <td class="py-3 px-4">
                <?php if ($item['in_code']): ?>
                  <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#F5F6F8] text-[#D71920] border border-[#E4E7EC]">
                    <?= icon('Lock', 'w-3 h-3') ?> Usada pelo código do site
                  </span>
                <?php elseif ($item['usage'] === []): ?>
                  <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                    <?= icon('Unlink', 'w-3 h-3') ?> Órfã
                  </span>
                <?php else: ?>
                  <div class="space-y-1">
                    <?php foreach (array_slice($item['usage'], 0, 3) as $uso): ?>
                      <a href="<?= e($uso['url']) ?>" class="block text-[11px] text-[#202124] hover:text-[#D71920] truncate max-w-[240px]">
                        <span class="text-[#697386]"><?= e($uso['label']) ?>:</span> <?= e($uso['title']) ?>
                      </a>
                    <?php endforeach; ?>
                    <?php if (count($item['usage']) > 3): ?>
                      <button type="button" @click="open(<?= $i ?>)" class="text-[10px] font-semibold text-[#D71920] hover:underline cursor-pointer">
                        +<?= count($item['usage']) - 3 ?> outro<?= count($item['usage']) - 3 === 1 ? '' : 's' ?>
                      </button>
                    <?php endif; ?>
                  </div>
                <?php endif; ?>
              </td>
              <td class="py-3 px-4 text-[11px] text-[#697386] whitespace-nowrap"><?= e($item['modified_label']) ?></td>
              <td class="py-3 px-4 text-right whitespace-nowrap">
                <div class="inline-flex items-center gap-2">
                  <button type="button" @click="open(<?= $i ?>)" class="p-1.5 rounded-lg border border-[#E4E7EC] hover:bg-neutral-100 text-[#202124] transition-colors cursor-pointer" title="Ver detalhes">
                    <?= icon('Eye', 'w-3.5 h-3.5') ?>
                  </button>
                  <a href="<?= e($item['url']) ?>" target="_blank" rel="noopener" class="p-1.5 rounded-lg border border-[#E4E7EC] hover:bg-neutral-100 text-[#202124] transition-colors" title="Abrir em nova aba">
                    <?= icon('ExternalLink', 'w-3.5 h-3.5') ?>
                  </a>
                  <?php if ($item['deletable']): ?>
                    <form method="post" action="/painel/biblioteca/excluir" onsubmit="return confirm(<?= e(json_script('Apagar definitivamente "' . $item['filename'] . '"? O arquivo será removido do servidor.')) ?>)">
                      <?= csrf_field() ?>
                      <input type="hidden" name="path" value="<?= e($item['path']) ?>">
                      <button type="submit" class="p-1.5 rounded-lg border border-[#E4E7EC] hover:bg-red-50 text-red-600 transition-colors cursor-pointer" title="Apagar imagem">
                        <?= icon('Trash2', 'w-3.5 h-3.5') ?>
                      </button>
                    </form>
                  <?php else: ?>
                    <span class="p-1.5 text-neutral-300" title="Em uso: não pode ser apagada"><?= icon('Trash2', 'w-3.5 h-3.5') ?></span>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- 4. Modal de detalhes -->
  <template x-if="detail">
    <div class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto" @click.self="detail = null">
      <div class="bg-white rounded-2xl border border-[#E4E7EC] max-w-3xl w-full p-6 shadow-2xl space-y-5 my-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-between pb-3 border-b border-[#E4E7EC] gap-3">
          <h3 class="font-heading font-bold text-base text-[#202124] truncate" x-text="detail.filename"></h3>
          <button type="button" @click="detail = null" class="text-[#697386] hover:text-[#202124] p-1 rounded shrink-0" aria-label="Fechar"><?= icon('X', 'w-5 h-5') ?></button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
          <div class="rounded-xl overflow-hidden border border-[#E4E7EC] bg-[#F5F6F8]">
            <img :src="detail.url" :alt="detail.filename" class="w-full h-auto max-h-72 object-contain">
          </div>

          <div class="space-y-3 text-xs">
            <div class="grid grid-cols-2 gap-3">
              <div class="p-3 rounded-xl bg-[#F5F6F8] border border-[#E4E7EC]">
                <span class="text-[10px] font-semibold uppercase text-[#697386] block">Dimensões</span>
                <span class="font-bold text-[#202124] font-mono-numbers" x-text="detail.dimensions"></span>
                <span class="text-[10px] text-[#697386] block" x-show="detail.megapixels > 0" x-text="detail.megapixels + ' MP'"></span>
              </div>
              <div class="p-3 rounded-xl bg-[#F5F6F8] border border-[#E4E7EC]">
                <span class="text-[10px] font-semibold uppercase text-[#697386] block">Peso</span>
                <span class="font-bold text-[#202124] font-mono-numbers" x-text="detail.size_label"></span>
                <span class="text-[10px] text-[#697386] block uppercase" x-text="detail.ext"></span>
              </div>
            </div>

            <div>
              <span class="text-[10px] font-semibold uppercase text-[#697386] block mb-1">Caminho no servidor</span>
              <input type="text" :value="detail.path" readonly @focus="$el.select()" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-[#F5F6F8] text-[11px] font-mono text-[#202124]">
            </div>

            <div>
              <span class="text-[10px] font-semibold uppercase text-[#697386] block mb-1">Enviada em</span>
              <span class="text-[#202124]" x-text="detail.modified_label"></span>
            </div>
          </div>
        </div>

        <!-- Onde está anexada -->
        <div class="pt-3 border-t border-[#E4E7EC] space-y-2">
          <span class="text-[10px] font-bold uppercase tracking-wider text-[#697386] block">Anexada em</span>

          <template x-if="detail.in_code">
            <div class="p-3 rounded-xl bg-[#F5F6F8] border border-[#E4E7EC] text-xs text-[#697386] flex items-start gap-2">
              <span class="text-[#D71920] shrink-0 mt-0.5"><?= icon('Lock', 'w-4 h-4') ?></span>
              <span>Esta imagem está escrita diretamente no código do site (logo, ícone ou imagem padrão de compartilhamento). Por isso não pode ser apagada pelo painel.</span>
            </div>
          </template>

          <template x-if="!detail.in_code && detail.usage.length === 0">
            <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900 flex items-start gap-2">
              <span class="shrink-0 mt-0.5"><?= icon('Unlink', 'w-4 h-4') ?></span>
              <span>Nenhum cadastro usa esta imagem. Ela está ocupando espaço no servidor e pode ser apagada com segurança.</span>
            </div>
          </template>

          <template x-if="detail.usage.length > 0">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
              <template x-for="uso in detail.usage" :key="uso.label + uso.title">
                <a :href="uso.url" class="flex items-center gap-2 p-2.5 rounded-xl bg-[#F5F6F8] border border-[#E4E7EC] hover:border-[#D71920] transition-colors">
                  <span class="text-[10px] font-bold uppercase text-[#697386] shrink-0" x-text="uso.label"></span>
                  <span class="text-xs font-semibold text-[#202124] truncate" x-text="uso.title"></span>
                </a>
              </template>
            </div>
          </template>
        </div>

        <div class="pt-3 border-t border-[#E4E7EC] flex flex-wrap items-center justify-between gap-3">
          <a :href="detail.url" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-[#E4E7EC] bg-white hover:bg-neutral-50 text-xs font-semibold text-[#202124] transition-colors">
            <?= icon('ExternalLink', 'w-3.5 h-3.5 text-[#D71920]') ?>
            <span>Abrir imagem</span>
          </a>

          <div class="flex items-center gap-2">
            <button type="button" @click="detail = null" class="px-4 py-2 rounded-lg border border-[#E4E7EC] text-xs font-semibold text-[#697386] hover:bg-neutral-50 transition-colors cursor-pointer">Fechar</button>
            <template x-if="detail.deletable">
              <form method="post" action="/painel/biblioteca/excluir" @submit="if (!confirm('Apagar definitivamente ' + detail.filename + '? O arquivo será removido do servidor.')) $event.preventDefault()">
                <?= csrf_field() ?>
                <input type="hidden" name="path" :value="detail.path">
                <button type="submit" class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-semibold transition-colors inline-flex items-center gap-1.5 shadow-xs cursor-pointer">
                  <?= icon('Trash2', 'w-3.5 h-3.5') ?>
                  <span>Apagar imagem</span>
                </button>
              </form>
            </template>
          </div>
        </div>
      </div>
    </div>
  </template>

  <!-- 5. Modal de envio -->
  <div x-show="uploadOpen" x-cloak class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto" @click.self="uploadOpen = false">
    <div class="bg-white rounded-2xl border border-[#E4E7EC] max-w-lg w-full p-6 shadow-2xl space-y-4 my-auto" role="dialog" aria-modal="true">
      <div class="flex items-center justify-between pb-3 border-b border-[#E4E7EC]">
        <h3 class="font-heading font-bold text-base text-[#202124]">Enviar Imagens</h3>
        <button type="button" @click="uploadOpen = false" class="text-[#697386] hover:text-[#202124] p-1 rounded" aria-label="Fechar"><?= icon('X', 'w-5 h-5') ?></button>
      </div>

      <form method="post" action="/painel/biblioteca" enctype="multipart/form-data" class="space-y-4" x-ref="uploadForm" @submit="sending = true">
        <?= csrf_field() ?>

        <label
          class="block p-6 rounded-xl border-2 border-dashed transition-colors cursor-pointer text-center"
          :class="dragging ? 'border-[#D71920] bg-[#D71920]/5' : 'border-[#E4E7EC] bg-[#F5F6F8] hover:border-[#D71920]/40'"
          @dragover.prevent="dragging = true"
          @dragleave.prevent="dragging = false"
          @drop.prevent="dropFiles($event)"
        >
          <div class="flex justify-center text-[#D71920]"><?= icon('Upload', 'w-7 h-7') ?></div>
          <span class="block text-xs font-semibold text-[#202124] mt-2">Arraste as imagens aqui ou clique para escolher</span>
          <span class="block text-[10px] text-[#697386] mt-1"><?= e($uploadHint) ?></span>
          <input type="file" name="images[]" multiple accept="<?= e($aceita) ?>" x-ref="uploadInput" class="sr-only" @change="pickFiles($event)">
        </label>

        <div x-show="chosen.length > 0" x-cloak class="space-y-1.5">
          <span class="text-[10px] font-bold uppercase tracking-wider text-[#697386]">Selecionadas</span>
          <template x-for="(nome, idx) in chosen" :key="idx">
            <div class="flex items-center justify-between gap-2 text-xs p-2 rounded-lg bg-[#F5F6F8] border border-[#E4E7EC]">
              <span class="truncate text-[#202124]" x-text="nome"></span>
            </div>
          </template>
        </div>

        <div class="pt-3 border-t border-[#E4E7EC] flex justify-end gap-2">
          <button type="button" @click="uploadOpen = false" class="px-4 py-2 rounded-lg border border-[#E4E7EC] text-xs font-semibold text-[#697386] hover:bg-neutral-50 transition-colors cursor-pointer">Cancelar</button>
          <button type="submit" :disabled="chosen.length === 0 || sending" class="px-5 py-2 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold shadow-xs transition-colors inline-flex items-center gap-1.5 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
            <?= icon('Upload', 'w-3.5 h-3.5') ?>
            <span x-text="sending ? 'Enviando...' : 'Enviar para a Biblioteca'"></span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
