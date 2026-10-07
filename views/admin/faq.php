<?php
/** @var list<array<string, mixed>> $faqs */
$adminTitle = 'Perguntas Frequentes (FAQ)';
$adminSubtitle = 'Gerencie as dúvidas comuns respondidas na área pública do site para reduzir atrito na contratação';
ob_start(); ?>
<button type="button" @click="$dispatch('new-item')" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold shadow-xs transition-colors cursor-pointer">
  <?= icon('Plus', 'w-3.5 h-3.5') ?>
  <span>Nova Pergunta</span>
</button>
<?php $adminAction = ob_get_clean(); ?>
<div
  class="space-y-6"
  x-data="crudPage(<?= json_attr(['newOpen' => input('novo') !== null]) ?>)"
  @new-item.window="newOpen = true"
  @keydown.escape.window="editing = null; newOpen = false"
>
  <div class="space-y-4">
    <?php if ($faqs === []): ?>
      <div class="bg-white rounded-2xl border border-[#E4E7EC] p-10 text-center text-xs text-[#697386]">Nenhuma pergunta cadastrada.</div>
    <?php endif; ?>
    <?php foreach ($faqs as $faq): ?>
      <div class="bg-white rounded-2xl border border-[#E4E7EC] p-5 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="space-y-1.5 flex-1">
          <div class="flex items-center gap-2 flex-wrap">
            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-[#F5F6F8] text-[#D71920] border border-[#E4E7EC]"><?= e($faq['category']) ?></span>
            <h3 class="font-heading font-bold text-sm text-[#202124]"><?= e($faq['question']) ?></h3>
          </div>
          <p class="text-xs text-[#697386] leading-relaxed"><?= e($faq['answer']) ?></p>
        </div>

        <div class="flex items-center gap-2 shrink-0">
          <button type="button" @click="edit(<?= json_attr(['id' => $faq['id'], 'question' => $faq['question'], 'answer' => $faq['answer'], 'category' => $faq['category']]) ?>)" class="p-2 rounded-lg border border-[#E4E7EC] hover:bg-neutral-50 text-[#202124] transition-colors" title="Editar">
            <?= icon('Edit2', 'w-3.5 h-3.5') ?>
          </button>
          <form method="post" action="/painel/faq/<?= $faq['id'] ?>/excluir" onsubmit="return confirm('Deseja excluir esta pergunta?')">
            <?= csrf_field() ?>
            <button type="submit" class="p-2 rounded-lg border border-[#E4E7EC] hover:bg-red-50 text-red-600 transition-colors" title="Excluir">
              <?= icon('Trash2', 'w-3.5 h-3.5') ?>
            </button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Modal: nova pergunta -->
  <div x-show="newOpen" x-cloak class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto" @click.self="newOpen = false">
    <div class="bg-white rounded-2xl border border-[#E4E7EC] max-w-lg w-full p-6 shadow-2xl space-y-4 my-auto" role="dialog" aria-modal="true">
      <div class="flex items-center justify-between pb-3 border-b border-[#E4E7EC]">
        <h3 class="font-heading font-bold text-sm text-[#202124]">Adicionar Pergunta Frequente</h3>
        <button type="button" @click="newOpen = false" class="text-[#697386] hover:text-[#202124]" aria-label="Fechar"><?= icon('X', 'w-5 h-5') ?></button>
      </div>

      <form method="post" action="/painel/faq" class="space-y-3 text-xs">
        <?= csrf_field() ?>
        <div>
          <label class="block text-xs font-semibold text-[#202124] mb-1" for="faq-q">Pergunta *</label>
          <input id="faq-q" type="text" name="question" required maxlength="255" value="<?= e(old('question')) ?>" placeholder="Ex: Vocês atendem a domicílio em Cabedelo?" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]">
        </div>
        <div>
          <label class="block text-xs font-semibold text-[#202124] mb-1" for="faq-cat">Categoria</label>
          <input id="faq-cat" type="text" name="category" maxlength="80" value="<?= e(old('category', 'Geral')) ?>" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]">
        </div>
        <div>
          <label class="block text-xs font-semibold text-[#202124] mb-1" for="faq-a">Resposta Explicativa *</label>
          <textarea id="faq-a" name="answer" rows="3" required maxlength="3000" placeholder="Explicação clara e acessível..." class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]"><?= e(old('answer')) ?></textarea>
        </div>
        <div class="pt-2 flex justify-end gap-2">
          <button type="button" @click="newOpen = false" class="px-4 py-2 rounded-lg border border-[#E4E7EC] text-xs font-semibold">Cancelar</button>
          <button type="submit" class="px-5 py-2 rounded-lg bg-[#D71920] text-white text-xs font-semibold shadow-xs">Salvar</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal: editar pergunta -->
  <template x-if="editing">
    <div class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto" @click.self="editing = null">
      <div class="bg-white rounded-2xl border border-[#E4E7EC] max-w-lg w-full p-6 shadow-2xl space-y-4 my-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-between pb-3 border-b border-[#E4E7EC]">
          <h3 class="font-heading font-bold text-sm text-[#202124]">Editar Pergunta Frequente</h3>
          <button type="button" @click="editing = null" class="text-[#697386] hover:text-[#202124]" aria-label="Fechar"><?= icon('X', 'w-5 h-5') ?></button>
        </div>

        <form method="post" :action="'/painel/faq/' + editing.id" class="space-y-3 text-xs">
          <?= csrf_field() ?>
          <div>
            <label class="block text-xs font-semibold text-[#202124] mb-1" for="faq-edit-q">Pergunta</label>
            <input id="faq-edit-q" type="text" name="question" required maxlength="255" x-model="editing.question" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]">
          </div>
          <div>
            <label class="block text-xs font-semibold text-[#202124] mb-1" for="faq-edit-cat">Categoria</label>
            <input id="faq-edit-cat" type="text" name="category" maxlength="80" x-model="editing.category" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]">
          </div>
          <div>
            <label class="block text-xs font-semibold text-[#202124] mb-1" for="faq-edit-a">Resposta</label>
            <textarea id="faq-edit-a" name="answer" rows="3" required maxlength="3000" x-model="editing.answer" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]"></textarea>
          </div>
          <div class="pt-2 flex justify-end gap-2">
            <button type="button" @click="editing = null" class="px-4 py-2 rounded-lg border border-[#E4E7EC] text-xs font-semibold">Cancelar</button>
            <button type="submit" class="px-5 py-2 rounded-lg bg-[#D71920] text-white text-xs font-semibold shadow-xs">Salvar</button>
          </div>
        </form>
      </div>
    </div>
  </template>
</div>
