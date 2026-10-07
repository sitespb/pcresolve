<?php
/**
 * @var int $pendingCount
 * @var int $inProgressCount
 * @var int $completedCount
 * @var float $totalBudget
 * @var array<string, list<array<string, mixed>>> $recent
 * @var array<string, mixed> $analytics
 * @var int $activeServices
 */
$adminTitle = 'Painel de Controle Técnico';
$adminSubtitle = 'Visão operacional das ordens de serviço, solicitações de atendimento e métricas de desempenho';
ob_start(); ?>
<div class="flex items-center gap-2">
  <a href="/painel/analytics" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-[#E4E7EC] bg-white hover:bg-neutral-50 text-xs font-semibold text-[#202124] transition-colors cursor-pointer">
    <?= icon('TrendingUp', 'w-3.5 h-3.5 text-[#D71920]') ?>
    <span>Ver Estatísticas</span>
  </a>
  <a href="/painel/solicitacoes" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold shadow-xs transition-colors cursor-pointer">
    <?= icon('Plus', 'w-3.5 h-3.5') ?>
    <span>Gerenciar O.S.</span>
  </a>
</div>
<?php $adminAction = ob_get_clean();
$summary = $analytics['summary'];
?>
<!-- 1. Cards de métricas -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
  <div class="bg-white p-5 rounded-2xl border border-[#E4E7EC] shadow-2xs space-y-2">
    <div class="flex items-center justify-between text-xs text-[#697386]">
      <span>Chamados Pendentes</span>
      <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold"><?= icon('AlertCircle', 'w-4 h-4') ?></span>
    </div>
    <div class="flex items-baseline gap-2">
      <span class="font-heading font-extrabold text-2xl text-[#202124] font-mono-numbers"><?= $pendingCount ?></span>
      <span class="text-[11px] text-amber-700 font-medium">Requer atenção</span>
    </div>
    <p class="text-[11px] text-[#697386]">Triagem imediata de novos clientes</p>
  </div>

  <div class="bg-white p-5 rounded-2xl border border-[#E4E7EC] shadow-2xs space-y-2">
    <div class="flex items-center justify-between text-xs text-[#697386]">
      <span>Em Bancada / Execução</span>
      <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold"><?= icon('Sliders', 'w-4 h-4') ?></span>
    </div>
    <div class="flex items-baseline gap-2">
      <span class="font-heading font-extrabold text-2xl text-[#202124] font-mono-numbers"><?= $inProgressCount ?></span>
      <span class="text-[11px] text-blue-700 font-medium">Laboratório ativo</span>
    </div>
    <p class="text-[11px] text-[#697386]">Máquinas em testes e reparo</p>
  </div>

  <div class="bg-white p-5 rounded-2xl border border-[#E4E7EC] shadow-2xs space-y-2">
    <div class="flex items-center justify-between text-xs text-[#697386]">
      <span>Ordens Concluídas</span>
      <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold"><?= icon('CheckCircle2', 'w-4 h-4') ?></span>
    </div>
    <div class="flex items-baseline gap-2">
      <span class="font-heading font-extrabold text-2xl text-[#202124] font-mono-numbers"><?= $completedCount ?></span>
      <span class="text-[11px] text-emerald-700 font-medium">98% aprovação</span>
    </div>
    <p class="text-[11px] text-[#697386]">Garantia de 90 dias ativada</p>
  </div>

  <div class="bg-white p-5 rounded-2xl border border-[#E4E7EC] shadow-2xs space-y-2">
    <div class="flex items-center justify-between text-xs text-[#697386]">
      <span>Volume em Orçamentos</span>
      <span class="w-8 h-8 rounded-lg bg-[#F5F6F8] text-[#D71920] flex items-center justify-center font-bold"><?= icon('DollarSign', 'w-4 h-4') ?></span>
    </div>
    <div class="flex items-baseline gap-2">
      <span class="font-heading font-extrabold text-2xl text-[#202124] font-mono-numbers">R$ <?= num_br($totalBudget, 2) ?></span>
    </div>
    <p class="text-[11px] text-[#697386]">Acumulado dos atendimentos</p>
  </div>
</div>

<!-- 2. Atividade recente + resumo do site -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
  <div class="lg:col-span-8 bg-white rounded-2xl border border-[#E4E7EC] p-6 shadow-2xs space-y-4" x-data="{ statusFilter: 'todos' }">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-[#E4E7EC]">
      <div>
        <h2 class="font-heading font-bold text-base text-[#202124]">Ordens de Serviço e Solicitações Recentes</h2>
        <p class="text-xs text-[#697386]">Acompanhamento em tempo real dos contatos gerados pelo site</p>
      </div>

      <div class="flex items-center gap-1 overflow-x-auto p-1 bg-[#F5F6F8] rounded-lg">
        <?php foreach (['todos', 'pendente', 'em_execucao', 'concluido'] as $st): ?>
          <button type="button" @click="statusFilter = '<?= $st ?>'" class="px-2.5 py-1 text-[11px] font-semibold rounded-md transition-colors capitalize" :class="statusFilter === '<?= $st ?>' ? 'bg-white text-[#D71920] shadow-2xs' : 'text-[#697386] hover:text-[#202124]'">
            <?= e(str_replace('_', ' ', $st)) ?>
          </button>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead>
          <tr class="border-b border-[#E4E7EC] text-[#697386] uppercase tracking-wider text-[10px]">
            <th class="py-2.5 px-3">Protocolo</th>
            <th class="py-2.5 px-3">Cliente</th>
            <th class="py-2.5 px-3">Cidade / Bairro</th>
            <th class="py-2.5 px-3">Serviço</th>
            <th class="py-2.5 px-3">Status</th>
            <th class="py-2.5 px-3 text-right">Ação</th>
          </tr>
        </thead>
        <?php foreach ($recent as $filter => $leads): ?>
          <tbody class="divide-y divide-[#E4E7EC]/70" x-show="statusFilter === '<?= $filter ?>'"<?= $filter !== 'todos' ? ' x-cloak' : '' ?>>
            <?php if ($leads === []): ?>
              <tr><td colspan="6" class="py-8 text-center text-[#697386]">Nenhuma solicitação com este status.</td></tr>
            <?php endif; ?>
            <?php foreach ($leads as $lead): $badge = lead_status_badge($lead['status']); ?>
              <tr class="hover:bg-[#F5F6F8]/60 transition-colors">
                <td class="py-3 px-3 font-mono font-bold text-[#D71920]"><?= e($lead['protocol']) ?></td>
                <td class="py-3 px-3 font-semibold text-[#202124]">
                  <div><?= e($lead['customer_name']) ?></div>
                  <div class="text-[10px] text-[#697386] font-normal"><?= e($lead['phone']) ?></div>
                </td>
                <td class="py-3 px-3 text-[#697386]"><?= e($lead['city']) ?></td>
                <td class="py-3 px-3 text-[#202124] max-w-[180px] truncate" title="<?= e($lead['service_type']) ?>"><?= e($lead['service_type']) ?></td>
                <td class="py-3 px-3">
                  <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold border <?= $badge['class'] ?>"><?= e($badge['label']) ?></span>
                </td>
                <td class="py-3 px-3 text-right space-x-1.5 whitespace-nowrap">
                  <a href="<?= e(wa_url($lead['phone'], 'Olá ' . $lead['customer_name'] . ', tudo bem? Sou da assistência técnica PC Resolve sobre a sua solicitação ' . $lead['protocol'] . '.')) ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center p-1.5 rounded-md bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors" title="Falar no WhatsApp">
                    <?= icon('Phone', 'w-3.5 h-3.5') ?>
                  </a>
                  <a href="/painel/solicitacoes?abrir=<?= (int) $lead['id'] ?>" class="inline-flex items-center justify-center p-1.5 rounded-md bg-neutral-100 text-[#202124] hover:bg-neutral-200 transition-colors cursor-pointer" title="Ver Detalhes">
                    <?= icon('Eye', 'w-3.5 h-3.5') ?>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        <?php endforeach; ?>
      </table>
    </div>

    <div class="pt-2 text-right">
      <a href="/painel/solicitacoes" class="text-xs font-semibold text-[#D71920] hover:underline cursor-pointer">Ver todas as solicitações &rarr;</a>
    </div>
  </div>

  <div class="lg:col-span-4 space-y-6">
    <div class="bg-white rounded-2xl border border-[#E4E7EC] p-6 shadow-2xs space-y-4">
      <div class="flex items-center justify-between pb-3 border-b border-[#E4E7EC]">
        <h3 class="font-heading font-bold text-sm text-[#202124]">Estatísticas do Site (Últimos 7 dias)</h3>
        <a href="/painel/analytics" class="text-[11px] font-semibold text-[#D71920] hover:underline">Detalhes</a>
      </div>

      <div class="space-y-3">
        <div class="flex items-center justify-between text-xs">
          <span class="text-[#697386]">Visitas Únicas:</span>
          <span class="font-bold text-[#202124] font-mono-numbers"><?= num_br($summary['uniqueVisitors']) ?></span>
        </div>
        <div class="flex items-center justify-between text-xs">
          <span class="text-[#697386]">Taxa de Conversão:</span>
          <span class="font-bold text-emerald-600 font-mono-numbers"><?= e($summary['conversionRate']) ?></span>
        </div>
        <div class="flex items-center justify-between text-xs">
          <span class="text-[#697386]">Cliques no WhatsApp:</span>
          <span class="font-bold text-[#202124] font-mono-numbers"><?= (int) $summary['whatsappClicks'] ?></span>
        </div>
        <div class="flex items-center justify-between text-xs">
          <span class="text-[#697386]">Tempo Médio de Sessão:</span>
          <span class="font-bold text-[#202124] font-mono-numbers"><?= e($summary['avgTimeOnSite']) ?></span>
        </div>
      </div>

      <div class="pt-3 border-t border-[#E4E7EC] space-y-2">
        <span class="text-[10px] font-bold uppercase tracking-wider text-[#697386]">Origem do Tráfego</span>
        <?php foreach ($analytics['trafficSources'] as $src): ?>
          <div class="space-y-1">
            <div class="flex justify-between text-[11px]">
              <span class="text-[#202124] truncate"><?= e($src['source']) ?></span>
              <span class="font-semibold text-[#697386]"><?= e(js_number($src['percentage'])) ?>%</span>
            </div>
            <div class="w-full h-1.5 bg-[#F5F6F8] rounded-full overflow-hidden">
              <div class="h-full bg-[#D71920] rounded-full" style="width: <?= (float) $src['percentage'] ?>%"></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="bg-[#F5F6F8] rounded-2xl border border-[#E4E7EC] p-5 space-y-3">
      <h4 class="font-heading font-bold text-xs uppercase tracking-wider text-[#202124]">Acesso Rápido</h4>
      <div class="grid grid-cols-2 gap-2 text-xs">
        <a href="/painel/servicos" class="p-3 bg-white rounded-xl border border-[#E4E7EC] text-left hover:border-[#D71920] transition-colors">
          <div class="font-bold text-[#202124]">Serviços</div>
          <div class="text-[10px] text-[#697386]"><?= $activeServices ?> ativos</div>
        </a>
        <a href="/painel/configuracoes" class="p-3 bg-white rounded-xl border border-[#E4E7EC] text-left hover:border-[#D71920] transition-colors">
          <div class="font-bold text-[#202124]">Configurações</div>
          <div class="text-[10px] text-[#697386]">Empresa &amp; SEO</div>
        </a>
      </div>
    </div>
  </div>
</div>
