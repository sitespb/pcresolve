import React, { useState } from 'react';
import { useApp, AppRoute } from '../../context/AppContext';
import { Logo } from '../common/Logo';
import {
  LayoutDashboard,
  BarChart3,
  ClipboardList,
  Wrench,
  MapPin,
  MessageSquare,
  HelpCircle,
  User,
  Settings,
  Globe,
  Bell,
  Search,
  LogOut,
  Menu,
  X,
  ExternalLink,
  ShieldCheck,
  RefreshCw,
} from 'lucide-react';

interface AdminLayoutProps {
  children: React.ReactNode;
  title: string;
  subtitle?: string;
  action?: React.ReactNode;
}

export const AdminLayout: React.FC<AdminLayoutProps> = ({
  children,
  title,
  subtitle,
  action,
}) => {
  const { currentRoute, navigateTo, userProfile, leads, resetAllData, showToast } = useApp();
  const [sidebarOpen, setSidebarOpen] = useState(false);

  const pendingLeadsCount = leads.filter((l) => l.status === 'pendente').length;

  const menuItems: { route: AppRoute; label: string; icon: React.ReactNode; badge?: number }[] = [
    {
      route: 'admin-dashboard',
      label: 'Visão Geral (Dashboard)',
      icon: <LayoutDashboard className="w-4 h-4" />,
    },
    {
      route: 'admin-analytics',
      label: 'Relatórios & Estatísticas',
      icon: <BarChart3 className="w-4 h-4" />,
    },
    {
      route: 'admin-solicitacoes',
      label: 'Ordens de Serviço & Leads',
      icon: <ClipboardList className="w-4 h-4" />,
      badge: pendingLeadsCount > 0 ? pendingLeadsCount : undefined,
    },
    {
      route: 'admin-servicos',
      label: 'Catálogo de Serviços',
      icon: <Wrench className="w-4 h-4" />,
    },
    {
      route: 'admin-cidades',
      label: 'Cidades & Leva e Traz',
      icon: <MapPin className="w-4 h-4" />,
    },
    {
      route: 'admin-depoimentos',
      label: 'Depoimentos & Avaliações',
      icon: <MessageSquare className="w-4 h-4" />,
    },
    {
      route: 'admin-faq',
      label: 'Perguntas Frequentes (FAQ)',
      icon: <HelpCircle className="w-4 h-4" />,
    },
    {
      route: 'admin-perfil',
      label: 'Perfil do Usuário',
      icon: <User className="w-4 h-4" />,
    },
    {
      route: 'admin-configuracoes',
      label: 'Configurações do Sistema',
      icon: <Settings className="w-4 h-4" />,
    },
  ];

  return (
    <div className="min-h-screen bg-[#F5F6F8] flex flex-col md:flex-row text-[#202124]">
      {/* Mobile Header Bar */}
      <div className="md:hidden bg-white border-b border-[#E4E7EC] px-4 py-3 flex items-center justify-between z-30">
        <Logo size="sm" />
        <div className="flex items-center gap-2">
          <button
            onClick={() => navigateTo('home')}
            className="p-2 text-[#697386] hover:text-[#D71920]"
            title="Ver Site Público"
          >
            <Globe className="w-5 h-5" />
          </button>
          <button
            onClick={() => setSidebarOpen(!sidebarOpen)}
            className="p-2 text-[#202124] rounded-lg border border-[#E4E7EC]"
            aria-label="Abrir menu lateral"
          >
            {sidebarOpen ? <X className="w-5 h-5" /> : <Menu className="w-5 h-5" />}
          </button>
        </div>
      </div>

      {/* Sidebar Navigation */}
      <aside
        className={`fixed md:sticky top-0 inset-y-0 left-0 z-40 w-64 bg-white border-r border-[#E4E7EC] flex flex-col justify-between transition-transform duration-300 md:translate-x-0 ${
          sidebarOpen ? 'translate-x-0 shadow-2xl' : '-translate-x-full md:translate-x-0'
        }`}
      >
        <div className="flex flex-col flex-1 overflow-y-auto">
          {/* Logo & App title */}
          <div className="p-5 border-b border-[#E4E7EC]">
            <Logo size="sm" />
            <div className="mt-2.5 flex items-center gap-2 text-[10px] font-bold uppercase tracking-wider text-[#697386]">
              <span className="w-2 h-2 rounded-full bg-emerald-500"></span>
              <span>Painel de Gestão Técnica</span>
            </div>
          </div>

          {/* Navigation Links */}
          <div className="p-3 space-y-1">
            <span className="text-[10px] font-bold text-[#697386] uppercase tracking-wider px-3 py-1.5 block">
              Menu Administrativo
            </span>
            {menuItems.map((item) => {
              const active = currentRoute === item.route;
              return (
                <button
                  key={item.route}
                  onClick={() => {
                    navigateTo(item.route);
                    setSidebarOpen(false);
                  }}
                  className={`w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition-all cursor-pointer ${
                    active
                      ? 'bg-[#D71920] text-white shadow-xs'
                      : 'text-[#202124] hover:bg-[#F5F6F8] hover:text-[#D71920]'
                  }`}
                >
                  <div className="flex items-center gap-2.5">
                    <span className={active ? 'text-white' : 'text-[#697386]'}>{item.icon}</span>
                    <span>{item.label}</span>
                  </div>
                  {item.badge !== undefined && (
                    <span
                      className={`px-1.5 py-0.2 rounded-full text-[10px] font-bold ${
                        active ? 'bg-white text-[#D71920]' : 'bg-[#D71920] text-white'
                      }`}
                    >
                      {item.badge}
                    </span>
                  )}
                </button>
              );
            })}
          </div>

          {/* Public Site Quick Jump */}
          <div className="p-3 pt-2">
            <button
              onClick={() => navigateTo('home')}
              className="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-[#697386] hover:text-[#202124] hover:bg-[#F5F6F8] border border-dashed border-[#E4E7EC] transition-colors cursor-pointer"
            >
              <Globe className="w-4 h-4 text-[#D71920]" />
              <span>Ver Site Público</span>
              <ExternalLink className="w-3 h-3 ml-auto text-neutral-400" />
            </button>
          </div>
        </div>

        {/* User Footer Profile Card */}
        <div className="p-4 border-t border-[#E4E7EC] bg-white">
          <div className="flex items-center gap-3">
            <div className="w-9 h-9 rounded-full bg-neutral-200 overflow-hidden border border-[#E4E7EC] shrink-0">
              <img
                src={userProfile.avatar}
                alt={userProfile.name}
                className="w-full h-full object-cover"
              />
            </div>
            <div className="flex flex-col min-w-0 flex-1">
              <span className="text-xs font-bold text-[#202124] truncate">
                {userProfile.name}
              </span>
              <span className="text-[10px] text-[#697386] truncate">
                {userProfile.role === 'superadmin' ? 'Superadministrador' : 'Técnico'}
              </span>
            </div>
            <button
              onClick={() => navigateTo('admin-perfil')}
              className="text-[#697386] hover:text-[#D71920] p-1 rounded transition-colors"
              title="Acessar Perfil"
            >
              <Settings className="w-4 h-4" />
            </button>
          </div>
        </div>
      </aside>

      {/* Main Content Area */}
      <main className="flex-1 flex flex-col min-w-0">
        {/* Top Header */}
        <header className="h-16 bg-white border-b border-[#E4E7EC] px-6 flex items-center justify-between gap-4 sticky top-0 z-20">
          <div className="flex items-center gap-3 min-w-0">
            <div>
              <h1 className="font-heading font-extrabold text-base sm:text-lg text-[#202124] tracking-tight truncate">
                {title}
              </h1>
              {subtitle && (
                <p className="text-[11px] text-[#697386] hidden sm:block truncate">
                  {subtitle}
                </p>
              )}
            </div>
          </div>

          <div className="flex items-center gap-3 shrink-0">
            {action}

            <button
              onClick={() => {
                showToast('Nenhuma notificação crítica no momento.', 'info');
              }}
              className="relative p-2 rounded-lg border border-[#E4E7EC] text-[#697386] hover:text-[#202124] hover:bg-[#F5F6F8] transition-colors"
              title="Notificações"
            >
              <Bell className="w-4 h-4" />
              {pendingLeadsCount > 0 && (
                <span className="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-[#D71920] text-white text-[9px] font-bold flex items-center justify-center">
                  {pendingLeadsCount}
                </span>
              )}
            </button>

            <button
              onClick={() => navigateTo('home')}
              className="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-[#E4E7EC] bg-[#F5F6F8] hover:bg-neutral-100 text-xs font-semibold text-[#202124] transition-colors"
            >
              <Globe className="w-3.5 h-3.5 text-[#D71920]" />
              <span>Abrir Site</span>
            </button>
          </div>
        </header>

        {/* Content Viewport */}
        <div className="p-4 sm:p-6 lg:p-8 space-y-6 flex-1">
          {children}
        </div>
      </main>
    </div>
  );
};
