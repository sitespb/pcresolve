<?php
/**
 * Catálogo de serviços: listagem, criação e edição (com escolha de imagem).
 *
 * @var list<array<string, mixed>> $services
 * @var list<array<string, mixed>> $media        Acervo para o seletor da biblioteca
 * @var string                     $uploadHint
 * @var string                     $defaultImage
 */
use App\Controllers\Admin\MediaController;
use App\Support\MediaLibrary;

$adminTitle = 'Catálogo de Serviços';
$adminSubtitle = 'Gerenciamento dos serviços oferecidos, tabela de preços de entrada, imagens e visibilidade no site';
$aceita = MediaController::acceptAttr();
$categorias = ['hardware' => 'Hardware', 'software' => 'Software', 'preventiva' => 'Preventiva', 'corporativo' => 'Corporativo'];

ob_start(); ?>
<button type="button" @click="$dispatch('new-item')" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold shadow-xs transition-colors cursor-pointer">
  <?= icon('Plus', 'w-3.5 h-3.5') ?>
  <span>Novo Serviço</span>
</button>
<?php $adminAction = ob_get_clean();

$rows = array_map(fn ($s) => mb_strtolower($s['title'] . "\n" . $s['short_desc']), $services);
?>
<div
  class="space-y-6"
  x-data="servicesPage(<?= json_attr([
      'rows' => $rows,
      'newOpen' => input('novo') !== null,
      'media' => $media,
      'placeholder' => image_url($defaultImage),
  ]) ?>)"
  @new-item.window="newOpen = true; newImage = ''; clearFile('new')"
  @keydown.escape.window="if (pickerOpen) { pickerOpen = false } else { editing = null; newOpen = false }"
>
  <div class="bg-white rounded-2xl border border-[#E4E7EC] p-4 shadow-2xs">
    <div class="relative max-w-md">
      <?= icon('Search', 'w-4 h-4 text-[#697386] absolute left-3.5 top-1/2 -translate-y-1/2') ?>
      <input type="text" x-model="search" placeholder="Pesquisar serviços cadastrados..." aria-label="Pesquisar serviços" class="w-full pl-9 pr-3.5 py-2 text-xs rounded-xl border border-[#E4E7EC] bg-white text-[#202124] focus:outline-none focus:border-[#D71920]">
    </div>
  </div>

  <div class="bg-white rounded-2xl border border-[#E4E7EC] shadow-2xs overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead>
          <tr class="bg-[#F5F6F8] border-b border-[#E4E7EC] text-[#697386] uppercase tracking-wider text-[10px]">
            <th class="py-3 px-4 font-bold">Serviço</th>
            <th class="py-3 px-4 font-bold">Categoria</th>
            <th class="py-3 px-4 font-bold">Preço de Entrada</th>
            <th class="py-3 px-4 font-bold">Prazo Estimado</th>
            <th class="py-3 px-4 font-bold">Garantia</th>
            <th class="py-3 px-4 font-bold">Status</th>
            <th class="py-3 px-4 font-bold text-right">Ações</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[#E4E7EC]">
          <tr x-show="visibleCount === 0"<?= $services !== [] ? ' x-cloak' : '' ?>>
            <td colspan="7" class="py-12 text-center text-[#697386]">Nenhum serviço encontrado.</td>
          </tr>
          <?php foreach ($services as $i => $service): ?>
            <tr x-show="isVisible(<?= $i ?>)" class="hover:bg-[#F5F6F8]/60 transition-colors">
              <td class="py-3.5 px-4">
                <div class="flex items-center gap-3">
                  <img src="<?= e(image_url($service['image'])) ?>" alt="<?= e($service['title']) ?>" class="w-10 h-10 rounded-lg object-cover border border-[#E4E7EC] bg-[#F5F6F8]" loading="lazy">
                  <div>
                    <div class="font-bold text-[#202124]"><?= e($service['title']) ?></div>
                    <div class="text-[11px] text-[#697386] truncate max-w-[260px]"><?= e($service['short_desc']) ?></div>
                  </div>
                </div>
              </td>
              <td class="py-3.5 px-4 capitalize font-semibold text-[#697386]"><?= e($service['category']) ?></td>
              <td class="py-3.5 px-4 font-mono font-bold text-[#202124]">R$ <?= fixed2($service['price_starting_at']) ?></td>
              <td class="py-3.5 px-4 text-[#697386]"><?= e($service['turnaround_time']) ?></td>
              <td class="py-3.5 px-4 text-[#202124]"><?= (int) $service['warranty_days'] ?> dias</td>
              <td class="py-3.5 px-4">
                <form method="post" action="/painel/servicos/<?= $service['id'] ?>/status">
                  <?= csrf_field() ?>
                  <button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold border cursor-pointer transition-colors <?= $service['active'] ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-neutral-100 text-neutral-600 border-neutral-300' ?>" title="<?= $service['active'] ? 'Clique para pausar no site público' : 'Clique para ativar no site público' ?>">
                    <?php if ($service['active']): ?>
                      <?= icon('CheckCircle2', 'w-3 h-3 text-emerald-600') ?><span>Ativo</span>
                    <?php else: ?>
                      <?= icon('XCircle', 'w-3 h-3 text-neutral-500') ?><span>Pausado</span>
                    <?php endif; ?>
                  </button>
                </form>
              </td>
              <td class="py-3.5 px-4 text-right whitespace-nowrap">
                <div class="inline-flex items-center gap-2">
                  <button type="button" @click="edit(<?= json_attr([
                      'id' => $service['id'],
                      'title' => $service['title'],
                      'short_desc' => $service['short_desc'],
                      'category' => $service['category'],
                      'price_starting_at' => $service['price_starting_at'],
                      'turnaround_time' => $service['turnaround_time'],
                      'image' => MediaLibrary::normalize((string) $service['image']),
                  ]) ?>)" class="p-1.5 rounded-lg border border-[#E4E7EC] hover:bg-neutral-100 text-[#202124] transition-colors cursor-pointer" title="Editar Serviço">
                    <?= icon('Edit2', 'w-3.5 h-3.5') ?>
                  </button>
                  <form method="post" action="/painel/servicos/<?= $service['id'] ?>/excluir" onsubmit="return confirm(<?= e(json_script('Remover o serviço "' . $service['title'] . '"? A imagem continua na Biblioteca de Mídia.')) ?>)">
                    <?= csrf_field() ?>
                    <button type="submit" class="p-1.5 rounded-lg border border-[#E4E7EC] hover:bg-red-50 text-red-600 transition-colors cursor-pointer" title="Excluir Serviço">
                      <?= icon('Trash2', 'w-3.5 h-3.5') ?>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal: editar serviço -->
  <template x-if="editing">
    <div class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto" @click.self="editing = null">
      <div class="bg-white rounded-2xl border border-[#E4E7EC] max-w-lg w-full p-6 shadow-2xl space-y-4 my-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-between pb-3 border-b border-[#E4E7EC]">
          <h3 class="font-heading font-bold text-base text-[#202124]">Editar Serviço</h3>
          <button type="button" @click="editing = null" class="text-[#697386] hover:text-[#202124]" aria-label="Fechar"><?= icon('X', 'w-5 h-5') ?></button>
        </div>

        <form method="post" :action="'/painel/servicos/' + editing.id" enctype="multipart/form-data" class="space-y-3.5 text-xs">
          <?= csrf_field() ?>

          <!-- Imagem -->
          <div>
            <span class="block text-xs font-semibold text-[#202124] mb-1">Imagem do Serviço</span>
            <div class="flex items-start gap-3 p-3 rounded-xl border border-[#E4E7EC] bg-[#F5F6F8]">
              <img :src="previewUrl('edit')" alt="Imagem do serviço" class="w-20 h-20 rounded-lg object-cover border border-[#E4E7EC] bg-white shrink-0">
              <div class="flex-1 min-w-0 space-y-2">
                <div class="flex flex-wrap items-center gap-2">
                  <button type="button" @click="$refs.editFile.click()" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border border-[#E4E7EC] bg-white hover:bg-neutral-50 text-[11px] font-semibold text-[#202124] transition-colors cursor-pointer">
                    <?= icon('Upload', 'w-3.5 h-3.5 text-[#D71920]') ?>
                    <span>Enviar do computador</span>
                  </button>
                  <button type="button" @click="openPicker('edit')" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border border-[#E4E7EC] bg-white hover:bg-neutral-50 text-[11px] font-semibold text-[#202124] transition-colors cursor-pointer">
                    <?= icon('Image', 'w-3.5 h-3.5 text-[#D71920]') ?>
                    <span>Escolher da biblioteca</span>
                  </button>
                </div>
                <p class="text-[10px] font-mono text-[#697386] truncate" x-show="!editFileName" x-text="editing.image || 'Nenhuma imagem definida'"></p>
                <p class="text-[10px] text-emerald-700 font-semibold" x-show="editFileName" x-cloak>
                  <span x-text="editFileName"></span> — será enviada ao salvar
                  <button type="button" @click="clearFile('edit')" class="underline font-semibold text-[#697386] hover:text-[#D71920] cursor-pointer ml-1">cancelar</button>
                </p>
                <p class="text-[10px] text-[#697386]"><?= e($uploadHint) ?></p>
              </div>
            </div>
            <input type="file" name="image_file" accept="<?= e($aceita) ?>" x-ref="editFile" class="sr-only" @change="pickFile($event, 'edit')">
            <input type="hidden" name="image_path" :value="editing.image">
          </div>

          <div>
            <label class="block text-xs font-semibold text-[#202124] mb-1" for="edit-title">Título do Serviço</label>
            <input id="edit-title" type="text" name="title" required maxlength="190" x-model="editing.title" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]">
          </div>
          <div>
            <label class="block text-xs font-semibold text-[#202124] mb-1" for="edit-short">Descrição Curta (Exibida no Card)</label>
            <textarea id="edit-short" name="short_desc" rows="2" maxlength="2000" x-model="editing.short_desc" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]"></textarea>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-semibold text-[#202124] mb-1" for="edit-category">Categoria</label>
              <select id="edit-category" name="category" x-model="editing.category" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]">
                <?php foreach ($categorias as $valor => $rotulo): ?>
                  <option value="<?= e($valor) ?>"><?= e($rotulo) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="block text-xs font-semibold text-[#202124] mb-1" for="edit-price">Preço Inicial (R$)</label>
              <input id="edit-price" type="number" name="price_starting_at" min="0" step="0.01" x-model="editing.price_starting_at" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]">
            </div>
          </div>
          <div>
            <label class="block text-xs font-semibold text-[#202124] mb-1" for="edit-turnaround">Prazo Médio</label>
            <input id="edit-turnaround" type="text" name="turnaround_time" maxlength="100" x-model="editing.turnaround_time" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]">
          </div>
          <div class="pt-3 border-t border-[#E4E7EC] flex justify-end gap-2">
            <button type="button" @click="editing = null" class="px-4 py-2 rounded-lg border border-[#E4E7EC] text-xs font-semibold text-[#697386] cursor-pointer">Cancelar</button>
            <button type="submit" class="px-5 py-2 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold shadow-xs cursor-pointer">Salvar</button>
          </div>
        </form>
      </div>
    </div>
  </template>

  <!-- Modal: novo serviço -->
  <div x-show="newOpen" x-cloak class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto" @click.self="newOpen = false">
    <div class="bg-white rounded-2xl border border-[#E4E7EC] max-w-lg w-full p-6 shadow-2xl space-y-4 my-auto" role="dialog" aria-modal="true">
      <div class="flex items-center justify-between pb-3 border-b border-[#E4E7EC]">
        <h3 class="font-heading font-bold text-base text-[#202124]">Adicionar Novo Serviço</h3>
        <button type="button" @click="newOpen = false" class="text-[#697386] hover:text-[#202124]" aria-label="Fechar"><?= icon('X', 'w-5 h-5') ?></button>
      </div>

      <form method="post" action="/painel/servicos" enctype="multipart/form-data" class="space-y-3.5 text-xs">
        <?= csrf_field() ?>

        <!-- Imagem -->
        <div>
          <span class="block text-xs font-semibold text-[#202124] mb-1">Imagem do Serviço</span>
          <div class="flex items-start gap-3 p-3 rounded-xl border border-[#E4E7EC] bg-[#F5F6F8]">
            <img :src="previewUrl('new')" alt="Imagem do serviço" class="w-20 h-20 rounded-lg object-cover border border-[#E4E7EC] bg-white shrink-0">
            <div class="flex-1 min-w-0 space-y-2">
              <div class="flex flex-wrap items-center gap-2">
                <button type="button" @click="$refs.newFile.click()" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border border-[#E4E7EC] bg-white hover:bg-neutral-50 text-[11px] font-semibold text-[#202124] transition-colors cursor-pointer">
                  <?= icon('Upload', 'w-3.5 h-3.5 text-[#D71920]') ?>
                  <span>Enviar do computador</span>
                </button>
                <button type="button" @click="openPicker('new')" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border border-[#E4E7EC] bg-white hover:bg-neutral-50 text-[11px] font-semibold text-[#202124] transition-colors cursor-pointer">
                  <?= icon('Image', 'w-3.5 h-3.5 text-[#D71920]') ?>
                  <span>Escolher da biblioteca</span>
                </button>
              </div>
              <p class="text-[10px] font-mono text-[#697386] truncate" x-show="!newFileName" x-text="newImage || 'Nenhuma escolhida — será usada a imagem padrão'"></p>
              <p class="text-[10px] text-emerald-700 font-semibold" x-show="newFileName" x-cloak>
                <span x-text="newFileName"></span> — será enviada ao salvar
                <button type="button" @click="clearFile('new')" class="underline font-semibold text-[#697386] hover:text-[#D71920] cursor-pointer ml-1">cancelar</button>
              </p>
              <p class="text-[10px] text-[#697386]"><?= e($uploadHint) ?></p>
            </div>
          </div>
          <input type="file" name="image_file" accept="<?= e($aceita) ?>" x-ref="newFile" class="sr-only" @change="pickFile($event, 'new')">
          <input type="hidden" name="image_path" :value="newImage">
        </div>

        <div>
          <label class="block text-xs font-semibold text-[#202124] mb-1" for="new-title">Nome do Serviço *</label>
          <input id="new-title" type="text" name="title" required maxlength="190" value="<?= e(old('title')) ?>" placeholder="Ex: Troca de Bateria para MacBook" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]">
        </div>
        <div>
          <label class="block text-xs font-semibold text-[#202124] mb-1" for="new-category">Categoria</label>
          <select id="new-category" name="category" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]">
            <?php foreach ($categorias as $valor => $rotulo): ?>
              <option value="<?= e($valor) ?>"<?= old('category', 'hardware') === $valor ? ' selected' : '' ?>><?= e($rotulo) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-semibold text-[#202124] mb-1" for="new-price">Preço Inicial (R$)</label>
            <input id="new-price" type="number" name="price_starting_at" min="0" step="0.01" value="<?= e(old('price_starting_at', '100')) ?>" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]">
          </div>
          <div>
            <label class="block text-xs font-semibold text-[#202124] mb-1" for="new-turnaround">Prazo Médio</label>
            <input id="new-turnaround" type="text" name="turnaround_time" maxlength="100" value="<?= e(old('turnaround_time', '24h a 48h úteis')) ?>" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]">
          </div>
        </div>
        <div>
          <label class="block text-xs font-semibold text-[#202124] mb-1" for="new-short">Descrição Curta</label>
          <textarea id="new-short" name="short_desc" rows="2" maxlength="2000" placeholder="Breve resumo para o catálogo..." class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]"><?= e(old('short_desc')) ?></textarea>
        </div>
        <div class="pt-3 border-t border-[#E4E7EC] flex justify-end gap-2">
          <button type="button" @click="newOpen = false" class="px-4 py-2 rounded-lg border border-[#E4E7EC] text-xs font-semibold text-[#697386] cursor-pointer">Cancelar</button>
          <button type="submit" class="px-5 py-2 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold shadow-xs cursor-pointer">Adicionar Serviço</button>
        </div>
      </form>
    </div>
  </div>

  <!--
    Seletor da biblioteca de mídia. Fica fora dos dois formulários de propósito:
    é compartilhado pelos modais de criar e editar, e precisa aparecer acima deles
    (z-60) sem virar um formulário dentro de outro.
  -->
  <div x-show="pickerOpen" x-cloak class="fixed inset-0 z-[60] bg-black/60 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto" @click.self="pickerOpen = false">
    <div class="bg-white rounded-2xl border border-[#E4E7EC] max-w-3xl w-full p-6 shadow-2xl space-y-4 my-auto" role="dialog" aria-modal="true">
      <div class="flex items-center justify-between pb-3 border-b border-[#E4E7EC] gap-3">
        <div>
          <h3 class="font-heading font-bold text-base text-[#202124]">Escolher da Biblioteca</h3>
          <p class="text-xs text-[#697386]">Reaproveite uma imagem que já está no servidor — não ocupa espaço novo</p>
        </div>
        <button type="button" @click="pickerOpen = false" class="text-[#697386] hover:text-[#202124] p-1 rounded shrink-0" aria-label="Fechar"><?= icon('X', 'w-5 h-5') ?></button>
      </div>

      <div class="relative">
        <?= icon('Search', 'w-4 h-4 text-[#697386] absolute left-3.5 top-1/2 -translate-y-1/2') ?>
        <input type="text" x-model="pickerQuery" placeholder="Buscar pelo nome do arquivo..." aria-label="Buscar na biblioteca" class="w-full pl-9 pr-3.5 py-2 text-xs rounded-xl border border-[#E4E7EC] bg-white focus:outline-none focus:border-[#D71920]">
      </div>

      <div class="max-h-[22rem] overflow-y-auto">
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
          <template x-for="item in pickerItems" :key="item.path">
            <button type="button" @click="choose(item)" class="text-left bg-white rounded-xl border border-[#E4E7EC] hover:border-[#D71920] overflow-hidden transition-colors cursor-pointer group">
              <span class="block aspect-square bg-[#F5F6F8] overflow-hidden">
                <img :src="item.url" :alt="item.filename" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy">
              </span>
              <span class="block p-2 space-y-0.5">
                <span class="block text-[11px] font-bold text-[#202124] truncate" x-text="item.filename"></span>
                <span class="flex items-center justify-between text-[10px] text-[#697386] font-mono-numbers">
                  <span x-text="item.dimensions"></span>
                  <span x-text="item.size_label"></span>
                </span>
                <span class="block text-[10px] text-emerald-700 font-semibold" x-show="item.usage_count > 0" x-text="item.usage_count + ' uso(s)'"></span>
              </span>
            </button>
          </template>
        </div>

        <p x-show="pickerItems.length === 0" x-cloak class="py-10 text-center text-xs text-[#697386]">
          Nenhuma imagem encontrada. Envie imagens na <a href="/painel/biblioteca" class="font-semibold text-[#D71920] hover:underline">Biblioteca de Mídia</a>.
        </p>
      </div>

      <div class="pt-3 border-t border-[#E4E7EC] flex items-center justify-between gap-3">
        <a href="/painel/biblioteca" class="text-[11px] font-semibold text-[#D71920] hover:underline">Abrir a Biblioteca de Mídia &rarr;</a>
        <button type="button" @click="pickerOpen = false" class="px-4 py-2 rounded-lg border border-[#E4E7EC] text-xs font-semibold text-[#697386] hover:bg-neutral-50 transition-colors cursor-pointer">Cancelar</button>
      </div>
    </div>
  </div>
</div>
