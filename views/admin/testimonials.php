<?php
/** @var list<array<string, mixed>> $testimonials */
$adminTitle = 'Moderação de Avaliações & Depoimentos';
$adminSubtitle = 'Aprovação, edição e controle das opiniões verificadas de clientes publicadas no website';

$notas = [5 => '5 estrelas (Excelente)', 4 => '4 estrelas (Muito bom)', 3 => '3 estrelas (Regular)', 2 => '2 estrelas (Ruim)', 1 => '1 estrela (Péssimo)'];

ob_start(); ?>
<button type="button" @click="$dispatch('new-item')" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold shadow-xs transition-colors cursor-pointer">
  <?= icon('Plus', 'w-3.5 h-3.5') ?>
  <span>Novo Depoimento</span>
</button>
<?php $adminAction = ob_get_clean();

$rows = array_map(fn ($t) => mb_strtolower($t['author'] . "\n" . $t['location'] . "\n" . $t['text'] . "\n" . $t['service_title']), $testimonials);
?>
<div
  class="space-y-6"
  x-data="crudPage(<?= json_attr(['rows' => $rows, 'newOpen' => input('novo') !== null]) ?>)"
  @new-item.window="newOpen = true"
  @keydown.escape.window="newOpen = false; editing = null"
>
  <!-- Busca -->
  <div class="bg-white rounded-2xl border border-[#E4E7EC] p-4 shadow-2xs">
    <div class="relative max-w-md">
      <?= icon('Search', 'w-4 h-4 text-[#697386] absolute left-3.5 top-1/2 -translate-y-1/2') ?>
      <input type="text" x-model="search" placeholder="Pesquisar por cliente, bairro ou serviço..." aria-label="Pesquisar depoimentos" class="w-full pl-9 pr-3.5 py-2 text-xs rounded-xl border border-[#E4E7EC] bg-white text-[#202124] focus:outline-none focus:border-[#D71920]">
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php if ($testimonials === []): ?>
      <div class="md:col-span-2 lg:col-span-3 bg-white rounded-2xl border border-[#E4E7EC] p-10 text-center text-xs text-[#697386]">Nenhum depoimento cadastrado.</div>
    <?php endif; ?>

    <div x-show="visibleCount === 0"<?= $testimonials !== [] ? ' x-cloak' : '' ?> class="md:col-span-2 lg:col-span-3 bg-white rounded-2xl border border-[#E4E7EC] p-10 text-center text-xs text-[#697386]">
      Nenhum depoimento encontrado para a busca.
    </div>

    <?php foreach ($testimonials as $i => $test): ?>
      <div x-show="isVisible(<?= $i ?>)" class="bg-white rounded-2xl border border-[#E4E7EC] p-5 shadow-2xs flex flex-col justify-between space-y-4">
        <div class="space-y-3">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-1 text-amber-500" aria-label="<?= (int) $test['rating'] ?> de 5 estrelas">
              <?php for ($s = 0; $s < $test['rating']; $s++): ?>
                <?= icon('Star', 'w-3.5 h-3.5 fill-amber-500') ?>
              <?php endfor; ?>
            </div>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold border <?= $test['approved'] ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-amber-50 text-amber-800 border-amber-200' ?>">
              <?= $test['approved'] ? 'Aprovado &amp; Público' : 'Aguardando Aprovação' ?>
            </span>
          </div>

          <p class="text-xs text-[#202124] italic leading-relaxed">"<?= e($test['text']) ?>"</p>

          <div class="text-[11px] text-[#697386]">
            <strong class="text-[#202124] block"><?= e($test['author']) ?></strong>
            <span><?= e($test['location']) ?> · <?= e($test['date_label']) ?></span>
            <span class="block text-[#D71920] font-medium"><?= e($test['service_title']) ?></span>
          </div>
        </div>

        <div class="pt-3 border-t border-[#E4E7EC] flex items-center justify-between gap-2">
          <!-- Alterar e excluir -->
          <div class="flex items-center gap-2">
            <button type="button" @click="edit(<?= json_attr([
                'id' => $test['id'],
                'author' => $test['author'],
                'location' => $test['location'],
                'rating' => $test['rating'],
                'service_title' => $test['service_title'],
                'text' => $test['text'],
            ]) ?>)" class="p-2 rounded-lg border border-[#E4E7EC] hover:bg-neutral-50 text-[#202124] transition-colors cursor-pointer" title="Editar depoimento">
              <?= icon('Edit2', 'w-3.5 h-3.5') ?>
            </button>
            <form method="post" action="/painel/depoimentos/<?= $test['id'] ?>/excluir" onsubmit="return confirm(<?= e(json_script('Excluir definitivamente o depoimento de "' . $test['author'] . '"?')) ?>)">
              <?= csrf_field() ?>
              <button type="submit" class="p-2 rounded-lg border border-[#E4E7EC] hover:bg-red-50 text-red-600 transition-colors cursor-pointer" title="Excluir depoimento">
                <?= icon('Trash2', 'w-3.5 h-3.5') ?>
              </button>
            </form>
          </div>

          <!-- Publicação -->
          <?php if ($test['approved']): ?>
            <form method="post" action="/painel/depoimentos/<?= $test['id'] ?>/ocultar">
              <?= csrf_field() ?>
              <button type="submit" class="px-3 py-1.5 rounded-lg border border-[#E4E7EC] hover:bg-neutral-50 text-xs font-semibold text-neutral-600 transition-colors cursor-pointer">Ocultar do Site</button>
            </form>
          <?php else: ?>
            <form method="post" action="/painel/depoimentos/<?= $test['id'] ?>/aprovar">
              <?= csrf_field() ?>
              <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold transition-colors flex items-center gap-1 cursor-pointer">
                <?= icon('CheckCircle2', 'w-3.5 h-3.5') ?>
                <span>Aprovar</span>
              </button>
            </form>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Modal: editar depoimento -->
  <template x-if="editing">
    <div class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto" @click.self="editing = null">
      <div class="bg-white rounded-2xl border border-[#E4E7EC] max-w-md w-full p-6 shadow-2xl space-y-4 my-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-between pb-3 border-b border-[#E4E7EC]">
          <h3 class="font-heading font-bold text-base text-[#202124]">Editar Depoimento</h3>
          <button type="button" @click="editing = null" class="text-[#697386] hover:text-[#202124]" aria-label="Fechar"><?= icon('X', 'w-5 h-5') ?></button>
        </div>

        <form method="post" :action="'/painel/depoimentos/' + editing.id" class="space-y-3 text-xs">
          <?= csrf_field() ?>
          <div>
            <label class="block text-xs font-semibold text-[#202124] mb-1" for="t-edit-author">Nome do Cliente *</label>
            <input id="t-edit-author" type="text" name="author" required maxlength="150" x-model="editing.author" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]">
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs font-semibold text-[#202124] mb-1" for="t-edit-location">Bairro / Cidade</label>
              <input id="t-edit-location" type="text" name="location" maxlength="150" x-model="editing.location" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]">
            </div>
            <div>
              <label class="block text-xs font-semibold text-[#202124] mb-1" for="t-edit-rating">Nota (Estrelas)</label>
              <select id="t-edit-rating" name="rating" x-model.number="editing.rating" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]">
                <?php foreach ($notas as $valor => $rotulo): ?>
                  <option value="<?= $valor ?>"><?= e($rotulo) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div>
            <label class="block text-xs font-semibold text-[#202124] mb-1" for="t-edit-service">Serviço Realizado</label>
            <input id="t-edit-service" type="text" name="service_title" maxlength="190" x-model="editing.service_title" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]">
          </div>
          <div>
            <label class="block text-xs font-semibold text-[#202124] mb-1" for="t-edit-text">Texto do Depoimento *</label>
            <textarea id="t-edit-text" name="text" rows="4" required maxlength="2000" x-model="editing.text" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]"></textarea>
          </div>
          <div class="pt-3 border-t border-[#E4E7EC] flex justify-end gap-2">
            <button type="button" @click="editing = null" class="px-4 py-2 rounded-lg border border-[#E4E7EC] text-xs font-semibold text-[#697386] cursor-pointer">Cancelar</button>
            <button type="submit" class="px-5 py-2 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold shadow-xs cursor-pointer">Salvar</button>
          </div>
        </form>
      </div>
    </div>
  </template>

  <!-- Modal: novo depoimento -->
  <div x-show="newOpen" x-cloak class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto" @click.self="newOpen = false">
    <div class="bg-white rounded-2xl border border-[#E4E7EC] max-w-md w-full p-6 shadow-2xl space-y-4 my-auto" role="dialog" aria-modal="true">
      <div class="flex items-center justify-between pb-3 border-b border-[#E4E7EC]">
        <h3 class="font-heading font-bold text-base text-[#202124]">Cadastrar Depoimento</h3>
        <button type="button" @click="newOpen = false" class="text-[#697386] hover:text-[#202124]" aria-label="Fechar"><?= icon('X', 'w-5 h-5') ?></button>
      </div>

      <form method="post" action="/painel/depoimentos" class="space-y-3 text-xs">
        <?= csrf_field() ?>
        <div>
          <label class="block text-xs font-semibold text-[#202124] mb-1" for="t-author">Nome do Cliente *</label>
          <input id="t-author" type="text" name="author" required maxlength="150" value="<?= e(old('author')) ?>" placeholder="Ex: Dra. Mariana Costa" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]">
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-semibold text-[#202124] mb-1" for="t-location">Bairro / Cidade</label>
            <input id="t-location" type="text" name="location" maxlength="150" value="<?= e(old('location', 'Manaíra, João Pessoa')) ?>" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]">
          </div>
          <div>
            <label class="block text-xs font-semibold text-[#202124] mb-1" for="t-rating">Nota (Estrelas)</label>
            <select id="t-rating" name="rating" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]">
              <?php foreach ($notas as $valor => $rotulo): ?>
                <option value="<?= $valor ?>"<?= (int) old('rating', 5) === $valor ? ' selected' : '' ?>><?= e($rotulo) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div>
          <label class="block text-xs font-semibold text-[#202124] mb-1" for="t-service">Serviço Realizado</label>
          <input id="t-service" type="text" name="service_title" maxlength="190" value="<?= e(old('service_title', 'Manutenção de Notebook')) ?>" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]">
        </div>
        <div>
          <label class="block text-xs font-semibold text-[#202124] mb-1" for="t-text">Texto do Depoimento *</label>
          <textarea id="t-text" name="text" rows="3" required maxlength="2000" placeholder="Feedback relatado pelo cliente..." class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]"><?= e(old('text')) ?></textarea>
        </div>
        <div class="pt-2 flex justify-end gap-2">
          <button type="button" @click="newOpen = false" class="px-4 py-2 rounded-lg border border-[#E4E7EC] text-xs font-semibold text-[#697386] cursor-pointer">Cancelar</button>
          <button type="submit" class="px-5 py-2 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold shadow-xs cursor-pointer">Salvar</button>
        </div>
      </form>
    </div>
  </div>
</div>
