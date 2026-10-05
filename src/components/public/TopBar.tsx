import React from 'react';
import { useApp } from '../../context/AppContext';
import { Phone, Clock, MapPin, LayoutDashboard } from 'lucide-react';

export const TopBar: React.FC = () => {
  const { companySettings, navigateTo } = useApp();

  return (
    <aside className="bg-[#F5F6F8] border-b border-[#E4E7EC] text-xs text-[#697386] py-2 px-4 sm:px-8">
      <div className="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-3">
        <div className="flex items-center gap-6">
          <a
            href={`tel:${companySettings.phone.replace(/\D/g, '')}`}
            className="inline-flex items-center gap-1.5 hover:text-[#D71920] transition-colors"
          >
            <Phone className="w-3.5 h-3.5 text-[#D71920]" />
            <span className="font-semibold text-[#202124]">{companySettings.phone}</span>
          </a>
          <span className="hidden md:inline-flex items-center gap-1.5">
            <Clock className="w-3.5 h-3.5 text-[#697386]" />
            <span>{companySettings.workingHoursWeekday} | {companySettings.workingHoursSaturday}</span>
          </span>
        </div>

        <div className="flex items-center gap-4">
          <div className="flex items-center gap-1.5">
            <MapPin className="w-3.5 h-3.5 text-[#D71920]" />
            <span>João Pessoa - PB • Grande João Pessoa</span>
          </div>

          <div className="h-3.5 w-px bg-[#E4E7EC] hidden sm:block"></div>

          <button
            onClick={() => navigateTo('admin-dashboard')}
            className="inline-flex items-center gap-1.5 text-xs font-semibold text-[#202124] hover:text-[#D71920] bg-white hover:bg-neutral-50 px-2.5 py-1 rounded-md border border-[#E4E7EC] transition-all shadow-2xs"
            title="Acessar o painel administrativo, estatísticas e configurações"
          >
            <LayoutDashboard className="w-3.5 h-3.5 text-[#D71920]" />
            <span>Painel Administrativo</span>
          </button>
        </div>
      </div>
    </aside>
  );
};
