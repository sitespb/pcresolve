import React, { useState } from 'react';
import { useApp } from '../context/AppContext';
import { ServiceCategory } from '../types';
import {
  Laptop,
  Cpu,
  Zap,
  Activity,
  Terminal,
  Shield,
  Fan,
  Building,
  ArrowRight,
  Clock,
  ShieldCheck,
  Search,
} from 'lucide-react';

export const ServicesPage: React.FC = () => {
  const { services, companySettings, navigateTo } = useApp();
  const [selectedCategory, setSelectedCategory] = useState<ServiceCategory>('todos');
  const [searchQuery, setSearchQuery] = useState('');

  const categories: { key: ServiceCategory; label: string }[] = [
    { key: 'todos', label: 'Todos os Serviços' },
    { key: 'hardware', label: 'Hardware & Placas' },
    { key: 'software', label: 'Sistemas & Segurança' },
    { key: 'preventiva', label: 'Limpeza & Térmica' },
    { key: 'corporativo', label: 'Empresarial & PJ' },
  ];

  const filteredServices = services.filter((s) => {
    if (!s.active) return false;
    const matchesCategory = selectedCategory === 'todos' || s.category === selectedCategory;
    const matchesSearch =
      s.title.toLowerCase().includes(searchQuery.toLowerCase()) ||
      s.shortDesc.toLowerCase().includes(searchQuery.toLowerCase());
    return matchesCategory && matchesSearch;
  });

  const getServiceIcon = (iconName: string) => {
    switch (iconName) {
      case 'Laptop':
        return <Laptop className="w-5 h-5 text-[#D71920]" />;
      case 'Cpu':
        return <Cpu className="w-5 h-5 text-[#D71920]" />;
      case 'Zap':
        return <Zap className="w-5 h-5 text-[#D71920]" />;
      case 'Activity':
        return <Activity className="w-5 h-5 text-[#D71920]" />;
      case 'Terminal':
        return <Terminal className="w-5 h-5 text-[#D71920]" />;
      case 'Shield':
        return <Shield className="w-5 h-5 text-[#D71920]" />;
      case 'Fan':
        return <Fan className="w-5 h-5 text-[#D71920]" />;
      case 'Building':
        return <Building className="w-5 h-5 text-[#D71920]" />;
      default:
        return <Cpu className="w-5 h-5 text-[#D71920]" />;
    }
  };

  const cleanPhone = companySettings.whatsapp.replace(/\D/g, '');

  return (
    <div className="bg-white py-12 lg:py-16">
      <div className="max-w-7xl mx-auto px-4 sm:px-8">
        {/* Page Header */}
        <div className="max-w-3xl mb-12">
          <div className="inline-flex items-center gap-2 text-xs font-semibold text-[#D71920] mb-2 uppercase tracking-wider">
            <span>Especialidades Técnicas</span>
          </div>
          <h1 className="font-heading font-extrabold text-3xl sm:text-4xl text-[#202124] tracking-tight">
            Catálogo Completo de Serviços de Informática
          </h1>
          <p className="text-sm sm:text-base text-[#697386] mt-3 leading-relaxed">
            Manutenção preventiva e corretiva para computadores e notebooks com equipamentos calibrados, bancada anti-estática ESD e garantia formal de 90 dias.
          </p>
        </div>

        {/* Filter and Search Bar */}
        <div className="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4 pb-8 mb-8 border-b border-[#E4E7EC]">
          {/* Category Tabs */}
          <div className="flex items-center gap-1.5 p-1 bg-[#F5F6F8] rounded-xl border border-[#E4E7EC] overflow-x-auto">
            {categories.map((cat) => (
              <button
                key={cat.key}
                onClick={() => setSelectedCategory(cat.key)}
                className={`px-3.5 py-2 text-xs font-semibold rounded-lg transition-colors whitespace-nowrap cursor-pointer ${
                  selectedCategory === cat.key
                    ? 'bg-white text-[#D71920] shadow-xs'
                    : 'text-[#697386] hover:text-[#202124]'
                }`}
              >
                {cat.label}
              </button>
            ))}
          </div>

          {/* Search Input */}
          <div className="relative min-w-[260px]">
            <Search className="w-4 h-4 text-[#697386] absolute left-3.5 top-1/2 -translate-y-1/2" />
            <input
              type="text"
              placeholder="Buscar serviço (ex: SSD, tela, placa)..."
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
              className="w-full pl-9 pr-3.5 py-2 text-xs rounded-xl border border-[#E4E7EC] bg-white text-[#202124] focus:outline-none focus:border-[#D71920]"
            />
          </div>
        </div>

        {/* Services Grid */}
        {filteredServices.length === 0 ? (
          <div className="text-center py-16 bg-[#F5F6F8] rounded-2xl border border-[#E4E7EC]">
            <p className="text-sm font-semibold text-[#202124]">Nenhum serviço encontrado com esse termo.</p>
            <p className="text-xs text-[#697386] mt-1">Tente pesquisar por outra palavra-chave ou limpe os filtros.</p>
            <button
              onClick={() => {
                setSelectedCategory('todos');
                setSearchQuery('');
              }}
              className="mt-4 px-4 py-2 rounded-lg bg-white border border-[#E4E7EC] text-xs font-semibold text-[#D71920] hover:bg-neutral-50 transition-colors"
            >
              Limpar filtros
            </button>
          </div>
        ) : (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {filteredServices.map((service) => (
              <div
                key={service.id}
                className="bg-[#F5F6F8] border border-[#E4E7EC] rounded-2xl overflow-hidden flex flex-col justify-between hover:border-[#D71920]/40 hover:shadow-md transition-all group"
              >
                <div>
                  <div className="h-48 w-full overflow-hidden bg-neutral-200 relative">
                    <img
                      src={service.image}
                      alt={service.title}
                      className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                    />
                    <div className="absolute top-3 left-3 w-8 h-8 rounded-lg bg-white/95 backdrop-blur-xs flex items-center justify-center shadow-xs">
                      {getServiceIcon(service.iconName)}
                    </div>
                    <div className="absolute bottom-3 right-3 px-2.5 py-1 rounded-md bg-white/95 text-[11px] font-bold text-[#202124] shadow-xs">
                      A partir de R$ {service.priceStartingAt}
                    </div>
                  </div>

                  <div className="p-6">
                    <h2 className="font-heading font-bold text-base text-[#202124] mb-2 leading-snug">
                      {service.title}
                    </h2>
                    <p className="text-xs text-[#697386] leading-relaxed mb-4">
                      {service.shortDesc}
                    </p>

                    <div className="space-y-1.5 pt-2 border-t border-[#E4E7EC]/60 text-[11px] text-[#697386]">
                      <div className="flex items-center gap-2">
                        <Clock className="w-3.5 h-3.5 text-[#D71920]" />
                        <span>Prazo médio: <strong className="text-[#202124]">{service.turnaroundTime}</strong></span>
                      </div>
                      <div className="flex items-center gap-2">
                        <ShieldCheck className="w-3.5 h-3.5 text-[#25D366]" />
                        <span>Garantia: <strong className="text-[#202124]">{service.warrantyDays} dias</strong></span>
                      </div>
                    </div>
                  </div>
                </div>

                <div className="p-6 pt-0 flex items-center justify-between gap-3">
                  <button
                    onClick={() => navigateTo('servico-detalhe', service.slug)}
                    className="flex-1 py-2.5 px-3 rounded-lg bg-white border border-[#E4E7EC] hover:border-[#D71920] hover:text-[#D71920] text-xs font-semibold text-[#202124] transition-colors flex items-center justify-center gap-1.5 cursor-pointer"
                  >
                    <span>Ver detalhes</span>
                    <ArrowRight className="w-3.5 h-3.5" />
                  </button>

                  <a
                    href={`https://wa.me/55${cleanPhone}?text=${encodeURIComponent(
                      `Olá! Gostaria de solicitar um orçamento para o serviço: ${service.title}`
                    )}`}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="py-2.5 px-4 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold transition-colors flex items-center justify-center gap-1.5"
                  >
                    <span>Orçar</span>
                  </a>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  );
};
