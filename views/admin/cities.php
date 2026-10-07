<?php
/** @var list<array<string, mixed>> $cities */
$adminTitle = 'Cidades Atendidas & Logística Leva e Traz';
$adminSubtitle = 'Definição das cidades com cobertura na Grande João Pessoa e taxas do serviço de coleta em domicílio';

ob_start(); ?>
<button type="button" @click="$dispatch('new-item')" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold shadow-xs transition-colors cursor-pointer">
  <?= icon('Plus', 'w-3.5 h-3.5') ?>
  <span>Nova Cidade</span>
</button>
<?php $adminAction = ob_get_clean();

$rows = array_map(fn ($c) => mb_strtolower($c['name'] . "\n" . $c['coverage']), $cities);
?>
<div
  class="space-y-6"
  x-data="crudPage(<?= json_attr(['rows' => $rows, 'newOpen' => input('novo') !== null]) ?>)"
  @new-item.window="newOpen = true"
  @keydown.escape.window="editing = null; newOpen = false"
>
  <!-- Busca -->
  <div class="bg-white rounded-2xl border border-[#E4E7EC] p-4 shadow-2xs">
    <div class="relative max-w-md">
      <?= icon('Search', 'w-4 h-4 text-[#697386] absolute left-3.5 top-1/2 -translate-y-1/2') ?>
      <input type="text" x-model="search" placeholder="Pesquisar município ou bairro..." aria-label="Pesquisar cidades" class="w-full pl-9 pr-3.5 py-2 text-xs rounded-xl border border-[#E4E7EC] bg-white text-[#202124] focus:outline-none focus:border-[#D71920]">
    </div>
  </div>

  <div class="bg-white rounded-2xl border border-[#E4E7EC] shadow-2xs overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead>
          <tr class="bg-[#F5F6F8] border-b border-[#E4E7EC] text-[#697386] uppercase tracking-wider text-[10px]">
            <th class="py-3 px-4 font-bold">Município</th>
            <th class="py-3 px-4 font-bold">Bairros e Região</th>
            <th class="py-3 px-4 font-bold">Leva e Traz</th>
            <th class="py-3 px-4 font-bold">Taxa de Coleta</th>
            <th class="py-3 px-4 font-bold">Status</th>
            <th class="py-3 px-4 font-bold text-right">Ações</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[#E4E7EC]">
          <tr x-show="visibleCount === 0"<?= $cities !== [] ? ' x-cloak' : '' ?>>
            <td colspan="6" class="py-12 text-center text-[#697386]">Nenhuma cidade encontrada.</td>
          </tr>
          <?php foreach ($cities as $i => $city): ?>
            <tr x-show="isVisible(<?= $i ?>)" class="hover:bg-[#F5F6F8]/60 transition-colors">
              <td class="py-3.5 px-4 font-bold text-[#202124]">
                <div class="flex items-center gap-2">
                  <?= icon('MapPin', 'w-4 h-4 text-[#D71920] shrink-0') ?>
                  <span><?= e($city['name']) ?></span>
                </div>
              </td>
              <td class="py-3.5 px-4 text-[#697386] max-w-xs"><?= e($city['coverage']) ?></td>
              <td class="py-3.5 px-4">
                <?php if ($city['delivery_available']): ?>
                  <span class="inline-flex items-center gap-1 text-[11px] text-emerald-700 font-semibold bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                    <?= icon('Truck', 'w-3 h-3') ?>
                    Disponível
                  </span>
                <?php else: ?>
                  <span class="text-[#697386] text-[11px]">Sob consulta</span>
                <?php endif; ?>
              </td>
              <td class="py-3.5 px-4 font-mono font-bold text-[#202124]">
                <?= $city['delivery_fee'] == 0 ? 'Grátis (&gt; R$150)' : 'R$ ' . fixed2($city['delivery_fee']) ?>
              </td>
              <td class="py-3.5 px-4">
                <form method="post" action="/painel/cidades/<?= $city['id'] ?>/status">
                  <?= csrf_field() ?>
                  <button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold border cursor-pointer transition-colors <?= $city['active'] ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-neutral-100 text-neutral-600 border-neutral-300' ?>" title="<?= $city['active'] ? 'Clique para ocultar do site' : 'Clique para exibir no site' ?>">
                    <?= $city['active'] ? 'Ativa' : 'Inativa' ?>
                  </button>
                </form>
              </td>
              <td class="py-3.5 px-4 text-right whitespace-nowrap">
                <div class="inline-flex items-center gap-2">
                  <button type="button" @click="edit(<?= json_attr([
                      'id' => $city['id'],
                      'name' => $city['name'],
                      'coverage' => $city['coverage'],
                      'delivery_available' => (bool) $city['delivery_available'],
                      'delivery_fee' => $city['delivery_fee'],
                  ]) ?>)" class="p-1.5 rounded-lg border border-[#E4E7EC] hover:bg-neutral-100 text-[#202124] transition-colors cursor-pointer" title="Editar cidade">
                    <?= icon('Edit2', 'w-3.5 h-3.5') ?>
                  </button>
                  <form method="post" action="/painel/cidades/<?= $city['id'] ?>/excluir" onsubmit="return confirm(<?= e(json_script('Remover a cidade "' . $city['name'] . '" da cobertura?')) ?>)">
                    <?= csrf_field() ?>
                    <button type="submit" class="p-1.5 rounded-lg border border-[#E4E7EC] hover:bg-red-50 text-red-600 transition-colors cursor-pointer" title="Excluir cidade">
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

  <!-- Modal: editar cidade -->
  <template x-if="editing">
    <div class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto" @click.self="editing = null">
      <div class="bg-white rounded-2xl border border-[#E4E7EC] max-w-md w-full p-6 shadow-2xl space-y-4 my-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-between pb-3 border-b border-[#E4E7EC]">
          <h3 class="font-heading font-bold text-base text-[#202124]">Editar Cidade</h3>
          <button type="button" @click="editing = null" class="text-[#697386] hover:text-[#202124]" aria-label="Fechar"><?= icon('X', 'w-5 h-5') ?></button>
        </div>

        <form method="post" :action="'/painel/cidades/' + editing.id" class="space-y-3 text-xs">
          <?= csrf_field() ?>
          <div>
            <label class="block text-xs font-semibold text-[#202124] mb-1" for="city-name">Município *</label>
            <input id="city-name" type="text" name="name" required maxlength="120" x-model="editing.name" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]">
          </div>
          <div>
            <label class="block text-xs font-semibold text-[#202124] mb-1" for="city-coverage">Bairros e Região Atendida</label>
            <textarea id="city-coverage" name="coverage" rows="2" maxlength="2000" x-model="editing.coverage" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]"></textarea>
          </div>
          <div>
            <label class="block text-xs font-semibold text-[#202124] mb-1" for="city-edit-fee">Taxa de Deslocamento / Coleta (R$)</label>
            <input id="city-edit-fee" type="number" name="delivery_fee" step="5" min="0" x-model="editing.delivery_fee" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]">
            <span class="text-[10px] text-[#697386] mt-1 block">Informe 0 para gratuidade promocional ou regras especiais.</span>
          </div>
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="delivery_available" value="1" x-model="editing.delivery_available" class="accent-[#D71920]">
            <span class="text-[#202124]">Serviço de Leva e Traz disponível nesta cidade</span>
          </label>
          <div class="pt-3 border-t border-[#E4E7EC] flex justify-end gap-2">
            <button type="button" @click="editing = null" class="px-4 py-2 rounded-lg border border-[#E4E7EC] text-xs font-semibold text-[#697386] cursor-pointer">Cancelar</button>
            <button type="submit" class="px-5 py-2 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold shadow-xs cursor-pointer">Salvar</button>
          </div>
        </form>
      </div>
    </div>
  </template>

  <!-- Modal: nova cidade -->
  <div x-show="newOpen" x-cloak class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto" @click.self="newOpen = false">
    <div class="bg-white rounded-2xl border border-[#E4E7EC] max-w-md w-full p-6 shadow-2xl space-y-4 my-auto" role="dialog" aria-modal="true">
      <div class="flex items-center justify-between pb-3 border-b border-[#E4E7EC]">
        <h3 class="font-heading font-bold text-base text-[#202124]">Adicionar Cidade à Cobertura</h3>
        <button type="button" @click="newOpen = false" class="text-[#697386] hover:text-[#202124]" aria-label="Fechar"><?= icon('X', 'w-5 h-5') ?></button>
      </div>

      <form method="post" action="/painel/cidades" class="space-y-3 text-xs">
        <?= csrf_field() ?>
        <div>
          <label class="block text-xs font-semibold text-[#202124] mb-1" for="city-new-name">Município *</label>
          <input id="city-new-name" type="text" name="name" required maxlength="120" value="<?= e(old('name')) ?>" placeholder="Ex: Santa Rita" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]">
        </div>
        <div>
          <label class="block text-xs font-semibold text-[#202124] mb-1" for="city-new-coverage">Bairros e Região Atendida</label>
          <textarea id="city-new-coverage" name="coverage" rows="2" maxlength="2000" placeholder="Ex: Centro, Tibiri, Várzea Nova" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]"><?= e(old('coverage')) ?></textarea>
        </div>
        <div>
          <label class="block text-xs font-semibold text-[#202124] mb-1" for="city-new-fee">Taxa de Deslocamento / Coleta (R$)</label>
          <input id="city-new-fee" type="number" name="delivery_fee" step="5" min="0" value="<?= e(old('delivery_fee', '0')) ?>" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]">
        </div>
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="checkbox" name="delivery_available" value="1" checked class="accent-[#D71920]">
          <span class="text-[#202124]">Serviço de Leva e Traz disponível nesta cidade</span>
        </label>
        <div class="pt-3 border-t border-[#E4E7EC] flex justify-end gap-2">
          <button type="button" @click="newOpen = false" class="px-4 py-2 rounded-lg border border-[#E4E7EC] text-xs font-semibold text-[#697386] cursor-pointer">Cancelar</button>
          <button type="submit" class="px-5 py-2 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold shadow-xs cursor-pointer">Adicionar Cidade</button>
        </div>
      </form>
    </div>
  </div>
</div>
