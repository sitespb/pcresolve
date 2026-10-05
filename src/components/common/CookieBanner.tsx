import React from 'react';
import { useApp } from '../../context/AppContext';
import { ShieldCheck } from 'lucide-react';

export const CookieBanner: React.FC = () => {
  const { cookieAccepted, acceptCookies, navigateTo } = useApp();

  if (cookieAccepted) return null;

  return (
    <div className="fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-[#E4E7EC] p-4 sm:p-5 shadow-2xl">
      <div className="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
        <div className="flex items-start sm:items-center gap-3">
          <div className="w-9 h-9 rounded-lg bg-[#F5F6F8] border border-[#E4E7EC] flex items-center justify-center text-[#D71920] shrink-0">
            <ShieldCheck className="w-5 h-5" />
          </div>
          <p className="text-xs text-[#697386] leading-relaxed">
            Utilizamos cookies essenciais e tecnologias de medição para otimizar sua experiência de navegação e aprimorar nossos atendimentos na Grande João Pessoa, em conformidade com a LGPD (Lei Geral de Proteção de Dados).{' '}
            <button
              onClick={() => navigateTo('privacidade')}
              className="text-[#D71920] hover:underline font-semibold"
            >
              Conheça nossa Política de Privacidade
            </button>
            .
          </p>
        </div>

        <div className="flex items-center gap-3 shrink-0 w-full sm:w-auto">
          <button
            onClick={acceptCookies}
            className="w-full sm:w-auto px-5 py-2 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold transition-colors shadow-xs"
          >
            Aceitar e Continuar
          </button>
        </div>
      </div>
    </div>
  );
};
