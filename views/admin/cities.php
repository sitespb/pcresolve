<?php
/** @var list<array<string, mixed>> $cities */
$adminTitle = 'Cidades Atendidas & Logística Leva e Traz';
$adminSubtitle = 'Definição das cidades com cobertura na Grande João Pessoa e taxas do serviço de coleta em domicílio';
?>
<div class="space-y-6" x-data="crudPage()" @keydown.escape.window="editing = null">
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
            <th class="py-3 px-4 font-bold text-right">Ação</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[#E4E7EC]">
          <?php foreach ($cities as $city): ?>
            <tr class="hover:bg-[#F5F6F8]/60 transition-colors">
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
              <td class="py-3.5 px-4 text-right">
                <button type="button" @click="edit(<?= json_attr(['id' => $city['id'], 'name' => $city['name'], 'fee' => $city['delivery_fee']]) ?>)" class="px-2.5 py-1 rounded-md border border-[#E4E7EC] hover:bg-neutral-100 text-[11px] font-semibold text-[#202124] whitespace-nowrap">
                  Ajustar Taxa
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal: ajustar taxa -->
  <template x-if="editing">
    <div class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4" @click.self="editing = null">
      <div class="bg-white rounded-2xl border border-[#E4E7EC] max-w-sm w-full p-6 shadow-2xl space-y-4" role="dialog" aria-modal="true">
        <div class="flex items-center justify-between pb-3 border-b border-[#E4E7EC]">
          <h3 class="font-heading font-bold text-sm text-[#202124]">Ajustar Taxa de Coleta - <span x-text="editing.name"></span></h3>
          <button type="button" @click="editing = null" class="text-[#697386] hover:text-[#202124]" aria-label="Fechar"><?= icon('X', 'w-4 h-4') ?></button>
        </div>

        <form method="post" :action="'/painel/cidades/' + editing.id + '/taxa'" class="space-y-3 text-xs">
          <?= csrf_field() ?>
          <div>
            <label class="block text-xs font-semibold text-[#202124] mb-1" for="city-fee">Taxa de Deslocamento / Coleta (R$)</label>
            <input id="city-fee" type="number" name="delivery_fee" step="5" min="0" x-model="editing.fee" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]">
            <span class="text-[10px] text-[#697386] mt-1 block">Informe 0 para gratuidade promocional ou regras especiais.</span>
          </div>
          <div class="pt-2 flex justify-end gap-2">
            <button type="button" @click="editing = null" class="px-3.5 py-1.5 rounded-lg border border-[#E4E7EC] text-xs font-semibold">Cancelar</button>
            <button type="submit" class="px-4 py-1.5 rounded-lg bg-[#D71920] text-white text-xs font-semibold shadow-xs">Salvar</button>
          </div>
        </form>
      </div>
    </div>
  </template>
</div>
