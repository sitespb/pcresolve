import React, { useState } from 'react';
import { useApp } from '../../context/AppContext';
import { AdminLayout } from '../../components/admin/AdminLayout';
import {
  ClipboardList,
  Clock,
  CheckCircle2,
  DollarSign,
  TrendingUp,
  ArrowUpRight,
  Plus,
  Phone,
  Eye,
  Sliders,
  AlertCircle,
  FileSpreadsheet,
} from 'lucide-react';
import { LeadStatus } from '../../types';

export const AdminDashboard: React.FC = () => {
  const { leads, services, updateLeadStatus, navigateTo, currentAnalytics } = useApp();

  const [statusFilter, setStatusFilter] = useState<string>('todos');

  const pendingCount = leads.filter((l) => l.status === 'pendente').length;
  const inProgressCount = leads.filter(
    (l) => l.status === 'em_diagnostico' || l.status === 'em_execucao'
  ).length;
  const completedCount = leads.filter(
    (l) => l.status === 'concluido' || l.status === 'entregue'
  ).length;

  const totalEstimatedRevenue = leads.reduce((acc, l) => acc + (l.finalBudget || l.estimatedBudget || 0), 0);

  const getStatusBadge = (status: LeadStatus) => {
    const map: Record<LeadStatus, { label: string; class: string }> = {
      pendente: { label: 'Pendente', class: 'bg-amber-50 text-amber-800 border-amber-200' },
      em_diagnostico: { label: 'Em Diagnóstico', class: 'bg-blue-50 text-blue-800 border-blue-200' },
      aguardando_aprovacao: { label: 'Aguardando Aprovação', class: 'bg-purple-50 text-purple-800 border-purple-200' },
      em_execucao: { label: 'Em Execução', class: 'bg-orange-50 text-orange-800 border-orange-200' },
      concluido: { label: 'Concluído', class: 'bg-emerald-50 text-emerald-800 border-emerald-200' },
      entregue: { label: 'Entregue', class: 'bg-neutral-100 text-neutral-800 border-neutral-300' },
    };
    return map[status] || map.pendente;
  };

  const filteredLeads = leads.filter((l) => {
    if (statusFilter === 'todos') return true;
    return l.status === statusFilter;
  });

  return (
    <AdminLayout
      title="Painel de Controle Técnico"
      subtitle="Visão operacional das ordens de serviço, solicitações de atendimento e métricas de desempenho"
      action={
        <div className="flex items-center gap-2">
          <button
            onClick={() => navigateTo('admin-analytics')}
            className="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-[#E4E7EC] bg-white hover:bg-neutral-50 text-xs font-semibold text-[#202124] transition-colors cursor-pointer"
          >
            <TrendingUp className="w-3.5 h-3.5 text-[#D71920]" />
            <span>Ver Estatísticas</span>
          </button>
          <button
            onClick={() => navigateTo('admin-solicitacoes')}
            className="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold shadow-xs transition-colors cursor-pointer"
          >
            <Plus className="w-3.5 h-3.5" />
            <span>Gerenciar O.S.</span>
          </button>
        </div>
      }
    >
      {/* 1. Key Metric Cards */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {/* Metric 1 */}
        <div className="bg-white p-5 rounded-2xl border border-[#E4E7EC] shadow-2xs space-y-2">
          <div className="flex items-center justify-between text-xs text-[#697386]">
            <span>Chamados Pendentes</span>
            <span className="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
              <AlertCircle className="w-4 h-4" />
            </span>
          </div>
          <div className="flex items-baseline gap-2">
            <span className="font-heading font-extrabold text-2xl text-[#202124] font-mono-numbers">
              {pendingCount}
            </span>
            <span className="text-[11px] text-amber-700 font-medium">Requer atenção</span>
          </div>
          <p className="text-[11px] text-[#697386]">Triagem imediata de novos clientes</p>
        </div>

        {/* Metric 2 */}
        <div className="bg-white p-5 rounded-2xl border border-[#E4E7EC] shadow-2xs space-y-2">
          <div className="flex items-center justify-between text-xs text-[#697386]">
            <span>Em Bancada / Execução</span>
            <span className="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
              <Sliders className="w-4 h-4" />
            </span>
          </div>
          <div className="flex items-baseline gap-2">
            <span className="font-heading font-extrabold text-2xl text-[#202124] font-mono-numbers">
              {inProgressCount}
            </span>
            <span className="text-[11px] text-blue-700 font-medium">Laboratório ativo</span>
          </div>
          <p className="text-[11px] text-[#697386]">Máquinas em testes e reparo</p>
        </div>

        {/* Metric 3 */}
        <div className="bg-white p-5 rounded-2xl border border-[#E4E7EC] shadow-2xs space-y-2">
          <div className="flex items-center justify-between text-xs text-[#697386]">
            <span>Ordens Concluídas</span>
            <span className="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
              <CheckCircle2 className="w-4 h-4" />
            </span>
          </div>
          <div className="flex items-baseline gap-2">
            <span className="font-heading font-extrabold text-2xl text-[#202124] font-mono-numbers">
              {completedCount}
            </span>
            <span className="text-[11px] text-emerald-700 font-medium">98% aprovação</span>
          </div>
          <p className="text-[11px] text-[#697386]">Garantia de 90 dias ativada</p>
        </div>

        {/* Metric 4 */}
        <div className="bg-white p-5 rounded-2xl border border-[#E4E7EC] shadow-2xs space-y-2">
          <div className="flex items-center justify-between text-xs text-[#697386]">
            <span>Volume em Orçamentos</span>
            <span className="w-8 h-8 rounded-lg bg-[#F5F6F8] text-[#D71920] flex items-center justify-center font-bold">
              <DollarSign className="w-4 h-4" />
            </span>
          </div>
          <div className="flex items-baseline gap-2">
            <span className="font-heading font-extrabold text-2xl text-[#202124] font-mono-numbers">
              R$ {totalEstimatedRevenue.toLocaleString('pt-BR', { minimumFractionDigits: 0 })}
            </span>
          </div>
          <p className="text-[11px] text-[#697386]">Acumulado dos atendimentos</p>
        </div>
      </div>

      {/* 2. Quick Alerts & Website Conversion Snapshot */}
      <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {/* Left: Recent Activity Feed */}
        <div className="lg:col-span-8 bg-white rounded-2xl border border-[#E4E7EC] p-6 shadow-2xs space-y-4">
          <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-[#E4E7EC]">
            <div>
              <h2 className="font-heading font-bold text-base text-[#202124]">
                Ordens de Serviço e Solicitações Recentes
              </h2>
              <p className="text-xs text-[#697386]">
                Acompanhamento em tempo real dos contatos gerados pelo site
              </p>
            </div>

            {/* Filter buttons */}
            <div className="flex items-center gap-1 overflow-x-auto p-1 bg-[#F5F6F8] rounded-lg">
              {['todos', 'pendente', 'em_execucao', 'concluido'].map((st) => (
                <button
                  key={st}
                  onClick={() => setStatusFilter(st)}
                  className={`px-2.5 py-1 text-[11px] font-semibold rounded-md transition-colors capitalize ${
                    statusFilter === st
                      ? 'bg-white text-[#D71920] shadow-2xs'
                      : 'text-[#697386] hover:text-[#202124]'
                  }`}
                >
                  {st.replace('_', ' ')}
                </button>
              ))}
            </div>
          </div>

          {/* Table */}
          <div className="overflow-x-auto">
            <table className="w-full text-left text-xs">
              <thead>
                <tr className="border-b border-[#E4E7EC] text-[#697386] uppercase tracking-wider text-[10px]">
                  <th className="py-2.5 px-3">Protocolo</th>
                  <th className="py-2.5 px-3">Cliente</th>
                  <th className="py-2.5 px-3">Cidade / Bairro</th>
                  <th className="py-2.5 px-3">Serviço</th>
                  <th className="py-2.5 px-3">Status</th>
                  <th className="py-2.5 px-3 text-right">Ação</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-[#E4E7EC]/70">
                {filteredLeads.slice(0, 5).map((lead) => {
                  const badge = getStatusBadge(lead.status);
                  const cleanP = lead.phone.replace(/\D/g, '');
                  return (
                    <tr key={lead.id} className="hover:bg-[#F5F6F8]/60 transition-colors">
                      <td className="py-3 px-3 font-mono font-bold text-[#D71920]">
                        {lead.protocol}
                      </td>
                      <td className="py-3 px-3 font-semibold text-[#202124]">
                        <div>{lead.customerName}</div>
                        <div className="text-[10px] text-[#697386] font-normal">{lead.phone}</div>
                      </td>
                      <td className="py-3 px-3 text-[#697386]">{lead.city}</td>
                      <td className="py-3 px-3 text-[#202124] max-w-[180px] truncate" title={lead.serviceType}>
                        {lead.serviceType}
                      </td>
                      <td className="py-3 px-3">
                        <span
                          className={`inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold border ${badge.class}`}
                        >
                          {badge.label}
                        </span>
                      </td>
                      <td className="py-3 px-3 text-right space-x-1.5 whitespace-nowrap">
                        <a
                          href={`https://wa.me/55${cleanP}?text=${encodeURIComponent(
                            `Olá ${lead.customerName}, tudo bem? Sou da assistência técnica PC Resolve sobre a sua solicitação ${lead.protocol}.`
                          )}`}
                          target="_blank"
                          rel="noopener noreferrer"
                          className="inline-flex items-center justify-center p-1.5 rounded-md bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors"
                          title="Falar no WhatsApp"
                        >
                          <Phone className="w-3.5 h-3.5" />
                        </a>
                        <button
                          onClick={() => navigateTo('admin-solicitacoes')}
                          className="inline-flex items-center justify-center p-1.5 rounded-md bg-neutral-100 text-[#202124] hover:bg-neutral-200 transition-colors cursor-pointer"
                          title="Ver Detalhes"
                        >
                          <Eye className="w-3.5 h-3.5" />
                        </button>
                      </td>
                    </tr>
                  );
                })}
              </tbody>
            </table>
          </div>

          <div className="pt-2 text-right">
            <button
              onClick={() => navigateTo('admin-solicitacoes')}
              className="text-xs font-semibold text-[#D71920] hover:underline cursor-pointer"
            >
              Ver todas as solicitações &rarr;
            </button>
          </div>
        </div>

        {/* Right: Quick Website Analytics Snapshot */}
        <div className="lg:col-span-4 space-y-6">
          <div className="bg-white rounded-2xl border border-[#E4E7EC] p-6 shadow-2xs space-y-4">
            <div className="flex items-center justify-between pb-3 border-b border-[#E4E7EC]">
              <h3 className="font-heading font-bold text-sm text-[#202124]">
                Estatísticas do Site (Últimos 7 dias)
              </h3>
              <button
                onClick={() => navigateTo('admin-analytics')}
                className="text-[11px] font-semibold text-[#D71920] hover:underline"
              >
                Detalhes
              </button>
            </div>

            <div className="space-y-3">
              <div className="flex items-center justify-between text-xs">
                <span className="text-[#697386]">Visitas Únicas:</span>
                <span className="font-bold text-[#202124] font-mono-numbers">
                  {currentAnalytics.summary.uniqueVisitors.toLocaleString()}
                </span>
              </div>
              <div className="flex items-center justify-between text-xs">
                <span className="text-[#697386]">Taxa de Conversão:</span>
                <span className="font-bold text-emerald-600 font-mono-numbers">
                  {currentAnalytics.summary.conversionRate}
                </span>
              </div>
              <div className="flex items-center justify-between text-xs">
                <span className="text-[#697386]">Cliques no WhatsApp:</span>
                <span className="font-bold text-[#202124] font-mono-numbers">
                  {currentAnalytics.summary.whatsappClicks}
                </span>
              </div>
              <div className="flex items-center justify-between text-xs">
                <span className="text-[#697386]">Tempo Médio de Sessão:</span>
                <span className="font-bold text-[#202124] font-mono-numbers">
                  {currentAnalytics.summary.avgTimeOnSite}
                </span>
              </div>
            </div>

            {/* Quick breakdown of traffic sources */}
            <div className="pt-3 border-t border-[#E4E7EC] space-y-2">
              <span className="text-[10px] font-bold uppercase tracking-wider text-[#697386]">
                Origem do Tráfego
              </span>
              {currentAnalytics.trafficSources.map((src, i) => (
                <div key={i} className="space-y-1">
                  <div className="flex justify-between text-[11px]">
                    <span className="text-[#202124] truncate">{src.source}</span>
                    <span className="font-semibold text-[#697386]">{src.percentage}%</span>
                  </div>
                  <div className="w-full h-1.5 bg-[#F5F6F8] rounded-full overflow-hidden">
                    <div
                      className="h-full bg-[#D71920] rounded-full"
                      style={{ width: `${src.percentage}%` }}
                    ></div>
                  </div>
                </div>
              ))}
            </div>
          </div>

          {/* Quick Shortcuts */}
          <div className="bg-[#F5F6F8] rounded-2xl border border-[#E4E7EC] p-5 space-y-3">
            <h4 className="font-heading font-bold text-xs uppercase tracking-wider text-[#202124]">
              Acesso Rápido
            </h4>
            <div className="grid grid-cols-2 gap-2 text-xs">
              <button
                onClick={() => navigateTo('admin-servicos')}
                className="p-3 bg-white rounded-xl border border-[#E4E7EC] text-left hover:border-[#D71920] transition-colors"
              >
                <div className="font-bold text-[#202124]">Serviços</div>
                <div className="text-[10px] text-[#697386]">{services.length} ativos</div>
              </button>
              <button
                onClick={() => navigateTo('admin-configuracoes')}
                className="p-3 bg-white rounded-xl border border-[#E4E7EC] text-left hover:border-[#D71920] transition-colors"
              >
                <div className="font-bold text-[#202124]">Configurações</div>
                <div className="text-[10px] text-[#697386]">Empresa &amp; SEO</div>
              </button>
            </div>
          </div>
        </div>
      </div>
    </AdminLayout>
  );
};
