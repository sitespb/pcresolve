import React, { useState } from 'react';
import { useApp } from '../../context/AppContext';
import { AdminLayout } from '../../components/admin/AdminLayout';
import { LeadRequest, LeadStatus } from '../../types';
import {
  ClipboardList,
  Search,
  Filter,
  Phone,
  Eye,
  CheckCircle2,
  Clock,
  Printer,
  X,
  Save,
  MessageSquare,
  DollarSign,
  AlertCircle,
  Plus,
} from 'lucide-react';

export const AdminRequests: React.FC = () => {
  const {
    leads,
    updateLeadStatus,
    updateLeadBudget,
    addLeadNote,
    addLead,
    showToast,
  } = useApp();

  const [search, setSearch] = useState('');
  const [selectedStatus, setSelectedStatus] = useState<string>('todos');
  const [activeModalLead, setActiveModalLead] = useState<LeadRequest | null>(null);
  const [isNewLeadModalOpen, setIsNewLeadModalOpen] = useState(false);

  // Edit fields for active modal
  const [budgetInput, setBudgetInput] = useState<number>(0);
  const [noteInput, setNoteInput] = useState<string>('');
  const [statusSelect, setStatusSelect] = useState<LeadStatus>('pendente');

  // New lead form state
  const [newCustomerName, setNewCustomerName] = useState('');
  const [newPhone, setNewPhone] = useState('');
  const [newCity, setNewCity] = useState('João Pessoa');
  const [newDeviceType, setNewDeviceType] = useState<any>('notebook');
  const [newServiceType, setNewServiceType] = useState('Manutenção de Notebooks');
  const [newDesc, setNewDesc] = useState('');

  const openLeadModal = (lead: LeadRequest) => {
    setActiveModalLead(lead);
    setBudgetInput(lead.finalBudget || lead.estimatedBudget || 0);
    setNoteInput(lead.internalNotes || '');
    setStatusSelect(lead.status);
  };

  const handleSaveModal = () => {
    if (!activeModalLead) return;
    updateLeadStatus(activeModalLead.id, statusSelect);
    updateLeadBudget(activeModalLead.id, Number(budgetInput));
    addLeadNote(activeModalLead.id, noteInput);
    setActiveModalLead(null);
  };

  const handleCreateNewLead = (e: React.FormEvent) => {
    e.preventDefault();
    if (!newCustomerName.trim() || !newPhone.trim()) {
      showToast('Nome e telefone são obrigatórios.', 'warning');
      return;
    }

    addLead({
      customerName: newCustomerName,
      phone: newPhone,
      city: newCity,
      deviceType: newDeviceType,
      serviceType: newServiceType,
      description: newDesc || 'Cadastrado manualmente via painel administrativo.',
    });

    setIsNewLeadModalOpen(false);
    setNewCustomerName('');
    setNewPhone('');
    setNewDesc('');
  };

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

  const filteredLeads = leads.filter((lead) => {
    const matchStatus = selectedStatus === 'todos' || lead.status === selectedStatus;
    const matchSearch =
      lead.customerName.toLowerCase().includes(search.toLowerCase()) ||
      lead.protocol.toLowerCase().includes(search.toLowerCase()) ||
      lead.phone.includes(search) ||
      lead.city.toLowerCase().includes(search.toLowerCase());
    return matchStatus && matchSearch;
  });

  return (
    <AdminLayout
      title="Gestão de Ordens de Serviço &amp; Leads"
      subtitle="Controle operacional de ordens de serviço, orçamentos e comunicações com os clientes"
      action={
        <button
          onClick={() => setIsNewLeadModalOpen(true)}
          className="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold shadow-xs transition-colors cursor-pointer"
        >
          <Plus className="w-3.5 h-3.5" />
          <span>Cadastrar Nova O.S.</span>
        </button>
      }
    >
      {/* Search and Filters */}
      <div className="bg-white rounded-2xl border border-[#E4E7EC] p-4 shadow-2xs space-y-4">
        <div className="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
          {/* Search */}
          <div className="relative flex-1 max-w-md">
            <Search className="w-4 h-4 text-[#697386] absolute left-3.5 top-1/2 -translate-y-1/2" />
            <input
              type="text"
              placeholder="Buscar por cliente, telefone ou protocolo (ex: OS-2026-0842)..."
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              className="w-full pl-9 pr-3.5 py-2 text-xs rounded-xl border border-[#E4E7EC] bg-white text-[#202124] focus:outline-none focus:border-[#D71920]"
            />
          </div>

          {/* Filter status buttons */}
          <div className="flex items-center gap-1 overflow-x-auto p-1 bg-[#F5F6F8] rounded-xl border border-[#E4E7EC]">
            {[
              { key: 'todos', label: 'Todos' },
              { key: 'pendente', label: 'Pendentes' },
              { key: 'em_diagnostico', label: 'Em Diagnóstico' },
              { key: 'em_execucao', label: 'Em Execução' },
              { key: 'concluido', label: 'Concluídos' },
              { key: 'entregue', label: 'Entregues' },
            ].map((f) => (
              <button
                key={f.key}
                onClick={() => setSelectedStatus(f.key)}
                className={`px-3 py-1.5 text-xs font-semibold rounded-lg transition-colors whitespace-nowrap cursor-pointer ${
                  selectedStatus === f.key
                    ? 'bg-white text-[#D71920] shadow-2xs'
                    : 'text-[#697386] hover:text-[#202124]'
                }`}
              >
                {f.label}
              </button>
            ))}
          </div>
        </div>
      </div>

      {/* Main Table */}
      <div className="bg-white rounded-2xl border border-[#E4E7EC] shadow-2xs overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left text-xs">
            <thead>
              <tr className="bg-[#F5F6F8] border-b border-[#E4E7EC] text-[#697386] uppercase tracking-wider text-[10px]">
                <th className="py-3 px-4 font-bold">Protocolo / Data</th>
                <th className="py-3 px-4 font-bold">Cliente / Contato</th>
                <th className="py-3 px-4 font-bold">Dispositivo</th>
                <th className="py-3 px-4 font-bold">Serviço / Defeito</th>
                <th className="py-3 px-4 font-bold">Orçamento</th>
                <th className="py-3 px-4 font-bold">Status</th>
                <th className="py-3 px-4 font-bold text-right">Ações</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-[#E4E7EC]">
              {filteredLeads.length === 0 ? (
                <tr>
                  <td colSpan={7} className="py-12 text-center text-[#697386]">
                    Nenhuma ordem de serviço encontrada para os filtros selecionados.
                  </td>
                </tr>
              ) : (
                filteredLeads.map((lead) => {
                  const badge = getStatusBadge(lead.status);
                  const cleanP = lead.phone.replace(/\D/g, '');
                  return (
                    <tr key={lead.id} className="hover:bg-[#F5F6F8]/60 transition-colors">
                      <td className="py-3 px-4">
                        <div className="font-mono font-bold text-[#D71920]">{lead.protocol}</div>
                        <div className="text-[10px] text-[#697386]">{lead.createdAt}</div>
                      </td>
                      <td className="py-3 px-4">
                        <div className="font-bold text-[#202124]">{lead.customerName}</div>
                        <div className="text-[11px] text-[#697386]">{lead.phone}</div>
                        <div className="text-[10px] text-[#697386]">{lead.city}</div>
                      </td>
                      <td className="py-3 px-4">
                        <span className="capitalize font-semibold text-[#202124]">
                          {lead.deviceType}
                        </span>
                      </td>
                      <td className="py-3 px-4 max-w-[220px]">
                        <div className="font-semibold text-[#202124] truncate" title={lead.serviceType}>
                          {lead.serviceType}
                        </div>
                        <div className="text-[11px] text-[#697386] truncate" title={lead.description}>
                          {lead.description}
                        </div>
                      </td>
                      <td className="py-3 px-4 font-mono font-bold text-[#202124]">
                        {lead.finalBudget || lead.estimatedBudget
                          ? `R$ ${(lead.finalBudget || lead.estimatedBudget || 0).toFixed(2)}`
                          : 'A definir'}
                      </td>
                      <td className="py-3 px-4">
                        <span
                          className={`inline-block px-2.5 py-0.5 rounded-full text-[10px] font-semibold border ${badge.class}`}
                        >
                          {badge.label}
                        </span>
                      </td>
                      <td className="py-3 px-4 text-right space-x-1.5 whitespace-nowrap">
                        <a
                          href={`https://wa.me/55${cleanP}?text=${encodeURIComponent(
                            `Olá ${lead.customerName}! Sou da assistência técnica PC Resolve sobre a sua Ordem de Serviço ${lead.protocol} (${lead.serviceType}).`
                          )}`}
                          target="_blank"
                          rel="noopener noreferrer"
                          className="inline-flex items-center justify-center p-1.5 rounded-md bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors"
                          title="Falar no WhatsApp"
                        >
                          <Phone className="w-3.5 h-3.5" />
                        </a>
                        <button
                          onClick={() => openLeadModal(lead)}
                          className="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-md bg-white border border-[#E4E7EC] hover:bg-neutral-50 text-[#202124] font-semibold text-xs transition-colors cursor-pointer"
                        >
                          <Eye className="w-3.5 h-3.5 text-[#D71920]" />
                          <span>Gerenciar</span>
                        </button>
                      </td>
                    </tr>
                  );
                })
              )}
            </tbody>
          </table>
        </div>
      </div>

      {/* Modal: View & Edit Lead Details */}
      {activeModalLead && (
        <div className="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto">
          <div className="bg-white rounded-2xl border border-[#E4E7EC] max-w-2xl w-full p-6 shadow-2xl space-y-6 animate-in fade-in zoom-in-95">
            <div className="flex items-center justify-between pb-3 border-b border-[#E4E7EC]">
              <div className="flex items-center gap-3">
                <span className="font-mono font-bold text-lg text-[#D71920]">
                  {activeModalLead.protocol}
                </span>
                <span className="text-xs text-[#697386]">
                  Criado em: {activeModalLead.createdAt}
                </span>
              </div>
              <button
                onClick={() => setActiveModalLead(null)}
                className="text-[#697386] hover:text-[#202124] p-1 rounded"
              >
                <X className="w-5 h-5" />
              </button>
            </div>

            {/* Customer & Device Info */}
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
              <div className="p-3.5 rounded-xl bg-[#F5F6F8] space-y-1">
                <span className="text-[#697386] font-semibold uppercase text-[10px]">Cliente</span>
                <div className="font-bold text-sm text-[#202124]">{activeModalLead.customerName}</div>
                <div className="text-[#697386]">{activeModalLead.phone}</div>
                <div className="text-[#697386]">{activeModalLead.city}</div>
              </div>

              <div className="p-3.5 rounded-xl bg-[#F5F6F8] space-y-1">
                <span className="text-[#697386] font-semibold uppercase text-[10px]">Equipamento</span>
                <div className="font-bold text-sm text-[#202124] capitalize">{activeModalLead.deviceType}</div>
                <div className="font-semibold text-[#D71920]">{activeModalLead.serviceType}</div>
              </div>
            </div>

            {/* Problem Description */}
            <div className="text-xs space-y-1">
              <span className="text-[#697386] font-semibold uppercase text-[10px]">Relato do Cliente</span>
              <div className="p-3 rounded-xl bg-[#F5F6F8] text-[#202124] leading-relaxed">
                {activeModalLead.description}
              </div>
            </div>

            {/* Status & Budget Form */}
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
              <div>
                <label className="block text-xs font-semibold text-[#202124] mb-1">
                  Status Atual da O.S.
                </label>
                <select
                  value={statusSelect}
                  onChange={(e) => setStatusSelect(e.target.value as LeadStatus)}
                  className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]"
                >
                  <option value="pendente">Pendente (Novo Contato)</option>
                  <option value="em_diagnostico">Em Diagnóstico (Bancada)</option>
                  <option value="aguardando_aprovacao">Aguardando Aprovação do Orçamento</option>
                  <option value="em_execucao">Em Execução de Reparo</option>
                  <option value="concluido">Concluído (Pronto para Retirada)</option>
                  <option value="entregue">Entregue ao Cliente (Garantia Ativa)</option>
                </select>
              </div>

              <div>
                <label className="block text-xs font-semibold text-[#202124] mb-1">
                  Valor do Orçamento (R$)
                </label>
                <input
                  type="number"
                  step="0.01"
                  value={budgetInput}
                  onChange={(e) => setBudgetInput(Number(e.target.value))}
                  placeholder="0.00"
                  className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white text-xs font-mono text-[#202124] focus:outline-none focus:border-[#D71920]"
                />
              </div>
            </div>

            {/* Technical Notes */}
            <div className="text-xs space-y-1">
              <label className="block text-xs font-semibold text-[#202124] mb-1">
                Anotações Técnicas Internas (Bancada ESD)
              </label>
              <textarea
                rows={3}
                value={noteInput}
                onChange={(e) => setNoteInput(e.target.value)}
                placeholder="Ex: Medições realizadas, componentes substituídos, número de série..."
                className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]"
              />
            </div>

            {/* Actions */}
            <div className="pt-3 border-t border-[#E4E7EC] flex flex-wrap items-center justify-between gap-3">
              <div className="flex items-center gap-2">
                <a
                  href={`https://wa.me/55${activeModalLead.phone.replace(/\D/g, '')}?text=${encodeURIComponent(
                    `Olá ${activeModalLead.customerName}! Atualização da OS ${activeModalLead.protocol}: Seu equipamento está com status [${statusSelect.toUpperCase()}]. Valor do serviço: R$ ${budgetInput.toFixed(2)}.`
                  )}`}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="px-3.5 py-2 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs font-semibold transition-colors flex items-center gap-1.5"
                >
                  <Phone className="w-3.5 h-3.5" />
                  <span>Notificar no WhatsApp</span>
                </a>
              </div>

              <div className="flex items-center gap-2">
                <button
                  onClick={() => setActiveModalLead(null)}
                  className="px-4 py-2 rounded-lg border border-[#E4E7EC] text-xs font-semibold text-[#697386] hover:bg-neutral-50 transition-colors"
                >
                  Cancelar
                </button>
                <button
                  onClick={handleSaveModal}
                  className="px-5 py-2 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold transition-colors flex items-center gap-1.5 shadow-xs"
                >
                  <Save className="w-3.5 h-3.5" />
                  <span>Salvar Alterações</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      )}

      {/* Modal: New Lead Manual Registration */}
      {isNewLeadModalOpen && (
        <div className="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto">
          <div className="bg-white rounded-2xl border border-[#E4E7EC] max-w-xl w-full p-6 shadow-2xl space-y-6">
            <div className="flex items-center justify-between pb-3 border-b border-[#E4E7EC]">
              <h3 className="font-heading font-bold text-base text-[#202124]">
                Cadastrar Nova Ordem de Serviço
              </h3>
              <button
                onClick={() => setIsNewLeadModalOpen(false)}
                className="text-[#697386] hover:text-[#202124] p-1 rounded"
              >
                <X className="w-5 h-5" />
              </button>
            </div>

            <form onSubmit={handleCreateNewLead} className="space-y-4">
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div>
                  <label className="block text-xs font-semibold text-[#202124] mb-1">
                    Nome do Cliente *
                  </label>
                  <input
                    type="text"
                    required
                    value={newCustomerName}
                    onChange={(e) => setNewCustomerName(e.target.value)}
                    placeholder="Nome completo"
                    className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] text-xs"
                  />
                </div>
                <div>
                  <label className="block text-xs font-semibold text-[#202124] mb-1">
                    WhatsApp / Telefone *
                  </label>
                  <input
                    type="tel"
                    required
                    value={newPhone}
                    onChange={(e) => setNewPhone(e.target.value)}
                    placeholder="(83) 99999-9999"
                    className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] text-xs"
                  />
                </div>
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div>
                  <label className="block text-xs font-semibold text-[#202124] mb-1">
                    Cidade / Bairro
                  </label>
                  <input
                    type="text"
                    value={newCity}
                    onChange={(e) => setNewCity(e.target.value)}
                    className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] text-xs"
                  />
                </div>
                <div>
                  <label className="block text-xs font-semibold text-[#202124] mb-1">
                    Dispositivo
                  </label>
                  <select
                    value={newDeviceType}
                    onChange={(e) => setNewDeviceType(e.target.value)}
                    className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] text-xs"
                  >
                    <option value="notebook">Notebook</option>
                    <option value="desktop">PC Desktop</option>
                    <option value="all-in-one">All-in-One</option>
                    <option value="macbook">Apple MacBook</option>
                    <option value="corporativo">Corporativo</option>
                  </select>
                </div>
              </div>

              <div className="text-xs">
                <label className="block text-xs font-semibold text-[#202124] mb-1">
                  Serviço Solicitado
                </label>
                <input
                  type="text"
                  value={newServiceType}
                  onChange={(e) => setNewServiceType(e.target.value)}
                  className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] text-xs"
                />
              </div>

              <div className="text-xs">
                <label className="block text-xs font-semibold text-[#202124] mb-1">
                  Defeito Relatado / Observações
                </label>
                <textarea
                  rows={3}
                  value={newDesc}
                  onChange={(e) => setNewDesc(e.target.value)}
                  placeholder="Relato do cliente, itens deixados..."
                  className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] text-xs"
                />
              </div>

              <div className="pt-3 border-t border-[#E4E7EC] flex justify-end gap-2">
                <button
                  type="button"
                  onClick={() => setIsNewLeadModalOpen(false)}
                  className="px-4 py-2 rounded-lg border border-[#E4E7EC] text-xs font-semibold text-[#697386]"
                >
                  Cancelar
                </button>
                <button
                  type="submit"
                  className="px-5 py-2 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold shadow-xs"
                >
                  Criar Ordem de Serviço
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </AdminLayout>
  );
};
