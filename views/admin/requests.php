<?php
/**
 * @var list<array<string, mixed>> $leads
 * @var int $total
 * @var int $page
 * @var int $pages
 * @var string $status
 * @var string $search
 * @var array<string, mixed>|null $openLead
 */
$adminTitle = 'Gestão de Ordens de Serviço & Leads';
$adminSubtitle = 'Controle operacional de ordens de serviço, orçamentos e comunicações com os clientes';
ob_start(); ?>
<button type="button" @click="$dispatch('new-lead')" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold shadow-xs transition-colors cursor-pointer">
  <?= icon('Plus', 'w-3.5 h-3.5') ?>
  <span>Cadastrar Nova O.S.</span>
</button>
<?php $adminAction = ob_get_clean();

$toModal = fn (array $lead): array => [
    'id' => $lead['id'],
    'protocol' => $lead['protocol'],
    'created_label' => $lead['created_label'],
    'customer_name' => $lead['customer_name'],
    'phone' => $lead['phone'],
    'email' => $lead['email'],
    'city' => $lead['city'],
    'device_type' => $lead['device_type'],
    'service_type' => $lead['service_type'],
    'description' => $lead['description'],
    'status' => $lead['status'],
    'budget' => $lead['budget'] ?? 0,
    'internal_notes' => $lead['internal_notes'],
];

$filters = [
    'todos' => 'Todos',
    'pendente' => 'Pendentes',
    'em_diagnostico' => 'Em Diagnóstico',
    'em_execucao' => 'Em Execução',
    'concluido' => 'Concluídos',
    'entregue' => 'Entregues',
];

$searchIndex = array_map(fn ($l) => mb_strtolower($l['customer_name'] . ' ' . $l['protocol'] . ' ' . $l['city']) . ' ' . $l['phone'], $leads);
$queryFor = fn (array $extra) => '/painel/solicitacoes?' . http_build_query(array_filter(array_merge(['q' => $search, 'status' => $status], $extra), fn ($v) => $v !== '' && $v !== 'todos' && $v !== 1 && $v !== null));
?>
<div
  class="space-y-6"
  x-data="requestsPage(<?= json_attr([
      'search' => $search,
      'rows' => $searchIndex,
      'openLead' => $openLead ? $toModal($openLead) : null,
      'newOpen' => input('novo') !== null,
  ]) ?>)"
  @new-lead.window="newOpen = true"
  @keydown.escape.window="active = null; newOpen = false"
>
  <!-- Busca e filtros -->
  <div class="bg-white rounded-2xl border border-[#E4E7EC] p-4 shadow-2xs space-y-4">
    <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
      <form method="get" action="/painel/solicitacoes" class="relative flex-1 max-w-md">
        <?php if ($status !== 'todos'): ?><input type="hidden" name="status" value="<?= e($status) ?>"><?php endif; ?>
        <?= icon('Search', 'w-4 h-4 text-[#697386] absolute left-3.5 top-1/2 -translate-y-1/2') ?>
        <input type="search" name="q" x-model="search" placeholder="Buscar por cliente, telefone ou protocolo (ex: OS-2026-0842)..." aria-label="Buscar solicitações" class="w-full pl-9 pr-3.5 py-2 text-xs rounded-xl border border-[#E4E7EC] bg-white text-[#202124] focus:outline-none focus:border-[#D71920]">
      </form>

      <div class="flex items-center gap-1 overflow-x-auto p-1 bg-[#F5F6F8] rounded-xl border border-[#E4E7EC]">
        <?php foreach ($filters as $key => $label): ?>
          <a href="<?= e($queryFor(['status' => $key, 'pagina' => null])) ?>" class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-colors whitespace-nowrap cursor-pointer <?= $status === $key ? 'bg-white text-[#D71920] shadow-2xs' : 'text-[#697386] hover:text-[#202124]' ?>"><?= e($label) ?></a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Tabela -->
  <div class="bg-white rounded-2xl border border-[#E4E7EC] shadow-2xs overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead>
          <tr class="bg-[#F5F6F8] border-b border-[#E4E7EC] text-[#697386] uppercase tracking-wider text-[10px]">
            <th class="py-3 px-4 font-bold">Protocolo / Data</th>
            <th class="py-3 px-4 font-bold">Cliente / Contato</th>
            <th class="py-3 px-4 font-bold">Dispositivo</th>
            <th class="py-3 px-4 font-bold">Serviço / Defeito</th>
            <th class="py-3 px-4 font-bold">Orçamento</th>
            <th class="py-3 px-4 font-bold">Status</th>
            <th class="py-3 px-4 font-bold text-right">Ações</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[#E4E7EC]">
          <tr x-show="visibleCount === 0"<?= $leads !== [] ? ' x-cloak' : '' ?>>
            <td colspan="7" class="py-12 text-center text-[#697386]">Nenhuma ordem de serviço encontrada para os filtros selecionados.</td>
          </tr>
          <?php foreach ($leads as $i => $lead): $badge = lead_status_badge($lead['status']); ?>
            <tr x-show="isVisible(<?= $i ?>)" class="hover:bg-[#F5F6F8]/60 transition-colors">
              <td class="py-3 px-4">
                <div class="font-mono font-bold text-[#D71920]"><?= e($lead['protocol']) ?></div>
                <div class="text-[10px] text-[#697386]"><?= e($lead['created_label']) ?></div>
              </td>
              <td class="py-3 px-4">
                <div class="font-bold text-[#202124]"><?= e($lead['customer_name']) ?></div>
                <div class="text-[11px] text-[#697386]"><?= e($lead['phone']) ?></div>
                <div class="text-[10px] text-[#697386]"><?= e($lead['city']) ?></div>
              </td>
              <td class="py-3 px-4">
                <span class="capitalize font-semibold text-[#202124]"><?= e($lead['device_type']) ?></span>
              </td>
              <td class="py-3 px-4 max-w-[220px]">
                <div class="font-semibold text-[#202124] truncate" title="<?= e($lead['service_type']) ?>"><?= e($lead['service_type']) ?></div>
                <div class="text-[11px] text-[#697386] truncate" title="<?= e($lead['description']) ?>"><?= e($lead['description']) ?></div>
              </td>
              <td class="py-3 px-4 font-mono font-bold text-[#202124]">
                <?= $lead['budget'] ? 'R$ ' . fixed2($lead['budget']) : 'A definir' ?>
              </td>
              <td class="py-3 px-4">
                <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-semibold border <?= $badge['class'] ?>"><?= e($badge['label']) ?></span>
              </td>
              <td class="py-3 px-4 text-right space-x-1.5 whitespace-nowrap">
                <a href="<?= e(wa_url($lead['phone'], 'Olá ' . $lead['customer_name'] . '! Sou da assistência técnica PC Resolve sobre a sua Ordem de Serviço ' . $lead['protocol'] . ' (' . $lead['service_type'] . ').')) ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center p-1.5 rounded-md bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors" title="Falar no WhatsApp">
                  <?= icon('Phone', 'w-3.5 h-3.5') ?>
                </a>
                <button type="button" @click="open(<?= json_attr($toModal($lead)) ?>)" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-md bg-white border border-[#E4E7EC] hover:bg-neutral-50 text-[#202124] font-semibold text-xs transition-colors cursor-pointer">
                  <?= icon('Eye', 'w-3.5 h-3.5 text-[#D71920]') ?>
                  <span>Gerenciar</span>
                </button>
                <form method="post" action="/painel/solicitacoes/<?= (int) $lead['id'] ?>/excluir" class="inline-block" onsubmit="return confirm(<?= e(json_script('Excluir a ordem de serviço ' . $lead['protocol'] . ' de ' . $lead['customer_name'] . '? Esta ação não pode ser desfeita.')) ?>)">
                  <?= csrf_field() ?>
                  <button type="submit" class="inline-flex items-center justify-center p-1.5 rounded-md border border-[#E4E7EC] hover:bg-red-50 text-red-600 transition-colors cursor-pointer" title="Excluir ordem de serviço">
                    <?= icon('Trash2', 'w-3.5 h-3.5') ?>
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <?php if ($pages > 1): ?>
      <div class="flex items-center justify-between gap-3 px-4 py-3 border-t border-[#E4E7EC] text-xs text-[#697386]">
        <span><?= $total ?> registros · Página <?= $page ?> de <?= $pages ?></span>
        <div class="flex items-center gap-2">
          <?php if ($page > 1): ?>
            <a href="<?= e($queryFor(['pagina' => $page - 1])) ?>" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-[#E4E7EC] hover:bg-neutral-50 font-semibold text-[#202124]"><?= icon('ChevronLeft', 'w-3.5 h-3.5') ?> Anterior</a>
          <?php endif; ?>
          <?php if ($page < $pages): ?>
            <a href="<?= e($queryFor(['pagina' => $page + 1])) ?>" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-[#E4E7EC] hover:bg-neutral-50 font-semibold text-[#202124]">Próxima <?= icon('ChevronRight', 'w-3.5 h-3.5') ?></a>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <!-- Modal: gerenciar O.S. -->
  <template x-if="active">
    <div class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto" @click.self="active = null">
      <div class="bg-white rounded-2xl border border-[#E4E7EC] max-w-2xl w-full p-6 shadow-2xl space-y-6 my-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-between pb-3 border-b border-[#E4E7EC]">
          <div class="flex items-center gap-3 flex-wrap">
            <span class="font-mono font-bold text-lg text-[#D71920]" x-text="active.protocol"></span>
            <span class="text-xs text-[#697386]">Criado em: <span x-text="active.created_label"></span></span>
          </div>
          <button type="button" @click="active = null" class="text-[#697386] hover:text-[#202124] p-1 rounded" aria-label="Fechar">
            <?= icon('X', 'w-5 h-5') ?>
          </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
          <div class="p-3.5 rounded-xl bg-[#F5F6F8] space-y-1">
            <span class="text-[#697386] font-semibold uppercase text-[10px]">Cliente</span>
            <div class="font-bold text-sm text-[#202124]" x-text="active.customer_name"></div>
            <div class="text-[#697386]" x-text="active.phone"></div>
            <div class="text-[#697386]" x-show="active.email" x-text="active.email"></div>
            <div class="text-[#697386]" x-text="active.city"></div>
          </div>
          <div class="p-3.5 rounded-xl bg-[#F5F6F8] space-y-1">
            <span class="text-[#697386] font-semibold uppercase text-[10px]">Equipamento</span>
            <div class="font-bold text-sm text-[#202124] capitalize" x-text="active.device_type"></div>
            <div class="font-semibold text-[#D71920]" x-text="active.service_type"></div>
          </div>
        </div>

        <div class="text-xs space-y-1">
          <span class="text-[#697386] font-semibold uppercase text-[10px]">Relato do Cliente</span>
          <div class="p-3 rounded-xl bg-[#F5F6F8] text-[#202124] leading-relaxed whitespace-pre-line" x-text="active.description"></div>
        </div>

        <form method="post" :action="'/painel/solicitacoes/' + active.id" class="space-y-6">
          <?= csrf_field() ?>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            <div>
              <label for="lead-status" class="block text-xs font-semibold text-[#202124] mb-1">Status Atual da O.S.</label>
              <select id="lead-status" name="status" x-model="form.status" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]">
                <option value="pendente">Pendente (Novo Contato)</option>
                <option value="em_diagnostico">Em Diagnóstico (Bancada)</option>
                <option value="aguardando_aprovacao">Aguardando Aprovação do Orçamento</option>
                <option value="em_execucao">Em Execução de Reparo</option>
                <option value="concluido">Concluído (Pronto para Retirada)</option>
                <option value="entregue">Entregue ao Cliente (Garantia Ativa)</option>
              </select>
            </div>
            <div>
              <label for="lead-budget" class="block text-xs font-semibold text-[#202124] mb-1">Valor do Orçamento (R$)</label>
              <input id="lead-budget" type="number" name="budget" step="0.01" min="0" x-model="form.budget" placeholder="0.00" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white text-xs font-mono text-[#202124] focus:outline-none focus:border-[#D71920]">
            </div>
          </div>

          <div class="text-xs space-y-1">
            <label for="lead-notes" class="block text-xs font-semibold text-[#202124] mb-1">Anotações Técnicas Internas (Bancada ESD)</label>
            <textarea id="lead-notes" name="internal_notes" rows="3" x-model="form.notes" placeholder="Ex: Medições realizadas, componentes substituídos, número de série..." class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]"></textarea>
          </div>

          <div class="pt-3 border-t border-[#E4E7EC] flex flex-wrap items-center justify-between gap-3">
            <a :href="notifyUrl" target="_blank" rel="noopener noreferrer" class="px-3.5 py-2 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs font-semibold transition-colors flex items-center gap-1.5">
              <?= icon('Phone', 'w-3.5 h-3.5') ?>
              <span>Notificar no WhatsApp</span>
            </a>
            <div class="flex items-center gap-2">
              <button type="button" @click="active = null" class="px-4 py-2 rounded-lg border border-[#E4E7EC] text-xs font-semibold text-[#697386] hover:bg-neutral-50 transition-colors">Cancelar</button>
              <button type="submit" class="px-5 py-2 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold transition-colors flex items-center gap-1.5 shadow-xs">
                <?= icon('Save', 'w-3.5 h-3.5') ?>
                <span>Salvar Alterações</span>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </template>

  <!-- Modal: nova O.S. -->
  <div x-show="newOpen" x-cloak class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto" @click.self="newOpen = false">
    <div class="bg-white rounded-2xl border border-[#E4E7EC] max-w-xl w-full p-6 shadow-2xl space-y-6 my-auto" role="dialog" aria-modal="true">
      <div class="flex items-center justify-between pb-3 border-b border-[#E4E7EC]">
        <h3 class="font-heading font-bold text-base text-[#202124]">Cadastrar Nova Ordem de Serviço</h3>
        <button type="button" @click="newOpen = false" class="text-[#697386] hover:text-[#202124] p-1 rounded" aria-label="Fechar">
          <?= icon('X', 'w-5 h-5') ?>
        </button>
      </div>

      <form method="post" action="/painel/solicitacoes" class="space-y-4">
        <?= csrf_field() ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
          <div>
            <label for="new-name" class="block text-xs font-semibold text-[#202124] mb-1">Nome do Cliente *</label>
            <input id="new-name" type="text" name="customer_name" required maxlength="150" value="<?= e(old('customer_name')) ?>" placeholder="Nome completo" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] text-xs">
          </div>
          <div>
            <label for="new-phone" class="block text-xs font-semibold text-[#202124] mb-1">WhatsApp / Telefone *</label>
            <input id="new-phone" type="tel" name="phone" required maxlength="40" value="<?= e(old('phone')) ?>" placeholder="(83) 99999-9999" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] text-xs">
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
          <div>
            <label for="new-city" class="block text-xs font-semibold text-[#202124] mb-1">Cidade / Bairro</label>
            <input id="new-city" type="text" name="city" maxlength="150" value="<?= e(old('city', 'João Pessoa')) ?>" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] text-xs">
          </div>
          <div>
            <label for="new-device" class="block text-xs font-semibold text-[#202124] mb-1">Dispositivo</label>
            <select id="new-device" name="device_type" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] text-xs">
              <?php foreach (['notebook' => 'Notebook', 'desktop' => 'PC Desktop', 'all-in-one' => 'All-in-One', 'macbook' => 'Apple MacBook', 'corporativo' => 'Corporativo'] as $value => $label): ?>
                <option value="<?= e($value) ?>"<?= old('device_type', 'notebook') === $value ? ' selected' : '' ?>><?= e($label) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="text-xs">
          <label for="new-service" class="block text-xs font-semibold text-[#202124] mb-1">Serviço Solicitado</label>
          <input id="new-service" type="text" name="service_type" maxlength="190" value="<?= e(old('service_type', 'Manutenção de Notebooks')) ?>" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] text-xs">
        </div>

        <div class="text-xs">
          <label for="new-desc" class="block text-xs font-semibold text-[#202124] mb-1">Defeito Relatado / Observações</label>
          <textarea id="new-desc" name="description" rows="3" maxlength="3000" placeholder="Relato do cliente, itens deixados..." class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] text-xs"><?= e(old('description')) ?></textarea>
        </div>

        <div class="pt-3 border-t border-[#E4E7EC] flex justify-end gap-2">
          <button type="button" @click="newOpen = false" class="px-4 py-2 rounded-lg border border-[#E4E7EC] text-xs font-semibold text-[#697386]">Cancelar</button>
          <button type="submit" class="px-5 py-2 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold shadow-xs">Criar Ordem de Serviço</button>
        </div>
      </form>
    </div>
  </div>
</div>
