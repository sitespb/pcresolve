<?php
/**
 * @var string $timeframe
 * @var array<string, mixed> $analytics
 */
use App\Models\Analytics;

$adminTitle = 'Relatórios Analíticos & Estatísticas de Visitas';
$adminSubtitle = 'Métricas de tráfego, funil de conversão e comportamento dos usuários no site da PC Resolve';
ob_start(); ?>
<div class="flex items-center gap-2">
  <a href="/painel/analytics/exportar?periodo=<?= e($timeframe) ?>" @click="toast('Relatório exportado em formato CSV com sucesso!', 'success')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-[#E4E7EC] bg-white hover:bg-neutral-50 text-xs font-semibold text-[#202124] transition-colors cursor-pointer">
    <?= icon('Download', 'w-3.5 h-3.5 text-[#D71920]') ?>
    <span>Exportar CSV</span>
  </a>
  <button type="button" onclick="window.print()" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-[#E4E7EC] bg-white hover:bg-neutral-50 text-xs font-semibold text-[#202124] transition-colors cursor-pointer">
    <?= icon('Printer', 'w-3.5 h-3.5 text-[#697386]') ?>
    <span>Imprimir</span>
  </button>
</div>
<?php $adminAction = ob_get_clean();

$summary = $analytics['summary'];
$visitors = array_map(fn ($d) => (int) $d['visitors'], $analytics['dailyVisits']);
$maxVisitors = max([1, ...$visitors]);
$pagesPerSession = $summary['totalVisits'] > 0 ? number_format($summary['pageViews'] / $summary['totalVisits'], 1, '.', '') : '0.0';
?>
<!-- 1. Período -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-3 rounded-2xl border border-[#E4E7EC] shadow-2xs">
  <div class="flex items-center gap-2 text-xs font-semibold text-[#202124]">
    <?= icon('Calendar', 'w-4 h-4 text-[#D71920]') ?>
    <span>Filtrar período de análise:</span>
  </div>

  <div class="flex items-center gap-1 bg-[#F5F6F8] p-1 rounded-xl border border-[#E4E7EC] overflow-x-auto">
    <?php foreach (Analytics::TIMEFRAMES as $key => $label): ?>
      <a href="/painel/analytics?periodo=<?= e($key) ?>" class="px-3.5 py-1.5 text-xs font-semibold rounded-lg transition-colors cursor-pointer whitespace-nowrap <?= $timeframe === $key ? 'bg-white text-[#D71920] shadow-xs' : 'text-[#697386] hover:text-[#202124]' ?>"><?= e($label) ?></a>
    <?php endforeach; ?>
  </div>
</div>

<!-- 2. Métricas -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
  <div class="bg-white p-5 rounded-2xl border border-[#E4E7EC] shadow-2xs space-y-2">
    <div class="flex items-center justify-between text-xs text-[#697386]">
      <span>Total de Visitas</span>
      <?= icon('Users', 'w-4 h-4 text-[#D71920]') ?>
    </div>
    <div class="flex items-baseline gap-2">
      <span class="font-heading font-extrabold text-2xl text-[#202124] font-mono-numbers"><?= num_br($summary['totalVisits']) ?></span>
      <span class="text-[11px] text-emerald-600 font-medium flex items-center"><?= icon('ArrowUpRight', 'w-3 h-3') ?> +12.4%</span>
    </div>
    <p class="text-[11px] text-[#697386]"><?= num_br($summary['uniqueVisitors']) ?> visitantes únicos</p>
  </div>

  <div class="bg-white p-5 rounded-2xl border border-[#E4E7EC] shadow-2xs space-y-2">
    <div class="flex items-center justify-between text-xs text-[#697386]">
      <span>Páginas Visualizadas</span>
      <?= icon('Eye', 'w-4 h-4 text-[#D71920]') ?>
    </div>
    <div class="flex items-baseline gap-2">
      <span class="font-heading font-extrabold text-2xl text-[#202124] font-mono-numbers"><?= num_br($summary['pageViews']) ?></span>
      <span class="text-[11px] text-emerald-600 font-medium flex items-center"><?= icon('ArrowUpRight', 'w-3 h-3') ?> +8.1%</span>
    </div>
    <p class="text-[11px] text-[#697386]">Média de <?= $pagesPerSession ?> páginas por sessão</p>
  </div>

  <div class="bg-white p-5 rounded-2xl border border-[#E4E7EC] shadow-2xs space-y-2">
    <div class="flex items-center justify-between text-xs text-[#697386]">
      <span>Taxa de Conversão</span>
      <?= icon('TrendingUp', 'w-4 h-4 text-emerald-600') ?>
    </div>
    <div class="flex items-baseline gap-2">
      <span class="font-heading font-extrabold text-2xl text-emerald-700 font-mono-numbers"><?= e($summary['conversionRate']) ?></span>
      <span class="text-[11px] text-emerald-600 font-medium">Acima da média</span>
    </div>
    <p class="text-[11px] text-[#697386]"><?= (int) $summary['totalLeads'] ?> contatos técnicos gerados</p>
  </div>

  <div class="bg-white p-5 rounded-2xl border border-[#E4E7EC] shadow-2xs space-y-2">
    <div class="flex items-center justify-between text-xs text-[#697386]">
      <span>Cliques no WhatsApp</span>
      <span class="w-4 h-4 flex items-center justify-center font-bold text-[#25D366]">W</span>
    </div>
    <div class="flex items-baseline gap-2">
      <span class="font-heading font-extrabold text-2xl text-[#202124] font-mono-numbers"><?= (int) $summary['whatsappClicks'] ?></span>
      <span class="text-[11px] text-[#697386]">e <?= (int) $summary['phoneCalls'] ?> ligações</span>
    </div>
    <p class="text-[11px] text-[#697386]">Canal com maior taxa de fechamento</p>
  </div>
</div>

<!-- 3. Gráfico de acessos -->
<div class="bg-white rounded-2xl border border-[#E4E7EC] p-6 shadow-2xs space-y-6">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-[#E4E7EC]">
    <div>
      <h2 class="font-heading font-bold text-base text-[#202124]">Fluxo de Acessos &amp; Solicitações no Período</h2>
      <p class="text-xs text-[#697386]">Volume comparativo de visitantes e leads técnicos captados</p>
    </div>
    <div class="flex items-center gap-4 text-xs">
      <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-[#D71920]"></span><span class="text-[#697386]">Visitantes</span></div>
      <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-[#202124]"></span><span class="text-[#697386]">Pageviews</span></div>
      <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-emerald-500"></span><span class="text-[#697386]">Leads</span></div>
    </div>
  </div>

  <div class="space-y-4 pt-2">
    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-7 gap-3">
      <?php foreach ($analytics['dailyVisits'] as $item):
          $heightPercent = max(15, (int) round(($item['visitors'] / $maxVisitors) * 100)); ?>
        <div class="flex flex-col items-center gap-2">
          <div class="w-full h-36 bg-[#F5F6F8] rounded-xl flex items-end p-2 border border-[#E4E7EC] relative group">
            <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-[#202124] text-white text-[10px] py-1 px-2 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-10 font-mono">
              <?= (int) $item['visitors'] ?> visitas · <?= (int) $item['leads'] ?> leads
            </div>
            <div class="w-full bg-[#D71920] hover:bg-[#A90F17] rounded-lg transition-all duration-300 relative flex items-center justify-center text-[10px] text-white font-bold" style="height: <?= $heightPercent ?>%">
              <?php if ($item['visitors'] > 15): ?>
                <span class="font-mono text-[10px]"><?= (int) $item['visitors'] ?></span>
              <?php endif; ?>
            </div>
          </div>
          <span class="text-xs font-semibold text-[#202124] text-center"><?= e($item['date']) ?></span>
          <span class="text-[10px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
            <?= (int) $item['leads'] ?> <?= (int) $item['leads'] === 1 ? 'lead' : 'leads' ?>
          </span>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- 4. Funil + Canais -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
  <div class="lg:col-span-6 bg-white rounded-2xl border border-[#E4E7EC] p-6 shadow-2xs space-y-4">
    <div class="pb-3 border-b border-[#E4E7EC]">
      <h3 class="font-heading font-bold text-sm text-[#202124]">Funil de Conversão do Site</h3>
      <p class="text-xs text-[#697386]">Jornada do visitante desde o primeiro clique até a solicitação de OS</p>
    </div>

    <div class="space-y-4 pt-1">
      <?php foreach ($analytics['conversionFunnel'] as $step): ?>
        <div class="space-y-1.5">
          <div class="flex items-center justify-between text-xs">
            <span class="font-semibold text-[#202124]"><?= e($step['stage']) ?></span>
            <div class="flex items-center gap-2">
              <span class="font-mono text-[#697386]"><?= num_br($step['count']) ?></span>
              <span class="font-bold text-[#D71920] bg-[#F5F6F8] px-1.5 py-0.5 rounded text-[11px]"><?= e(js_number($step['percentage'])) ?>%</span>
            </div>
          </div>
          <div class="w-full h-2.5 bg-[#F5F6F8] rounded-full overflow-hidden border border-[#E4E7EC]/60">
            <div class="h-full bg-gradient-to-r from-[#D71920] to-[#A90F17] rounded-full transition-all duration-500" style="width: <?= (float) $step['percentage'] ?>%"></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="p-3.5 rounded-xl bg-[#F5F6F8] border border-[#E4E7EC] text-xs text-[#697386] space-y-1">
      <span class="font-bold text-[#202124] block">💡 Otimização de Conversão:</span>
      <span>A página inicial e o botão direto para o WhatsApp representam o maior ponto de conversão imediata, especialmente para usuários de smartphones na orla e zona sul de João Pessoa.</span>
    </div>
  </div>

  <div class="lg:col-span-6 bg-white rounded-2xl border border-[#E4E7EC] p-6 shadow-2xs space-y-4">
    <div class="pb-3 border-b border-[#E4E7EC]">
      <h3 class="font-heading font-bold text-sm text-[#202124]">Canais de Origem do Tráfego</h3>
      <p class="text-xs text-[#697386]">Como os clientes de João Pessoa e região encontram a empresa</p>
    </div>

    <div class="space-y-4 pt-1">
      <?php foreach ($analytics['trafficSources'] as $source): ?>
        <div class="p-3.5 rounded-xl bg-[#F5F6F8] border border-[#E4E7EC] space-y-2">
          <div class="flex items-center justify-between text-xs">
            <span class="font-bold text-[#202124]"><?= e($source['source']) ?></span>
            <div class="flex items-center gap-2">
              <span class="font-mono text-[#697386]"><?= (int) $source['visits'] ?> visitas</span>
              <span class="font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 text-[11px]"><?= (int) $source['conversions'] ?> leads</span>
            </div>
          </div>
          <div class="w-full h-2 bg-white rounded-full overflow-hidden border border-[#E4E7EC]">
            <div class="h-full bg-[#D71920] rounded-full" style="width: <?= (float) $source['percentage'] ?>%"></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- 5. Municípios + Dispositivos -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
  <div class="lg:col-span-8 bg-white rounded-2xl border border-[#E4E7EC] p-6 shadow-2xs space-y-4">
    <div class="flex items-center justify-between pb-3 border-b border-[#E4E7EC]">
      <div>
        <h3 class="font-heading font-bold text-sm text-[#202124]">Acessos por Município (Grande João Pessoa)</h3>
        <p class="text-xs text-[#697386]">Concentração geográfica das demandas de suporte e manutenção</p>
      </div>
      <?= icon('MapPin', 'w-4 h-4 text-[#D71920]') ?>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead>
          <tr class="border-b border-[#E4E7EC] text-[#697386] uppercase tracking-wider text-[10px]">
            <th class="py-2.5 px-3">Cidade</th>
            <th class="py-2.5 px-3">Visitas</th>
            <th class="py-2.5 px-3">Participação</th>
            <th class="py-2.5 px-3">Leads Gerados</th>
            <th class="py-2.5 px-3 text-right">Taxa Conv.</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[#E4E7EC]/70">
          <?php foreach ($analytics['cityStats'] as $city): ?>
            <tr class="hover:bg-[#F5F6F8]/60 transition-colors">
              <td class="py-3 px-3 font-bold text-[#202124]"><?= e($city['city']) ?></td>
              <td class="py-3 px-3 font-mono text-[#697386]"><?= (int) $city['visits'] ?></td>
              <td class="py-3 px-3">
                <div class="flex items-center gap-2">
                  <div class="w-16 h-1.5 bg-[#F5F6F8] rounded-full overflow-hidden border border-[#E4E7EC]">
                    <div class="h-full bg-[#D71920] rounded-full" style="width: <?= (float) $city['percentage'] ?>%"></div>
                  </div>
                  <span class="font-semibold text-xs text-[#202124]"><?= e(js_number($city['percentage'])) ?>%</span>
                </div>
              </td>
              <td class="py-3 px-3 font-semibold text-emerald-700"><?= (int) $city['leads'] ?></td>
              <td class="py-3 px-3 text-right font-mono font-bold text-[#202124]">
                <?= $city['visits'] > 0 ? number_format($city['leads'] / $city['visits'] * 100, 1, '.', '') : '0.0' ?>%
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="lg:col-span-4 bg-white rounded-2xl border border-[#E4E7EC] p-6 shadow-2xs space-y-4">
    <div class="pb-3 border-b border-[#E4E7EC]">
      <h3 class="font-heading font-bold text-sm text-[#202124]">Dispositivos dos Visitantes</h3>
      <p class="text-xs text-[#697386]">Distribuição entre celulares e computadores</p>
    </div>

    <div class="space-y-4 pt-1">
      <?php foreach ($analytics['deviceBreakdown'] as $dev): ?>
        <div class="p-3.5 rounded-xl bg-[#F5F6F8] border border-[#E4E7EC] space-y-2">
          <div class="flex items-center justify-between text-xs">
            <div class="flex items-center gap-2 font-bold text-[#202124]">
              <?= str_contains((string) $dev['device'], 'Mobile') ? icon('Smartphone', 'w-4 h-4 text-[#D71920]') : icon('Monitor', 'w-4 h-4 text-[#202124]') ?>
              <span><?= e($dev['device']) ?></span>
            </div>
            <span class="font-bold text-[#D71920] text-sm"><?= e(js_number($dev['percentage'])) ?>%</span>
          </div>
          <div class="w-full h-2 bg-white rounded-full overflow-hidden border border-[#E4E7EC]">
            <div class="h-full bg-[#D71920] rounded-full" style="width: <?= (float) $dev['percentage'] ?>%"></div>
          </div>
          <div class="text-[10px] text-[#697386]"><?= num_br($dev['visits']) ?> acessos registrados</div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- 6. Páginas mais acessadas -->
<div class="bg-white rounded-2xl border border-[#E4E7EC] p-6 shadow-2xs space-y-4">
  <div class="flex items-center justify-between pb-3 border-b border-[#E4E7EC]">
    <div>
      <h3 class="font-heading font-bold text-base text-[#202124]">Páginas Mais Acessadas &amp; Desempenho</h3>
      <p class="text-xs text-[#697386]">Ranking das URLs mais visitadas e eficiência de captação de clientes</p>
    </div>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-left text-xs">
      <thead>
        <tr class="border-b border-[#E4E7EC] text-[#697386] uppercase tracking-wider text-[10px]">
          <th class="py-2.5 px-3">Página / Título</th>
          <th class="py-2.5 px-3">Visualizações</th>
          <th class="py-2.5 px-3">Usuários Únicos</th>
          <th class="py-2.5 px-3">Tempo Médio</th>
          <th class="py-2.5 px-3 text-right">Conversões Geradas</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[#E4E7EC]/70">
        <?php foreach ($analytics['topPages'] as $page): ?>
          <tr class="hover:bg-[#F5F6F8]/60 transition-colors">
            <td class="py-3 px-3">
              <div class="font-bold text-[#202124]"><?= e($page['title']) ?></div>
              <div class="text-[10px] font-mono text-[#697386]"><?= e($page['path']) ?></div>
            </td>
            <td class="py-3 px-3 font-mono font-bold text-[#202124]"><?= num_br($page['views']) ?></td>
            <td class="py-3 px-3 font-mono text-[#697386]"><?= num_br($page['uniqueViews']) ?></td>
            <td class="py-3 px-3 font-mono text-[#697386]"><?= e($page['avgTime']) ?></td>
            <td class="py-3 px-3 text-right font-mono font-bold text-emerald-700"><?= (int) $page['conversionCount'] ?> contatos</td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
