import React, { useState } from 'react';
import { useApp, AppRoute } from '../../context/AppContext';
import { Logo } from '../common/Logo';
import { Menu, X, ArrowRight, ShieldCheck, BarChart3 } from 'lucide-react';

export const Header: React.FC = () => {
  const { currentRoute, navigateTo } = useApp();
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  const navLinks: { label: string; route: AppRoute; href?: string }[] = [
    { label: 'Início', route: 'home' },
    { label: 'Serviços', route: 'servicos' },
    { label: 'Sobre', route: 'sobre' },
    { label: 'Cidades Atendidas', route: 'areas' },
    { label: 'Contato', route: 'contato' },
  ];

  const handleNavClick = (route: AppRoute) => {
    navigateTo(route);
    setMobileMenuOpen(false);
  };

  return (
    <header className="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-[#E4E7EC] transition-all">
      <div className="max-w-7xl mx-auto px-4 sm:px-8 h-20 flex items-center justify-between gap-4">
        {/* Zone 1: Brand Wordmark */}
        <button
          onClick={() => handleNavClick('home')}
          className="text-left focus:outline-none cursor-pointer"
          aria-label="Ir para a página inicial da PC Resolve"
        >
          <Logo />
        </button>

        {/* Zone 2: Navigation Links */}
        <nav className="hidden lg:flex items-center gap-7 font-medium text-sm text-[#202124]">
          {navLinks.map((link) => {
            const isActive = currentRoute === link.route;
            return (
              <button
                key={link.route}
                onClick={() => handleNavClick(link.route)}
                className={`transition-colors py-1 relative cursor-pointer ${
                  isActive
                    ? 'text-[#D71920] font-bold'
                    : 'text-[#202124] hover:text-[#D71920]'
                }`}
              >
                {link.label}
                {isActive && (
                  <span className="absolute bottom-0 left-0 right-0 h-0.5 bg-[#D71920] rounded-full" />
                )}
              </button>
            );
          })}
        </nav>

        {/* Zone 3: Primary Actions */}
        <div className="flex items-center gap-2.5 sm:gap-3">
          {/* Quick link to Admin Panel */}
          <button
            onClick={() => handleNavClick('admin-dashboard')}
            className="hidden md:inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-lg border border-[#E4E7EC] hover:border-[#D71920]/40 bg-[#F5F6F8] hover:bg-white text-xs font-semibold text-[#202124] transition-all cursor-pointer"
            title="Abrir Painel Administrativo"
          >
            <BarChart3 className="w-3.5 h-3.5 text-[#D71920]" />
            <span>Admin</span>
          </button>

          {/* Primary CTA button */}
          <button
            onClick={() => handleNavClick('contato')}
            className="inline-flex items-center justify-center gap-2 px-4 sm:px-5 py-2.5 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white font-semibold text-xs sm:text-sm transition-all shadow-xs hover:shadow-md cursor-pointer whitespace-nowrap"
          >
            <span>Solicitar atendimento</span>
            <ArrowRight className="w-4 h-4 hidden sm:inline" />
          </button>

          {/* Mobile hamburger */}
          <button
            onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
            className="lg:hidden p-2 rounded-lg text-[#202124] hover:bg-[#F5F6F8] border border-[#E4E7EC]"
            aria-label="Abrir menu de navegação"
          >
            {mobileMenuOpen ? <X className="w-5 h-5" /> : <Menu className="w-5 h-5" />}
          </button>
        </div>
      </div>

      {/* Mobile Drawer Menu */}
      {mobileMenuOpen && (
        <div className="lg:hidden border-t border-[#E4E7EC] bg-white px-4 py-5 shadow-xl animate-in slide-in-from-top-2">
          <div className="flex flex-col space-y-3 font-medium text-sm">
            {navLinks.map((link) => (
              <button
                key={link.route}
                onClick={() => handleNavClick(link.route)}
                className={`text-left px-3 py-2 rounded-lg transition-colors ${
                  currentRoute === link.route
                    ? 'bg-[#F5F6F8] text-[#D71920] font-bold'
                    : 'text-[#202124] hover:bg-neutral-50'
                }`}
              >
                {link.label}
              </button>
            ))}

            <div className="pt-3 border-t border-[#E4E7EC] flex flex-col gap-2">
              <button
                onClick={() => handleNavClick('admin-dashboard')}
                className="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg border border-[#E4E7EC] bg-[#F5F6F8] text-xs font-bold text-[#202124]"
              >
                <BarChart3 className="w-4 h-4 text-[#D71920]" />
                <span>Painel Administrativo &amp; Analytics</span>
              </button>
              <button
                onClick={() => handleNavClick('contato')}
                className="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-[#D71920] text-white text-xs font-bold shadow-xs"
              >
                <span>Solicitar Orçamento Online</span>
              </button>
            </div>
          </div>
        </div>
      )}
    </header>
  );
};
