import React from 'react';
import { useApp } from '../../context/AppContext';
import { Logo } from '../common/Logo';
import { Phone, Clock, MapPin, Mail, ShieldCheck, Lock } from 'lucide-react';

export const Footer: React.FC = () => {
  const { companySettings, navigateTo } = useApp();

  return (
    <footer className="bg-[#F5F6F8] border-t border-[#E4E7EC] text-[#202124] pt-16 pb-12">
      <div className="max-w-7xl mx-auto px-4 sm:px-8">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
          {/* Column 1: Brand & Bio */}
          <div className="space-y-4">
            <button
              onClick={() => navigateTo('home')}
              className="text-left focus:outline-none cursor-pointer"
            >
              <Logo size="sm" />
            </button>
            <p className="text-xs text-[#697386] leading-relaxed">
              {companySettings.aboutText}
            </p>
            <div className="flex items-center gap-2 pt-2 text-[11px] text-[#697386] font-medium">
              <ShieldCheck className="w-4 h-4 text-[#25D366]" />
              <span>Garantia de 90 dias conforme CDC art. 26</span>
            </div>
          </div>

          {/* Column 2: Serviços */}
          <div>
            <h4 className="font-heading font-bold text-xs uppercase tracking-wider text-[#202124] mb-3">
              Principais Serviços
            </h4>
            <ul className="space-y-2 text-xs text-[#697386]">
              <li>
                <button
                  onClick={() => navigateTo('servico-detalhe', 'manutencao-notebooks')}
                  className="hover:text-[#D71920] transition-colors text-left"
                >
                  Manutenção de Notebooks
                </button>
              </li>
              <li>
                <button
                  onClick={() => navigateTo('servico-detalhe', 'manutencao-computadores')}
                  className="hover:text-[#D71920] transition-colors text-left"
                >
                  Manutenção de Desktops e PCs
                </button>
              </li>
              <li>
                <button
                  onClick={() => navigateTo('servico-detalhe', 'upgrade-ssd-memoria')}
                  className="hover:text-[#D71920] transition-colors text-left"
                >
                  Upgrade de SSD NVMe e Memória RAM
                </button>
              </li>
              <li>
                <button
                  onClick={() => navigateTo('servico-detalhe', 'diagnostico-reparo-hardware')}
                  className="hover:text-[#D71920] transition-colors text-left"
                >
                  Reparo de Placa-mãe em Bancada
                </button>
              </li>
              <li>
                <button
                  onClick={() => navigateTo('servico-detalhe', 'limpeza-preventiva-termica')}
                  className="hover:text-[#D71920] transition-colors text-left"
                >
                  Limpeza Preventiva e Pasta Térmica
                </button>
              </li>
              <li>
                <button
                  onClick={() => navigateTo('servicos')}
                  className="text-[#D71920] font-semibold hover:underline mt-1 inline-block"
                >
                  Ver todos os serviços &rarr;
                </button>
              </li>
            </ul>
          </div>

          {/* Column 3: Áreas Atendidas */}
          <div>
            <h4 className="font-heading font-bold text-xs uppercase tracking-wider text-[#202124] mb-3">
              Áreas Atendidas (Grande JP)
            </h4>
            <ul className="space-y-2 text-xs text-[#697386]">
              <li>
                <button onClick={() => navigateTo('areas')} className="hover:text-[#D71920] transition-colors text-left">
                  João Pessoa (Todos os bairros)
                </button>
              </li>
              <li>
                <button onClick={() => navigateTo('areas')} className="hover:text-[#D71920] transition-colors text-left">
                  Cabedelo (Intermares e Centro)
                </button>
              </li>
              <li>
                <button onClick={() => navigateTo('areas')} className="hover:text-[#D71920] transition-colors text-left">
                  Bayeux e Santa Rita
                </button>
              </li>
              <li>
                <button onClick={() => navigateTo('areas')} className="hover:text-[#D71920] transition-colors text-left">
                  Conde e Litoral Sul
                </button>
              </li>
              <li>
                <button onClick={() => navigateTo('areas')} className="text-[#D71920] font-semibold hover:underline mt-1 inline-block">
                  Consultar serviço Leva e Traz &rarr;
                </button>
              </li>
            </ul>
          </div>

          {/* Column 4: Atendimento & Contatos */}
          <div>
            <h4 className="font-heading font-bold text-xs uppercase tracking-wider text-[#202124] mb-3">
              Canais de Atendimento
            </h4>
            <ul className="space-y-2.5 text-xs text-[#697386]">
              <li className="flex items-center gap-2">
                <Phone className="w-3.5 h-3.5 text-[#D71920] shrink-0" />
                <span className="font-semibold text-[#202124]">{companySettings.phone}</span>
              </li>
              <li className="flex items-center gap-2">
                <span className="w-3.5 h-3.5 flex items-center justify-center text-[#25D366] shrink-0 font-bold">W</span>
                <span className="font-semibold text-[#202124]">{companySettings.whatsapp}</span>
              </li>
              <li className="flex items-center gap-2">
                <Clock className="w-3.5 h-3.5 text-[#697386] shrink-0" />
                <span>{companySettings.workingHoursWeekday}</span>
              </li>
              <li className="flex items-center gap-2">
                <Clock className="w-3.5 h-3.5 text-[#697386] shrink-0" />
                <span>{companySettings.workingHoursSaturday}</span>
              </li>
              <li className="flex items-start gap-2 pt-1">
                <MapPin className="w-3.5 h-3.5 text-[#D71920] shrink-0 mt-0.5" />
                <span>{companySettings.address} - João Pessoa/PB</span>
              </li>
            </ul>
          </div>
        </div>

        {/* Bottom copyright and legal links */}
        <div className="border-t border-[#E4E7EC] pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-[#697386]">
          <p>© {new Date().getFullYear()} {companySettings.name}. CNPJ: {companySettings.cnpj}. Todos os direitos reservados.</p>
          <div className="flex flex-wrap items-center gap-5">
            <button
              onClick={() => navigateTo('termos')}
              className="hover:text-[#D71920] transition-colors"
            >
              Termos de Uso
            </button>
            <span>·</span>
            <button
              onClick={() => navigateTo('privacidade')}
              className="hover:text-[#D71920] transition-colors"
            >
              Política de Privacidade (LGPD)
            </button>
            <span>·</span>
            <button
              onClick={() => navigateTo('admin-dashboard')}
              className="inline-flex items-center gap-1 text-[#202124] hover:text-[#D71920] font-semibold"
            >
              <Lock className="w-3 h-3 text-[#D71920]" />
              <span>Acesso Restrito</span>
            </button>
          </div>
        </div>
      </div>
    </footer>
  );
};
