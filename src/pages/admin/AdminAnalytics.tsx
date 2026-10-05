import React from 'react';
import { useApp } from '../../context/AppContext';
import { AdminLayout } from '../../components/admin/AdminLayout';
import {
  BarChart3,
  TrendingUp,
  Users,
  Eye,
  Clock,
  ArrowUpRight,
  Smartphone,
  Monitor,
  Download,
  Printer,
  Calendar,
  MapPin,
  CheckCircle2,
  PhoneCall,
  Search,
} from 'lucide-react';

export const AdminAnalytics: React.FC = () => {
  const { analyticsTimeframe, setAnalyticsTimeframe, currentAnalytics, showToast } = useApp();

  const summary = currentAnalytics.summary;

  const exportCSV = () => {
    const csvContent =
      'data:text/csv;charset=utf-8,' +
      'Data/Periodo,Visitantes,Pageviews,Leads\n' +
      currentAnalytics.dailyVisits
        .map((d) => `"${d.date}",${d.visitors},${d.pageViews},${d.leads}`)
        .join('\n');

    const encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', `pcresolve_relatorio_analytics_${analyticsTimeframe}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    showToast('Relatório exportado em formato CSV com sucesso!', 'success');
  };

  const handlePrint = () => {
    window.print();
  };

  // Find max visitors for chart scaling
  const maxVisitors = Math.max(...currentAnalytics.dailyVisits.map((d) => d.visitors), 1);

  return (
    <AdminLayout
      title="Relatórios Analíticos & Estatísticas de Visitas"
      subtitle="Métricas de tráfego, funil de conversão e comportamento dos usuários no site da PC Resolve"
      action={
        <div className="flex items-center gap-2">
          <button
            onClick={exportCSV}
            className="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-[#E4E7EC] bg-white hover:bg-neutral-50 text-xs font-semibold text-[#202124] transition-colors cursor-pointer"
          >
            <Download className="w-3.5 h-3.5 text-[#D71920]" />
            <span>Exportar CSV</span>
          </button>
          <button
            onClick={handlePrint}
            className="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-[#E4E7EC] bg-white hover:bg-neutral-50 text-xs font-semibold text-[#202124] transition-colors cursor-pointer"
          >
            <Printer className="w-3.5 h-3.5 text-[#697386]" />
            <span>Imprimir</span>
          </button>
        </div>
      }
    >
      {/* 1. Timeframe Switcher */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-3 rounded-2xl border border-[#E4E7EC] shadow-2xs">
        <div className="flex items-center gap-2 text-xs font-semibold text-[#202124]">
          <Calendar className="w-4 h-4 text-[#D71920]" />
          <span>Filtrar período de análise:</span>
        </div>

        <div className="flex items-center gap-1 bg-[#F5F6F8] p-1 rounded-xl border border-[#E4E7EC]">
          {(
            [
              { key: 'hoje', label: 'Hoje' },
              { key: '7dias', label: 'Últimos 7 dias' },
              { key: '30dias', label: 'Últimos 30 dias' },
              { key: 'mes', label: 'Mês Atual' },
            ] as const
          ).map((t) => (
            <button
              key={t.key}
              onClick={() => setAnalyticsTimeframe(t.key)}
              className={`px-3.5 py-1.5 text-xs font-semibold rounded-lg transition-colors cursor-pointer ${
                analyticsTimeframe === t.key
                  ? 'bg-white text-[#D71920] shadow-xs'
                  : 'text-[#697386] hover:text-[#202124]'
              }`}
            >
              {t.label}
            </button>
          ))}
        </div>
      </div>

      {/* 2. Top Metric Cards */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {/* Metric 1: Visitas Totais */}
        <div className="bg-white p-5 rounded-2xl border border-[#E4E7EC] shadow-2xs space-y-2">
          <div className="flex items-center justify-between text-xs text-[#697386]">
            <span>Total de Visitas</span>
            <Users className="w-4 h-4 text-[#D71920]" />
          </div>
          <div className="flex items-baseline gap-2">
            <span className="font-heading font-extrabold text-2xl text-[#202124] font-mono-numbers">
              {summary.totalVisits.toLocaleString('pt-BR')}
            </span>
            <span className="text-[11px] text-emerald-600 font-medium flex items-center">
              <ArrowUpRight className="w-3 h-3" /> +12.4%
            </span>
          </div>
          <p className="text-[11px] text-[#697386]">
            {summary.uniqueVisitors.toLocaleString('pt-BR')} visitantes únicos
          </p>
        </div>

        {/* Metric 2: Pageviews */}
        <div className="bg-white p-5 rounded-2xl border border-[#E4E7EC] shadow-2xs space-y-2">
          <div className="flex items-center justify-between text-xs text-[#697386]">
            <span>Páginas Visualizadas</span>
            <Eye className="w-4 h-4 text-[#D71920]" />
          </div>
          <div className="flex items-baseline gap-2">
            <span className="font-heading font-extrabold text-2xl text-[#202124] font-mono-numbers">
              {summary.pageViews.toLocaleString('pt-BR')}
            </span>
            <span className="text-[11px] text-emerald-600 font-medium flex items-center">
              <ArrowUpRight className="w-3 h-3" /> +8.1%
            </span>
          </div>
          <p className="text-[11px] text-[#697386]">
            Média de {(summary.pageViews / summary.totalVisits).toFixed(1)} páginas por sessão
          </p>
        </div>

        {/* Metric 3: Taxa de Conversão */}
        <div className="bg-white p-5 rounded-2xl border border-[#E4E7EC] shadow-2xs space-y-2">
          <div className="flex items-center justify-between text-xs text-[#697386]">
            <span>Taxa de Conversão</span>
            <TrendingUp className="w-4 h-4 text-emerald-600" />
          </div>
          <div className="flex items-baseline gap-2">
            <span className="font-heading font-extrabold text-2xl text-emerald-700 font-mono-numbers">
              {summary.conversionRate}
            </span>
            <span className="text-[11px] text-emerald-600 font-medium">Acima da média</span>
          </div>
          <p className="text-[11px] text-[#697386]">
            {summary.totalLeads} contatos técnicos gerados
          </p>
        </div>

        {/* Metric 4: Cliques no WhatsApp */}
        <div className="bg-white p-5 rounded-2xl border border-[#E4E7EC] shadow-2xs space-y-2">
          <div className="flex items-center justify-between text-xs text-[#697386]">
            <span>Cliques no WhatsApp</span>
            <span className="w-4 h-4 flex items-center justify-center font-bold text-[#25D366]">W</span>
          </div>
          <div className="flex items-baseline gap-2">
            <span className="font-heading font-extrabold text-2xl text-[#202124] font-mono-numbers">
              {summary.whatsappClicks}
            </span>
            <span className="text-[11px] text-[#697386]">e {summary.phoneCalls} ligações</span>
          </div>
          <p className="text-[11px] text-[#697386]">Canal com maior taxa de fechamento</p>
        </div>
      </div>

      {/* 3. Main Chart: Evolution of Visits Over Selected Timeframe */}
      <div className="bg-white rounded-2xl border border-[#E4E7EC] p-6 shadow-2xs space-y-6">
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-[#E4E7EC]">
          <div>
            <h2 className="font-heading font-bold text-base text-[#202124]">
              Fluxo de Acessos &amp; Solicitações no Período
            </h2>
            <p className="text-xs text-[#697386]">
              Volume comparativo de visitantes e leads técnicos captados
            </p>
          </div>

          <div className="flex items-center gap-4 text-xs">
            <div className="flex items-center gap-1.5">
              <span className="w-3 h-3 rounded-sm bg-[#D71920]"></span>
              <span className="text-[#697386]">Visitantes</span>
            </div>
            <div className="flex items-center gap-1.5">
              <span className="w-3 h-3 rounded-sm bg-[#202124]"></span>
              <span className="text-[#697386]">Pageviews</span>
            </div>
            <div className="flex items-center gap-1.5">
              <span className="w-3 h-3 rounded-sm bg-emerald-500"></span>
              <span className="text-[#697386]">Leads</span>
            </div>
          </div>
        </div>

        {/* Visual Bar Graph */}
        <div className="space-y-4 pt-2">
          <div className="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-7 gap-3">
            {currentAnalytics.dailyVisits.map((item, idx) => {
              const heightPercent = Math.max(15, Math.round((item.visitors / maxVisitors) * 100));
              return (
                <div key={idx} className="flex flex-col items-center gap-2">
                  <div className="w-full h-36 bg-[#F5F6F8] rounded-xl flex items-end p-2 border border-[#E4E7EC] relative group">
                    {/* Tooltip */}
                    <div className="absolute -top-10 left-1/2 -translate-x-1/2 bg-[#202124] text-white text-[10px] py-1 px-2 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-10 font-mono">
                      {item.visitors} visitas · {item.leads} leads
                    </div>

                    {/* Bar */}
                    <div
                      className="w-full bg-[#D71920] hover:bg-[#A90F17] rounded-lg transition-all duration-300 relative flex items-center justify-center text-[10px] text-white font-bold"
                      style={{ height: `${heightPercent}%` }}
                    >
                      {item.visitors > 15 && (
                        <span className="font-mono text-[10px]">{item.visitors}</span>
                      )}
                    </div>
                  </div>

                  <span className="text-xs font-semibold text-[#202124] text-center">
                    {item.date}
                  </span>
                  <span className="text-[10px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                    {item.leads} {item.leads === 1 ? 'lead' : 'leads'}
                  </span>
                </div>
              );
            })}
          </div>
        </div>
      </div>

      {/* 4. Conversion Funnel & Traffic Sources */}
      <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {/* Conversion Funnel (Col 6) */}
        <div className="lg:col-span-6 bg-white rounded-2xl border border-[#E4E7EC] p-6 shadow-2xs space-y-4">
          <div className="pb-3 border-b border-[#E4E7EC]">
            <h3 className="font-heading font-bold text-sm text-[#202124]">
              Funil de Conversão do Site
            </h3>
            <p className="text-xs text-[#697386]">
              Jornada do visitante desde o primeiro clique até a solicitação de OS
            </p>
          </div>

          <div className="space-y-4 pt-1">
            {currentAnalytics.conversionFunnel.map((step, i) => (
              <div key={i} className="space-y-1.5">
                <div className="flex items-center justify-between text-xs">
                  <span className="font-semibold text-[#202124]">{step.stage}</span>
                  <div className="flex items-center gap-2">
                    <span className="font-mono text-[#697386]">{step.count.toLocaleString()}</span>
                    <span className="font-bold text-[#D71920] bg-[#F5F6F8] px-1.5 py-0.5 rounded text-[11px]">
                      {step.percentage}%
                    </span>
                  </div>
                </div>
                <div className="w-full h-2.5 bg-[#F5F6F8] rounded-full overflow-hidden border border-[#E4E7EC]/60">
                  <div
                    className="h-full bg-gradient-to-r from-[#D71920] to-[#A90F17] rounded-full transition-all duration-500"
                    style={{ width: `${step.percentage}%` }}
                  ></div>
                </div>
              </div>
            ))}
          </div>

          <div className="p-3.5 rounded-xl bg-[#F5F6F8] border border-[#E4E7EC] text-xs text-[#697386] space-y-1">
            <span className="font-bold text-[#202124] block">💡 Otimização de Conversão:</span>
            <span>
              A página inicial e o botão direto para o WhatsApp representam o maior ponto de conversão imediata, especialmente para usuários de smartphones na orla e zona sul de João Pessoa.
            </span>
          </div>
        </div>

        {/* Traffic Sources & Search Channels (Col 6) */}
        <div className="lg:col-span-6 bg-white rounded-2xl border border-[#E4E7EC] p-6 shadow-2xs space-y-4">
          <div className="pb-3 border-b border-[#E4E7EC]">
            <h3 className="font-heading font-bold text-sm text-[#202124]">
              Canais de Origem do Tráfego
            </h3>
            <p className="text-xs text-[#697386]">
              Como os clientes de João Pessoa e região encontram a empresa
            </p>
          </div>

          <div className="space-y-4 pt-1">
            {currentAnalytics.trafficSources.map((source, i) => (
              <div key={i} className="p-3.5 rounded-xl bg-[#F5F6F8] border border-[#E4E7EC] space-y-2">
                <div className="flex items-center justify-between text-xs">
                  <span className="font-bold text-[#202124]">{source.source}</span>
                  <div className="flex items-center gap-2">
                    <span className="font-mono text-[#697386]">{source.visits} visitas</span>
                    <span className="font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 text-[11px]">
                      {source.conversions} leads
                    </span>
                  </div>
                </div>
                <div className="w-full h-2 bg-white rounded-full overflow-hidden border border-[#E4E7EC]">
                  <div
                    className="h-full bg-[#D71920] rounded-full"
                    style={{ width: `${source.percentage}%` }}
                  ></div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>

      {/* 5. Geographic Breakdown (Cities) & Device Breakdown */}
      <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {/* Cities Breakdown (Col 8) */}
        <div className="lg:col-span-8 bg-white rounded-2xl border border-[#E4E7EC] p-6 shadow-2xs space-y-4">
          <div className="flex items-center justify-between pb-3 border-b border-[#E4E7EC]">
            <div>
              <h3 className="font-heading font-bold text-sm text-[#202124]">
                Acessos por Município (Grande João Pessoa)
              </h3>
              <p className="text-xs text-[#697386]">
                Concentração geográfica das demandas de suporte e manutenção
              </p>
            </div>
            <MapPin className="w-4 h-4 text-[#D71920]" />
          </div>

          <div className="overflow-x-auto">
            <table className="w-full text-left text-xs">
              <thead>
                <tr className="border-b border-[#E4E7EC] text-[#697386] uppercase tracking-wider text-[10px]">
                  <th className="py-2.5 px-3">Cidade</th>
                  <th className="py-2.5 px-3">Visitas</th>
                  <th className="py-2.5 px-3">Participação</th>
                  <th className="py-2.5 px-3">Leads Gerados</th>
                  <th className="py-2.5 px-3 text-right">Taxa Conv.</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-[#E4E7EC]/70">
                {currentAnalytics.cityStats.map((city, idx) => (
                  <tr key={idx} className="hover:bg-[#F5F6F8]/60 transition-colors">
                    <td className="py-3 px-3 font-bold text-[#202124]">{city.city}</td>
                    <td className="py-3 px-3 font-mono text-[#697386]">{city.visits}</td>
                    <td className="py-3 px-3">
                      <div className="flex items-center gap-2">
                        <div className="w-16 h-1.5 bg-[#F5F6F8] rounded-full overflow-hidden border border-[#E4E7EC]">
                          <div
                            className="h-full bg-[#D71920] rounded-full"
                            style={{ width: `${city.percentage}%` }}
                          ></div>
                        </div>
                        <span className="font-semibold text-xs text-[#202124]">{city.percentage}%</span>
                      </div>
                    </td>
                    <td className="py-3 px-3 font-semibold text-emerald-700">{city.leads}</td>
                    <td className="py-3 px-3 text-right font-mono font-bold text-[#202124]">
                      {((city.leads / city.visits) * 100).toFixed(1)}%
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>

        {/* Devices Breakdown (Col 4) */}
        <div className="lg:col-span-4 bg-white rounded-2xl border border-[#E4E7EC] p-6 shadow-2xs space-y-4">
          <div className="pb-3 border-b border-[#E4E7EC]">
            <h3 className="font-heading font-bold text-sm text-[#202124]">
              Dispositivos dos Visitantes
            </h3>
            <p className="text-xs text-[#697386]">Distribuição entre celulares e computadores</p>
          </div>

          <div className="space-y-4 pt-1">
            {currentAnalytics.deviceBreakdown.map((dev, i) => (
              <div key={i} className="p-3.5 rounded-xl bg-[#F5F6F8] border border-[#E4E7EC] space-y-2">
                <div className="flex items-center justify-between text-xs">
                  <div className="flex items-center gap-2 font-bold text-[#202124]">
                    {dev.device.includes('Mobile') ? (
                      <Smartphone className="w-4 h-4 text-[#D71920]" />
                    ) : (
                      <Monitor className="w-4 h-4 text-[#202124]" />
                    )}
                    <span>{dev.device}</span>
                  </div>
                  <span className="font-bold text-[#D71920] text-sm">{dev.percentage}%</span>
                </div>
                <div className="w-full h-2 bg-white rounded-full overflow-hidden border border-[#E4E7EC]">
                  <div
                    className="h-full bg-[#D71920] rounded-full"
                    style={{ width: `${dev.percentage}%` }}
                  ></div>
                </div>
                <div className="text-[10px] text-[#697386]">
                  {dev.visits.toLocaleString()} acessos registrados
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>

      {/* 6. Top Pages & Performance Breakdown */}
      <div className="bg-white rounded-2xl border border-[#E4E7EC] p-6 shadow-2xs space-y-4">
        <div className="flex items-center justify-between pb-3 border-b border-[#E4E7EC]">
          <div>
            <h3 className="font-heading font-bold text-base text-[#202124]">
              Páginas Mais Acessadas &amp; Desempenho
            </h3>
            <p className="text-xs text-[#697386]">
              Ranking das URLs mais visitadas e eficiência de captação de clientes
            </p>
          </div>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full text-left text-xs">
            <thead>
              <tr className="border-b border-[#E4E7EC] text-[#697386] uppercase tracking-wider text-[10px]">
                <th className="py-2.5 px-3">Página / Título</th>
                <th className="py-2.5 px-3">Visualizações</th>
                <th className="py-2.5 px-3">Usuários Únicos</th>
                <th className="py-2.5 px-3">Tempo Médio</th>
                <th className="py-2.5 px-3 text-right">Conversões Geradas</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-[#E4E7EC]/70">
              {currentAnalytics.topPages.map((page, idx) => (
                <tr key={idx} className="hover:bg-[#F5F6F8]/60 transition-colors">
                  <td className="py-3 px-3">
                    <div className="font-bold text-[#202124]">{page.title}</div>
                    <div className="text-[10px] font-mono text-[#697386]">{page.path}</div>
                  </td>
                  <td className="py-3 px-3 font-mono font-bold text-[#202124]">
                    {page.views.toLocaleString()}
                  </td>
                  <td className="py-3 px-3 font-mono text-[#697386]">
                    {page.uniqueViews.toLocaleString()}
                  </td>
                  <td className="py-3 px-3 font-mono text-[#697386]">{page.avgTime}</td>
                  <td className="py-3 px-3 text-right font-mono font-bold text-emerald-700">
                    {page.conversionCount} contatos
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </AdminLayout>
  );
};
