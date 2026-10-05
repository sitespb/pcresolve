import React, { useState } from 'react';
import { useApp } from '../context/AppContext';
import {
  ArrowLeft,
  Clock,
  ShieldCheck,
  CheckCircle2,
  AlertCircle,
  Phone,
  Send,
  Sliders,
  DollarSign,
} from 'lucide-react';
import { DeviceType } from '../types';

export const ServiceDetailPage: React.FC = () => {
  const { services, selectedServiceSlug, companySettings, addLead, navigateTo, showToast } = useApp();

  const service =
    services.find((s) => s.slug === selectedServiceSlug) ||
    services[0] ||
    null;

  const [customerName, setCustomerName] = useState('');
  const [phone, setPhone] = useState('');
  const [deviceType, setDeviceType] = useState<DeviceType>('notebook');
  const [city, setCity] = useState('João Pessoa');
  const [description, setDescription] = useState('');
  const [submittedProtocol, setSubmittedProtocol] = useState<string | null>(null);

  if (!service) {
    return (
      <div className="max-w-4xl mx-auto py-20 px-4 text-center">
        <p className="text-base font-semibold">Serviço não encontrado.</p>
        <button
          onClick={() => navigateTo('servicos')}
          className="mt-4 px-4 py-2 bg-[#D71920] text-white rounded-lg text-xs font-semibold"
        >
          Voltar para Serviços
        </button>
      </div>
    );
  }

  const cleanPhone = companySettings.whatsapp.replace(/\D/g, '');
  const whatsappUrl = `https://wa.me/55${cleanPhone}?text=${encodeURIComponent(
    `Olá! Gostaria de agendar o serviço: ${service.title}`
  )}`;

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!customerName.trim() || !phone.trim()) {
      showToast('Por favor, informe seu nome e WhatsApp.', 'warning');
      return;
    }

    const protocol = addLead({
      customerName,
      phone,
      city,
      deviceType,
      serviceType: service.title,
      description: description || `Solicitação direta para ${service.title}`,
    });

    setSubmittedProtocol(protocol);
    setCustomerName('');
    setPhone('');
    setDescription('');
  };

  return (
    <div className="bg-white py-10 lg:py-16">
      <div className="max-w-7xl mx-auto px-4 sm:px-8">
        {/* Back navigation */}
        <div className="mb-8">
          <button
            onClick={() => navigateTo('servicos')}
            className="inline-flex items-center gap-2 text-xs font-semibold text-[#697386] hover:text-[#D71920] transition-colors cursor-pointer"
          >
            <ArrowLeft className="w-4 h-4" />
            <span>Voltar ao catálogo de serviços</span>
          </button>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
          {/* Main Service Details (Col 8) */}
          <div className="lg:col-span-8 space-y-8">
            <div>
              <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#F5F6F8] text-xs font-semibold text-[#D71920] mb-3">
                <Sliders className="w-3.5 h-3.5" />
                <span>Bancada Técnica ESD Certificada</span>
              </div>
              <h1 className="font-heading font-extrabold text-2xl sm:text-4xl text-[#202124] tracking-tight">
                {service.title}
              </h1>
              <p className="text-sm sm:text-base text-[#697386] mt-3 leading-relaxed">
                {service.shortDesc}
              </p>
            </div>

            {/* Main Photography Banner */}
            <div className="rounded-2xl overflow-hidden border border-[#E4E7EC] shadow-md bg-neutral-100 relative">
              <img
                src={service.image}
                alt={service.title}
                className="w-full aspect-[16/9] object-cover"
              />
              <div className="absolute bottom-4 left-4 right-4 bg-white/95 backdrop-blur-md p-4 rounded-xl border border-[#E4E7EC] flex flex-wrap items-center justify-between gap-4">
                <div className="flex items-center gap-4">
                  <div className="flex items-center gap-1.5 text-xs text-[#202124] font-medium">
                    <Clock className="w-4 h-4 text-[#D71920]" />
                    <span>Prazo: <strong>{service.turnaroundTime}</strong></span>
                  </div>
                  <div className="flex items-center gap-1.5 text-xs text-[#202124] font-medium">
                    <ShieldCheck className="w-4 h-4 text-[#25D366]" />
                    <span>Garantia: <strong>{service.warrantyDays} dias</strong></span>
                  </div>
                </div>
                <div className="text-xs font-bold text-[#D71920]">
                  Investimento estimado a partir de R$ {service.priceStartingAt}
                </div>
              </div>
            </div>

            {/* Full Technical Description */}
            <div className="space-y-4">
              <h2 className="font-heading font-bold text-lg text-[#202124]">
                Sobre o procedimento técnico
              </h2>
              <p className="text-sm text-[#697386] leading-relaxed">
                {service.fullDesc}
              </p>
            </div>

            {/* Highlights of procedure */}
            {service.highlights && service.highlights.length > 0 && (
              <div className="bg-[#F5F6F8] p-6 rounded-2xl border border-[#E4E7EC] space-y-4">
                <h3 className="font-heading font-bold text-sm text-[#202124] uppercase tracking-wider">
                  Diferenciais e Padrão de Execução
                </h3>
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                  {service.highlights.map((h, i) => (
                    <div key={i} className="flex items-start gap-2.5 text-xs text-[#202124]">
                      <CheckCircle2 className="w-4 h-4 text-[#25D366] shrink-0 mt-0.5" />
                      <span>{h}</span>
                    </div>
                  ))}
                </div>
              </div>
            )}

            {/* Recommended For (Symptoms) */}
            {service.recommendedFor && service.recommendedFor.length > 0 && (
              <div className="bg-white p-6 rounded-2xl border border-[#E4E7EC] space-y-4">
                <h3 className="font-heading font-bold text-sm text-[#202124] uppercase tracking-wider flex items-center gap-2">
                  <AlertCircle className="w-4 h-4 text-[#D71920]" />
                  <span>Sintomas mais comuns que demandam esse serviço</span>
                </h3>
                <ul className="space-y-2 text-xs text-[#697386]">
                  {service.recommendedFor.map((rec, i) => (
                    <li key={i} className="flex items-center gap-2">
                      <span className="w-1.5 h-1.5 rounded-full bg-[#D71920]"></span>
                      <span>{rec}</span>
                    </li>
                  ))}
                </ul>
              </div>
            )}
          </div>

          {/* Sidebar Quote & Action Form (Col 4) */}
          <div className="lg:col-span-4 sticky top-28 space-y-6">
            <div className="bg-[#F5F6F8] p-6 rounded-2xl border border-[#E4E7EC] shadow-sm">
              <h3 className="font-heading font-bold text-base text-[#202124] mb-1">
                Solicitar este serviço
              </h3>
              <p className="text-xs text-[#697386] mb-5">
                Receba retorno com estimativa de custo e orientações de bancada.
              </p>

              {submittedProtocol ? (
                <div className="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-center space-y-3">
                  <CheckCircle2 className="w-8 h-8 text-emerald-600 mx-auto" />
                  <p className="text-xs font-bold text-emerald-950">
                    Solicitação enviada com sucesso!
                  </p>
                  <p className="text-xs text-emerald-800">
                    Protocolo: <strong className="font-mono">{submittedProtocol}</strong>
                  </p>
                  <button
                    onClick={() => setSubmittedProtocol(null)}
                    className="text-xs text-emerald-900 underline font-semibold cursor-pointer"
                  >
                    Fazer novo pedido
                  </button>
                </div>
              ) : (
                <form onSubmit={handleSubmit} className="space-y-3.5">
                  <div>
                    <label className="block text-xs font-semibold text-[#202124] mb-1">
                      Seu nome completo *
                    </label>
                    <input
                      type="text"
                      required
                      value={customerName}
                      onChange={(e) => setCustomerName(e.target.value)}
                      placeholder="Ex: Carlos Oliveira"
                      className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]"
                    />
                  </div>

                  <div>
                    <label className="block text-xs font-semibold text-[#202124] mb-1">
                      WhatsApp para retorno *
                    </label>
                    <input
                      type="tel"
                      required
                      value={phone}
                      onChange={(e) => setPhone(e.target.value)}
                      placeholder="(83) 99999-9999"
                      className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]"
                    />
                  </div>

                  <div className="grid grid-cols-2 gap-2">
                    <div>
                      <label className="block text-xs font-semibold text-[#202124] mb-1">
                        Cidade
                      </label>
                      <select
                        value={city}
                        onChange={(e) => setCity(e.target.value)}
                        className="w-full px-2.5 py-2 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124]"
                      >
                        <option value="João Pessoa">João Pessoa</option>
                        <option value="Cabedelo">Cabedelo</option>
                        <option value="Bayeux">Bayeux</option>
                        <option value="Santa Rita">Santa Rita</option>
                        <option value="Outra">Outra</option>
                      </select>
                    </div>

                    <div>
                      <label className="block text-xs font-semibold text-[#202124] mb-1">
                        Dispositivo
                      </label>
                      <select
                        value={deviceType}
                        onChange={(e) => setDeviceType(e.target.value as DeviceType)}
                        className="w-full px-2.5 py-2 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124]"
                      >
                        <option value="notebook">Notebook</option>
                        <option value="desktop">PC Desktop</option>
                        <option value="macbook">MacBook</option>
                        <option value="corporativo">Empresa</option>
                      </select>
                    </div>
                  </div>

                  <div>
                    <label className="block text-xs font-semibold text-[#202124] mb-1">
                      Observações adicionais (opcional)
                    </label>
                    <textarea
                      rows={2}
                      value={description}
                      onChange={(e) => setDescription(e.target.value)}
                      placeholder="Ex: Marca do aparelho, ano ou defeito específico..."
                      className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]"
                    />
                  </div>

                  <button
                    type="submit"
                    className="w-full py-3 px-4 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold transition-colors flex items-center justify-center gap-2 cursor-pointer shadow-xs"
                  >
                    <Send className="w-3.5 h-3.5" />
                    <span>Solicitar Orçamento</span>
                  </button>
                </form>
              )}

              <div className="mt-4 pt-4 border-t border-[#E4E7EC] text-center">
                <span className="text-[11px] text-[#697386]">Prefere falar agora com o técnico?</span>
                <a
                  href={whatsappUrl}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="mt-2 inline-flex items-center justify-center gap-2 w-full py-2.5 px-3 rounded-lg bg-[#25D366] hover:bg-[#20ba59] text-white text-xs font-semibold transition-colors"
                >
                  <Phone className="w-3.5 h-3.5" />
                  <span>Chamar no WhatsApp</span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
