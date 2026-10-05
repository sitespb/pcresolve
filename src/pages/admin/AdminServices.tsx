import React, { useState } from 'react';
import { useApp } from '../../context/AppContext';
import { AdminLayout } from '../../components/admin/AdminLayout';
import { ServiceItem, ServiceCategory } from '../../types';
import {
  Wrench,
  Plus,
  Search,
  CheckCircle2,
  XCircle,
  Edit2,
  Trash2,
  X,
  Save,
  DollarSign,
  Clock,
  ShieldCheck,
} from 'lucide-react';

export const AdminServices: React.FC = () => {
  const { services, addService, updateService, toggleServiceStatus, deleteService, showToast } =
    useApp();

  const [search, setSearch] = useState('');
  const [editingService, setEditingService] = useState<ServiceItem | null>(null);
  const [isNewModalOpen, setIsNewModalOpen] = useState(false);

  // New service state
  const [newTitle, setNewTitle] = useState('');
  const [newShortDesc, setNewShortDesc] = useState('');
  const [newFullDesc, setNewFullDesc] = useState('');
  const [newCategory, setNewCategory] = useState<ServiceCategory>('hardware');
  const [newPrice, setNewPrice] = useState<number>(100);
  const [newTurnaround, setNewTurnaround] = useState('24h a 48h úteis');

  const filteredServices = services.filter(
    (s) =>
      s.title.toLowerCase().includes(search.toLowerCase()) ||
      s.shortDesc.toLowerCase().includes(search.toLowerCase())
  );

  const handleCreateService = (e: React.FormEvent) => {
    e.preventDefault();
    if (!newTitle.trim()) {
      showToast('Título do serviço é obrigatório.', 'warning');
      return;
    }

    addService({
      slug: newTitle
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, ''),
      title: newTitle,
      shortDesc: newShortDesc || newTitle,
      fullDesc: newFullDesc || newShortDesc || newTitle,
      category: newCategory,
      priceStartingAt: Number(newPrice),
      turnaroundTime: newTurnaround,
      warrantyDays: 90,
      iconName: 'Wrench',
      image:
        'https://lh3.googleusercontent.com/aida-public/AB6AXuDL8KGLk9WBdURz4Vfpz1xflvMhnQr7PDMYyZNghKrCt2Nlmy2DObTEtVSRJjsIWN-_gKRyD529_owAQ7szWMEdzUhURy6Wxxlj7m84aNKjYm3h2YkHWXA7oP2ksErYHWSziawRC4pSQNQGCloJdmfgPa_hYL752-f1gV3bgVRysl-qrD8XmiRogStVppbkvcNRiRSVkAac4DM_ohWHRr8P4NBFdQ_ECz4YVRiLbUNOReTNDNtYdn0okg',
      highlights: ['Testes laboratoriais completos', 'Garantia de 90 dias'],
      recommendedFor: ['Manutenção preventiva e corretiva'],
      active: true,
      order: services.length + 1,
    });

    setIsNewModalOpen(false);
    setNewTitle('');
    setNewShortDesc('');
    setNewFullDesc('');
  };

  const handleSaveEdit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!editingService) return;
    updateService(editingService.id, editingService);
    setEditingService(null);
  };

  return (
    <AdminLayout
      title="Catálogo de Serviços"
      subtitle="Gerenciamento dos serviços oferecidos, tabela de preços de entrada e visibilidade no site"
      action={
        <button
          onClick={() => setIsNewModalOpen(true)}
          className="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold shadow-xs transition-colors cursor-pointer"
        >
          <Plus className="w-3.5 h-3.5" />
          <span>Novo Serviço</span>
        </button>
      }
    >
      {/* Search Bar */}
      <div className="bg-white rounded-2xl border border-[#E4E7EC] p-4 shadow-2xs">
        <div className="relative max-w-md">
          <Search className="w-4 h-4 text-[#697386] absolute left-3.5 top-1/2 -translate-y-1/2" />
          <input
            type="text"
            placeholder="Pesquisar serviços cadastrados..."
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            className="w-full pl-9 pr-3.5 py-2 text-xs rounded-xl border border-[#E4E7EC] bg-white text-[#202124] focus:outline-none focus:border-[#D71920]"
          />
        </div>
      </div>

      {/* Services Table */}
      <div className="bg-white rounded-2xl border border-[#E4E7EC] shadow-2xs overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left text-xs">
            <thead>
              <tr className="bg-[#F5F6F8] border-b border-[#E4E7EC] text-[#697386] uppercase tracking-wider text-[10px]">
                <th className="py-3 px-4 font-bold">Serviço</th>
                <th className="py-3 px-4 font-bold">Categoria</th>
                <th className="py-3 px-4 font-bold">Preço de Entrada</th>
                <th className="py-3 px-4 font-bold">Prazo Estimado</th>
                <th className="py-3 px-4 font-bold">Garantia</th>
                <th className="py-3 px-4 font-bold">Status</th>
                <th className="py-3 px-4 font-bold text-right">Ações</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-[#E4E7EC]">
              {filteredServices.map((service) => (
                <tr key={service.id} className="hover:bg-[#F5F6F8]/60 transition-colors">
                  <td className="py-3.5 px-4">
                    <div className="flex items-center gap-3">
                      <img
                        src={service.image}
                        alt={service.title}
                        className="w-10 h-10 rounded-lg object-cover border border-[#E4E7EC]"
                      />
                      <div>
                        <div className="font-bold text-[#202124]">{service.title}</div>
                        <div className="text-[11px] text-[#697386] truncate max-w-[260px]">
                          {service.shortDesc}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td className="py-3.5 px-4 capitalize font-semibold text-[#697386]">
                    {service.category}
                  </td>
                  <td className="py-3.5 px-4 font-mono font-bold text-[#202124]">
                    R$ {service.priceStartingAt.toFixed(2)}
                  </td>
                  <td className="py-3.5 px-4 text-[#697386]">{service.turnaroundTime}</td>
                  <td className="py-3.5 px-4 text-[#202124]">{service.warrantyDays} dias</td>
                  <td className="py-3.5 px-4">
                    <button
                      onClick={() => toggleServiceStatus(service.id)}
                      className={`inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold border cursor-pointer transition-colors ${
                        service.active
                          ? 'bg-emerald-50 text-emerald-800 border-emerald-200'
                          : 'bg-neutral-100 text-neutral-600 border-neutral-300'
                      }`}
                    >
                      {service.active ? (
                        <>
                          <CheckCircle2 className="w-3 h-3 text-emerald-600" />
                          <span>Ativo</span>
                        </>
                      ) : (
                        <>
                          <XCircle className="w-3 h-3 text-neutral-500" />
                          <span>Pausado</span>
                        </>
                      )}
                    </button>
                  </td>
                  <td className="py-3.5 px-4 text-right space-x-2">
                    <button
                      onClick={() => setEditingService(service)}
                      className="p-1.5 rounded-lg border border-[#E4E7EC] hover:bg-neutral-100 text-[#202124] transition-colors"
                      title="Editar Serviço"
                    >
                      <Edit2 className="w-3.5 h-3.5" />
                    </button>
                    <button
                      onClick={() => {
                        if (confirm(`Remover o serviço "${service.title}"?`)) {
                          deleteService(service.id);
                        }
                      }}
                      className="p-1.5 rounded-lg border border-[#E4E7EC] hover:bg-red-50 text-red-600 transition-colors"
                      title="Excluir Serviço"
                    >
                      <Trash2 className="w-3.5 h-3.5" />
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {/* Modal: Edit Service */}
      {editingService && (
        <div className="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto">
          <div className="bg-white rounded-2xl border border-[#E4E7EC] max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div className="flex items-center justify-between pb-3 border-b border-[#E4E7EC]">
              <h3 className="font-heading font-bold text-base text-[#202124]">
                Editar Serviço
              </h3>
              <button
                onClick={() => setEditingService(null)}
                className="text-[#697386] hover:text-[#202124]"
              >
                <X className="w-5 h-5" />
              </button>
            </div>

            <form onSubmit={handleSaveEdit} className="space-y-3.5 text-xs">
              <div>
                <label className="block text-xs font-semibold text-[#202124] mb-1">
                  Título do Serviço
                </label>
                <input
                  type="text"
                  required
                  value={editingService.title}
                  onChange={(e) => setEditingService({ ...editingService, title: e.target.value })}
                  className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]"
                />
              </div>

              <div>
                <label className="block text-xs font-semibold text-[#202124] mb-1">
                  Descrição Curta (Exibida no Card)
                </label>
                <textarea
                  rows={2}
                  value={editingService.shortDesc}
                  onChange={(e) => setEditingService({ ...editingService, shortDesc: e.target.value })}
                  className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]"
                />
              </div>

              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="block text-xs font-semibold text-[#202124] mb-1">
                    Preço Inicial (R$)
                  </label>
                  <input
                    type="number"
                    value={editingService.priceStartingAt}
                    onChange={(e) =>
                      setEditingService({
                        ...editingService,
                        priceStartingAt: Number(e.target.value),
                      })
                    }
                    className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]"
                  />
                </div>

                <div>
                  <label className="block text-xs font-semibold text-[#202124] mb-1">
                    Prazo Médio
                  </label>
                  <input
                    type="text"
                    value={editingService.turnaroundTime}
                    onChange={(e) =>
                      setEditingService({ ...editingService, turnaroundTime: e.target.value })
                    }
                    className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]"
                  />
                </div>
              </div>

              <div className="pt-3 border-t border-[#E4E7EC] flex justify-end gap-2">
                <button
                  type="button"
                  onClick={() => setEditingService(null)}
                  className="px-4 py-2 rounded-lg border border-[#E4E7EC] text-xs font-semibold text-[#697386]"
                >
                  Cancelar
                </button>
                <button
                  type="submit"
                  className="px-5 py-2 rounded-lg bg-[#D71920] text-white text-xs font-semibold shadow-xs"
                >
                  Salvar
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* Modal: Create Service */}
      {isNewModalOpen && (
        <div className="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto">
          <div className="bg-white rounded-2xl border border-[#E4E7EC] max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div className="flex items-center justify-between pb-3 border-b border-[#E4E7EC]">
              <h3 className="font-heading font-bold text-base text-[#202124]">
                Adicionar Novo Serviço
              </h3>
              <button
                onClick={() => setIsNewModalOpen(false)}
                className="text-[#697386] hover:text-[#202124]"
              >
                <X className="w-5 h-5" />
              </button>
            </div>

            <form onSubmit={handleCreateService} className="space-y-3.5 text-xs">
              <div>
                <label className="block text-xs font-semibold text-[#202124] mb-1">
                  Nome do Serviço *
                </label>
                <input
                  type="text"
                  required
                  placeholder="Ex: Troca de Bateria para MacBook"
                  value={newTitle}
                  onChange={(e) => setNewTitle(e.target.value)}
                  className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]"
                />
              </div>

              <div>
                <label className="block text-xs font-semibold text-[#202124] mb-1">
                  Categoria
                </label>
                <select
                  value={newCategory}
                  onChange={(e) => setNewCategory(e.target.value as ServiceCategory)}
                  className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]"
                >
                  <option value="hardware">Hardware</option>
                  <option value="software">Software</option>
                  <option value="preventiva">Preventiva</option>
                  <option value="corporativo">Corporativo</option>
                </select>
              </div>

              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="block text-xs font-semibold text-[#202124] mb-1">
                    Preço Inicial (R$)
                  </label>
                  <input
                    type="number"
                    value={newPrice}
                    onChange={(e) => setNewPrice(Number(e.target.value))}
                    className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]"
                  />
                </div>
                <div>
                  <label className="block text-xs font-semibold text-[#202124] mb-1">
                    Prazo Médio
                  </label>
                  <input
                    type="text"
                    value={newTurnaround}
                    onChange={(e) => setNewTurnaround(e.target.value)}
                    className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]"
                  />
                </div>
              </div>

              <div>
                <label className="block text-xs font-semibold text-[#202124] mb-1">
                  Descrição Curta
                </label>
                <textarea
                  rows={2}
                  placeholder="Breve resumo para o catálogo..."
                  value={newShortDesc}
                  onChange={(e) => setNewShortDesc(e.target.value)}
                  className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC]"
                />
              </div>

              <div className="pt-3 border-t border-[#E4E7EC] flex justify-end gap-2">
                <button
                  type="button"
                  onClick={() => setIsNewModalOpen(false)}
                  className="px-4 py-2 rounded-lg border border-[#E4E7EC] text-xs font-semibold text-[#697386]"
                >
                  Cancelar
                </button>
                <button
                  type="submit"
                  className="px-5 py-2 rounded-lg bg-[#D71920] text-white text-xs font-semibold shadow-xs"
                >
                  Adicionar Serviço
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </AdminLayout>
  );
};
