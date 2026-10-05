import React, { useState } from 'react';
import { useApp } from '../context/AppContext';
import { Phone, Clock, MapPin, Mail, Send, CheckCircle2 } from 'lucide-react';
import { DeviceType } from '../types';

export const ContactPage: React.FC = () => {
  const { companySettings, addLead, showToast } = useApp();

  const [formData, setFormData] = useState({
    customerName: '',
    phone: '',
    email: '',
    city: 'João Pessoa',
    deviceType: 'notebook' as DeviceType,
    serviceType: 'Diagnóstico geral de falha',
    description: '',
    consent: false,
  });

  const [protocol, setProtocol] = useState<string | null>(null);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!formData.customerName.trim() || !formData.phone.trim()) {
      showToast('Por favor, informe seu nome e telefone.', 'warning');
      return;
    }
    if (!formData.consent) {
      showToast('Por favor, assinale o consentimento para prosseguir.', 'warning');
      return;
    }

    const generatedProtocol = addLead({
      customerName: formData.customerName,
      phone: formData.phone,
      email: formData.email,
      city: formData.city,
      deviceType: formData.deviceType,
      serviceType: formData.serviceType,
      description: formData.description,
    });

    setProtocol(generatedProtocol);
    setFormData({
      customerName: '',
      phone: '',
      email: '',
      city: 'João Pessoa',
      deviceType: 'notebook',
      serviceType: 'Diagnóstico geral de falha',
      description: '',
      consent: false,
    });
  };

  const cleanPhone = companySettings.whatsapp.replace(/\D/g, '');
  const whatsappUrl = `https://wa.me/55${cleanPhone}?text=${encodeURIComponent(
    companySettings.defaultWhatsappMessage
  )}`;

  return (
    <div className="bg-white py-12 lg:py-16">
      <div className="max-w-7xl mx-auto px-4 sm:px-8 space-y-12">
        <div className="max-w-3xl">
          <span className="text-xs font-bold uppercase tracking-wider text-[#D71920]">
            Atendimento Técnico
          </span>
          <h1 className="font-heading font-extrabold text-3xl sm:text-4xl text-[#202124] tracking-tight mt-2">
            Fale com a PC Resolve
          </h1>
          <p className="text-sm sm:text-base text-[#697386] mt-3 leading-relaxed">
            Estamos localizados em João Pessoa com estrutura pronta para receber seu equipamento ou coletar em seu endereço.
          </p>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
          {/* Contact Details (Col 5) */}
          <div className="lg:col-span-5 space-y-6">
            <div className="bg-[#F5F6F8] p-6 rounded-2xl border border-[#E4E7EC] space-y-4">
              <h2 className="font-heading font-bold text-base text-[#202124]">
                Informações de Contato
              </h2>

              <div className="space-y-3 pt-1">
                <div className="flex items-start gap-3 p-3.5 rounded-xl bg-white border border-[#E4E7EC]">
                  <Phone className="w-5 h-5 text-[#D71920] shrink-0 mt-0.5" />
                  <div>
                    <h4 className="font-heading font-bold text-xs text-[#202124]">Telefone Fixo</h4>
                    <p className="text-xs text-[#697386] mt-0.5">{companySettings.phone}</p>
                  </div>
                </div>

                <div className="flex items-start gap-3 p-3.5 rounded-xl bg-white border border-[#E4E7EC]">
                  <div className="w-5 h-5 flex items-center justify-center text-[#25D366] font-bold text-sm shrink-0">
                    W
                  </div>
                  <div>
                    <h4 className="font-heading font-bold text-xs text-[#202124]">WhatsApp Técnico</h4>
                    <p className="text-xs text-[#697386] mt-0.5">{companySettings.whatsapp}</p>
                    <a
                      href={whatsappUrl}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="text-[11px] font-semibold text-[#25D366] hover:underline mt-1 inline-block"
                    >
                      Iniciar conversa imediata &rarr;
                    </a>
                  </div>
                </div>

                <div className="flex items-start gap-3 p-3.5 rounded-xl bg-white border border-[#E4E7EC]">
                  <Mail className="w-5 h-5 text-[#D71920] shrink-0 mt-0.5" />
                  <div>
                    <h4 className="font-heading font-bold text-xs text-[#202124]">E-mail Corporativo</h4>
                    <p className="text-xs text-[#697386] mt-0.5">{companySettings.email}</p>
                  </div>
                </div>

                <div className="flex items-start gap-3 p-3.5 rounded-xl bg-white border border-[#E4E7EC]">
                  <Clock className="w-5 h-5 text-[#697386] shrink-0 mt-0.5" />
                  <div>
                    <h4 className="font-heading font-bold text-xs text-[#202124]">Horários de Atendimento</h4>
                    <p className="text-xs text-[#697386] mt-0.5">{companySettings.workingHoursWeekday}</p>
                    <p className="text-xs text-[#697386]">{companySettings.workingHoursSaturday}</p>
                  </div>
                </div>

                <div className="flex items-start gap-3 p-3.5 rounded-xl bg-white border border-[#E4E7EC]">
                  <MapPin className="w-5 h-5 text-[#D71920] shrink-0 mt-0.5" />
                  <div>
                    <h4 className="font-heading font-bold text-xs text-[#202124]">Endereço</h4>
                    <p className="text-xs text-[#697386] mt-0.5">{companySettings.address}</p>
                    <p className="text-xs text-[#697386]">Bairro {companySettings.neighborhood} - {companySettings.city}/{companySettings.state}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {/* Form (Col 7) */}
          <div className="lg:col-span-7 bg-[#F5F6F8] p-6 sm:p-8 rounded-2xl border border-[#E4E7EC] shadow-sm">
            <h2 className="font-heading font-bold text-lg text-[#202124] mb-1">
              Formulário de Solicitação de Atendimento
            </h2>
            <p className="text-xs text-[#697386] mb-6">
              Preencha os dados e receba resposta técnica com orientações e número de protocolo.
            </p>

            {protocol ? (
              <div className="p-6 rounded-xl bg-white border border-emerald-200 text-center space-y-4">
                <div className="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto">
                  <CheckCircle2 className="w-6 h-6" />
                </div>
                <div>
                  <h3 className="font-heading font-bold text-base text-emerald-950">
                    Solicitação Protocolada com Sucesso!
                  </h3>
                  <p className="text-xs text-neutral-600 mt-1">
                    Guarde o seu número de protocolo:
                  </p>
                  <div className="mt-2 inline-block px-3 py-1 bg-[#F5F6F8] rounded-md border border-[#E4E7EC] font-mono text-sm font-bold text-[#D71920]">
                    {protocol}
                  </div>
                </div>
                <p className="text-xs text-[#697386] max-w-md mx-auto">
                  Nossa equipe técnica entrará em contato em instantes através do WhatsApp fornecido com a triagem inicial do seu equipamento.
                </p>
                <button
                  onClick={() => setProtocol(null)}
                  className="px-5 py-2.5 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold transition-colors cursor-pointer"
                >
                  Enviar Outra Solicitação
                </button>
              </div>
            ) : (
              <form onSubmit={handleSubmit} className="space-y-4">
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label className="block text-xs font-semibold text-[#202124] mb-1">
                      Nome completo *
                    </label>
                    <input
                      type="text"
                      required
                      value={formData.customerName}
                      onChange={(e) => setFormData({ ...formData, customerName: e.target.value })}
                      placeholder="Ex: Carlos Oliveira"
                      className="w-full px-3.5 py-2.5 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]"
                    />
                  </div>
                  <div>
                    <label className="block text-xs font-semibold text-[#202124] mb-1">
                      Telefone / WhatsApp *
                    </label>
                    <input
                      type="tel"
                      required
                      value={formData.phone}
                      onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
                      placeholder="(83) 99999-9999"
                      className="w-full px-3.5 py-2.5 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]"
                    />
                  </div>
                </div>

                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label className="block text-xs font-semibold text-[#202124] mb-1">
                      E-mail (opcional)
                    </label>
                    <input
                      type="email"
                      value={formData.email}
                      onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                      placeholder="seu.email@exemplo.com"
                      className="w-full px-3.5 py-2.5 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]"
                    />
                  </div>
                  <div>
                    <label className="block text-xs font-semibold text-[#202124] mb-1">
                      Cidade *
                    </label>
                    <select
                      value={formData.city}
                      onChange={(e) => setFormData({ ...formData, city: e.target.value })}
                      className="w-full px-3.5 py-2.5 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]"
                    >
                      <option value="João Pessoa">João Pessoa</option>
                      <option value="Cabedelo">Cabedelo</option>
                      <option value="Bayeux">Bayeux</option>
                      <option value="Santa Rita">Santa Rita</option>
                      <option value="Conde">Conde</option>
                      <option value="Lucena">Lucena</option>
                      <option value="Outro">Outro município da região</option>
                    </select>
                  </div>
                </div>

                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label className="block text-xs font-semibold text-[#202124] mb-1">
                      Tipo de equipamento *
                    </label>
                    <select
                      value={formData.deviceType}
                      onChange={(e) =>
                        setFormData({ ...formData, deviceType: e.target.value as DeviceType })
                      }
                      className="w-full px-3.5 py-2.5 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]"
                    >
                      <option value="notebook">Notebook</option>
                      <option value="desktop">Computador Desktop</option>
                      <option value="all-in-one">All-in-One</option>
                      <option value="macbook">Apple MacBook</option>
                      <option value="corporativo">Parque corporativo / Empresa</option>
                    </select>
                  </div>

                  <div>
                    <label className="block text-xs font-semibold text-[#202124] mb-1">
                      Serviço desejado
                    </label>
                    <select
                      value={formData.serviceType}
                      onChange={(e) => setFormData({ ...formData, serviceType: e.target.value })}
                      className="w-full px-3.5 py-2.5 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]"
                    >
                      <option value="Diagnóstico geral de falha">Diagnóstico geral de falha</option>
                      <option value="Upgrade de SSD / Memória RAM">Upgrade de SSD / Memória RAM</option>
                      <option value="Limpeza preventiva e troca de pasta térmica">
                        Limpeza preventiva e troca de pasta térmica
                      </option>
                      <option value="Reparo de placa-mãe / Não liga">Reparo de placa-mãe / Não liga</option>
                      <option value="Formatação e instalação de sistema">
                        Formatação e instalação de sistema
                      </option>
                      <option value="Troca de tela ou teclado de notebook">
                        Troca de tela ou teclado de notebook
                      </option>
                      <option value="Suporte técnico empresarial">Suporte técnico empresarial</option>
                    </select>
                  </div>
                </div>

                <div>
                  <label className="block text-xs font-semibold text-[#202124] mb-1">
                    Descrição detalhada do problema
                  </label>
                  <textarea
                    rows={3}
                    value={formData.description}
                    onChange={(e) => setFormData({ ...formData, description: e.target.value })}
                    placeholder="Descreva o que acontece: lentidão, travamento, tela preta, ruído, etc."
                    className="w-full px-3.5 py-2.5 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]"
                  />
                </div>

                <div className="flex items-start gap-2 pt-1">
                  <input
                    type="checkbox"
                    id="consent-contact"
                    checked={formData.consent}
                    onChange={(e) => setFormData({ ...formData, consent: e.target.checked })}
                    className="mt-0.5 accent-[#D71920]"
                  />
                  <label htmlFor="consent-contact" className="text-[11px] text-[#697386] leading-tight">
                    Concordo com o tratamento dos dados informados para fins de contato comercial e orçamento técnico, segundo a Lei Geral de Proteção de Dados (LGPD).
                  </label>
                </div>

                <button
                  type="submit"
                  className="w-full py-3.5 px-6 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white font-semibold text-xs tracking-wide transition-all shadow-xs flex items-center justify-center gap-2 cursor-pointer"
                >
                  <Send className="w-4 h-4" />
                  <span>Registrar Solicitação</span>
                </button>
              </form>
            )}
          </div>
        </div>
      </div>
    </div>
  );
};
