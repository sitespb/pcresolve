import React, { useState } from 'react';
import { useApp } from '../context/AppContext';
import {
  Laptop,
  Cpu,
  Zap,
  Activity,
  Terminal,
  Shield,
  Fan,
  Building,
  CheckCircle2,
  ArrowRight,
  ArrowDown,
  Phone,
  Clock,
  MapPin,
  Star,
  ChevronDown,
  ShieldAlert,
  Send,
  Truck,
  Eye,
  Sliders,
  MessageSquare,
} from 'lucide-react';
import { DeviceType } from '../types';

export const HomePage: React.FC = () => {
  const {
    services,
    testimonials,
    cities,
    faqs,
    companySettings,
    addLead,
    navigateTo,
    showToast,
  } = useApp();

  // Contact form state
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

  const [formSubmittedProtocol, setFormSubmittedProtocol] = useState<string | null>(null);

  const handleFormSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!formData.customerName.trim() || !formData.phone.trim()) {
      showToast('Por favor, informe seu nome e telefone/WhatsApp.', 'warning');
      return;
    }
    if (!formData.consent) {
      showToast('É necessário concordar com o tratamento dos dados.', 'warning');
      return;
    }

    const protocol = addLead({
      customerName: formData.customerName,
      phone: formData.phone,
      email: formData.email,
      city: formData.city,
      deviceType: formData.deviceType,
      serviceType: formData.serviceType,
      description: formData.description,
    });

    setFormSubmittedProtocol(protocol);
    // Reset form
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

  const getServiceIcon = (iconName: string) => {
    switch (iconName) {
      case 'Laptop':
        return <Laptop className="w-6 h-6 text-[#D71920]" />;
      case 'Cpu':
        return <Cpu className="w-6 h-6 text-[#D71920]" />;
      case 'Zap':
        return <Zap className="w-6 h-6 text-[#D71920]" />;
      case 'Activity':
        return <Activity className="w-6 h-6 text-[#D71920]" />;
      case 'Terminal':
        return <Terminal className="w-6 h-6 text-[#D71920]" />;
      case 'Shield':
        return <Shield className="w-6 h-6 text-[#D71920]" />;
      case 'Fan':
        return <Fan className="w-6 h-6 text-[#D71920]" />;
      case 'Building':
        return <Building className="w-6 h-6 text-[#D71920]" />;
      default:
        return <Cpu className="w-6 h-6 text-[#D71920]" />;
    }
  };

  const cleanPhone = companySettings.whatsapp.replace(/\D/g, '');
  const encodedMsg = encodeURIComponent(companySettings.defaultWhatsappMessage);
  const whatsappUrl = `https://wa.me/55${cleanPhone}?text=${encodedMsg}`;

  const approvedTestimonials = testimonials.filter((t) => t.approved);

  return (
    <main className="bg-white">
      {/* 1. HERO SECTION */}
      <section className="relative bg-white pt-10 pb-16 lg:py-20 overflow-hidden">
        <div className="max-w-7xl mx-auto px-4 sm:px-8">
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            {/* Column 1: Text and CTAs */}
            <div className="lg:col-span-7 flex flex-col items-start space-y-6">
              <div className="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#F5F6F8] border border-[#E4E7EC] text-xs font-semibold text-[#697386]">
                <span className="w-2 h-2 rounded-full bg-[#D71920] animate-pulse"></span>
                <span>Assistência técnica especializada • Grande João Pessoa</span>
              </div>

              {/* H1 SEO */}
              <h1 className="font-heading font-extrabold text-3xl sm:text-4xl lg:text-[46px] leading-[1.18] tracking-tight text-[#202124]">
                Assistência Técnica de Computadores e Notebooks em João Pessoa
              </h1>

              {/* Supporting Text */}
              <p className="text-base sm:text-lg text-[#697386] leading-relaxed max-w-2xl">
                Manutenção, diagnóstico e suporte técnico para computadores e notebooks, com atendimento em João Pessoa e cidades da região metropolitana.
              </p>

              {/* Conversion Actions */}
              <div className="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 w-full sm:w-auto pt-2">
                <a
                  href={whatsappUrl}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="inline-flex items-center justify-center gap-3 px-7 py-4 rounded-xl bg-[#D71920] hover:bg-[#A90F17] text-white font-semibold text-sm transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5 cursor-pointer"
                >
                  <svg viewBox="0 0 24 24" className="fill-current w-5 h-5 shrink-0" aria-hidden="true">
                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.299.144.347.491 1.2.534 1.288.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.861.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.144.39-.086s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.824z" />
                  </svg>
                  <span>Solicitar orçamento pelo WhatsApp</span>
                </a>

                <button
                  onClick={() => navigateTo('servicos')}
                  className="inline-flex items-center justify-center gap-2 px-6 py-4 rounded-xl bg-[#F5F6F8] hover:bg-[#E4E7EC] text-[#202124] font-semibold text-sm border border-[#E4E7EC] transition-all cursor-pointer"
                >
                  <span>Conhecer nossos serviços</span>
                  <ArrowDown className="w-4 h-4 text-[#697386]" />
                </button>
              </div>

              {/* Trust Badges */}
              <div className="pt-3 flex flex-wrap items-center gap-6 text-xs text-[#697386]">
                <div className="flex items-center gap-1.5 font-medium">
                  <CheckCircle2 className="w-4 h-4 text-[#25D366]" />
                  <span>Garantia de 90 dias em serviços</span>
                </div>
                <div className="flex items-center gap-1.5 font-medium">
                  <ShieldAlert className="w-4 h-4 text-[#D71920]" />
                  <span>Sigilo e proteção total dos dados (LGPD)</span>
                </div>
              </div>
            </div>

            {/* Column 2: Photographic Asset */}
            <div className="lg:col-span-5 relative">
              <div className="relative rounded-2xl overflow-hidden border border-[#E4E7EC] shadow-xl bg-[#F5F6F8]">
                <img
                  src="https://lh3.googleusercontent.com/aida-public/AB6AXuDfSe_gwOY_AdVah5Xm_Sn-sD2ei8fnIMOHmB1KdXZ8JTdndu6vUePLLl16K1iI-5WPIVT2ZuqkXg3cvpNbZ6XPH79gF4jqUSq8qLXVky0xZI5RAKqVbMCVqI4cYQyuoUh3hTeAXlHRqAwCQ4qDaZanaVWrg0Z2QGXvPCwrUG7GvTTJeEzGtbxp3C4dvXCnPIxZ7OuD6eRTGHQNs35LBR8Pot62ON4jwvZUhuN0muvfAyJKuU6p05awwg"
                  alt="Profissional qualificada da PC Resolve realizando diagnóstico em placa de notebook em bancada organizada"
                  className="w-full aspect-[4/3] object-cover object-center"
                />
                <div className="absolute bottom-4 left-4 right-4 bg-white/95 backdrop-blur-md p-3.5 rounded-xl border border-[#E4E7EC] shadow-md flex items-center justify-between">
                  <div className="flex items-center gap-3">
                    <div className="w-9 h-9 rounded-lg bg-[#F5F6F8] border border-[#E4E7EC] flex items-center justify-center text-[#D71920]">
                      <Sliders className="w-5 h-5 text-[#D71920]" />
                    </div>
                    <div>
                      <p className="font-heading font-bold text-xs text-[#202124]">Bancada técnica ESD</p>
                      <p className="text-[11px] text-[#697386]">Diagnóstico preciso &amp; instrumentação calibrada</p>
                    </div>
                  </div>
                  <span className="px-2 py-0.5 rounded text-[10px] font-bold bg-[#E4E7EC] text-[#202124]">Ativo</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* 2. FAIXA DE CONFIANÇA */}
      <section className="bg-[#F5F6F8] border-y border-[#E4E7EC] py-8">
        <div className="max-w-7xl mx-auto px-4 sm:px-8">
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div className="flex items-center gap-4 bg-white p-4 rounded-xl border border-[#E4E7EC] shadow-xs">
              <div className="w-12 h-12 rounded-xl bg-[#F5F6F8] flex items-center justify-center text-[#D71920] shrink-0">
                <Activity className="w-6 h-6" />
              </div>
              <div>
                <h4 className="font-heading font-bold text-sm text-[#202124]">Diagnóstico técnico</h4>
                <p className="text-xs text-[#697386] mt-0.5">Análise precisa de circuitos</p>
              </div>
            </div>

            <div className="flex items-center gap-4 bg-white p-4 rounded-xl border border-[#E4E7EC] shadow-xs">
              <div className="w-12 h-12 rounded-xl bg-[#F5F6F8] flex items-center justify-center text-[#D71920] shrink-0">
                <Zap className="w-6 h-6" />
              </div>
              <div>
                <h4 className="font-heading font-bold text-sm text-[#202124]">Atendimento ágil</h4>
                <p className="text-xs text-[#697386] mt-0.5">Agilidade e transparência</p>
              </div>
            </div>

            <div className="flex items-center gap-4 bg-white p-4 rounded-xl border border-[#E4E7EC] shadow-xs">
              <div className="w-12 h-12 rounded-xl bg-[#F5F6F8] flex items-center justify-center text-[#D71920] shrink-0">
                <Laptop className="w-6 h-6" />
              </div>
              <div>
                <h4 className="font-heading font-bold text-sm text-[#202124]">Soluções completas</h4>
                <p className="text-xs text-[#697386] mt-0.5">Para computadores e notebooks</p>
              </div>
            </div>

            <div className="flex items-center gap-4 bg-white p-4 rounded-xl border border-[#E4E7EC] shadow-xs">
              <div className="w-12 h-12 rounded-xl bg-[#F5F6F8] flex items-center justify-center text-[#25D366] shrink-0">
                <Phone className="w-6 h-6 text-[#25D366]" />
              </div>
              <div>
                <h4 className="font-heading font-bold text-sm text-[#202124]">Contato direto</h4>
                <p className="text-xs text-[#697386] mt-0.5">Fale com os técnicos no WhatsApp</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* 3. CATÁLOGO DE SERVIÇOS */}
      <section id="servicos" className="py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-8">
          <div className="text-center max-w-3xl mx-auto mb-16">
            <span className="text-xs font-bold uppercase tracking-wider text-[#D71920]">Especialidades</span>
            <h2 className="font-heading font-extrabold text-2xl sm:text-3xl lg:text-4xl text-[#202124] mt-2 mb-3">
              Serviços de manutenção e suporte em informática
            </h2>
            <p className="text-base text-[#697386]">
              Soluções para manter seus equipamentos seguros, rápidos e funcionando corretamente.
            </p>
          </div>

          {/* Grid de Serviços */}
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {services.slice(0, 8).map((service) => (
              <div
                key={service.id}
                className="flex flex-col bg-[#F5F6F8] border border-[#E4E7EC] rounded-2xl overflow-hidden hover:border-[#D71920]/50 hover:shadow-md transition-all group"
              >
                <div className="h-44 w-full overflow-hidden bg-neutral-100 relative">
                  <img
                    src={service.image}
                    alt={service.title}
                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                  />
                  <div className="absolute top-3 left-3 w-8 h-8 rounded-lg bg-white/90 backdrop-blur-xs flex items-center justify-center shadow-xs">
                    {getServiceIcon(service.iconName)}
                  </div>
                </div>

                <div className="p-6 flex-1 flex flex-col justify-between space-y-4">
                  <div>
                    <h3 className="font-heading font-bold text-base text-[#202124] mb-2 leading-snug">
                      {service.title}
                    </h3>
                    <p className="text-xs text-[#697386] leading-relaxed line-clamp-3">
                      {service.shortDesc}
                    </p>
                  </div>

                  <div className="pt-3 border-t border-[#E4E7EC] flex items-center justify-between">
                    <button
                      onClick={() => navigateTo('servico-detalhe', service.slug)}
                      className="text-xs font-semibold text-[#D71920] group-hover:underline flex items-center gap-1 cursor-pointer"
                    >
                      <span>Saiba mais</span>
                      <ArrowRight className="w-3.5 h-3.5" />
                    </button>

                    <a
                      href={`https://wa.me/55${cleanPhone}?text=${encodeURIComponent(
                        `Olá! Gostaria de um orçamento para: ${service.title}`
                      )}`}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="text-[11px] font-medium px-2.5 py-1 rounded-md bg-white border border-[#E4E7EC] hover:bg-[#D71920] hover:text-white hover:border-[#D71920] transition-colors"
                    >
                      Solicitar
                    </a>
                  </div>
                </div>
              </div>
            ))}
          </div>

          <div className="mt-12 text-center">
            <button
              onClick={() => navigateTo('servicos')}
              className="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white border border-[#E4E7EC] hover:border-[#D71920] hover:text-[#D71920] text-[#202124] font-semibold text-xs transition-colors shadow-2xs cursor-pointer"
            >
              <span>Ver todos os 12 serviços cadastrados</span>
              <ArrowRight className="w-4 h-4" />
            </button>
          </div>
        </div>
      </section>

      {/* 4. DIFERENCIAIS TÉCNICOS */}
      <section id="diferenciais" className="py-20 bg-[#F5F6F8] border-y border-[#E4E7EC]">
        <div className="max-w-7xl mx-auto px-4 sm:px-8">
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            {/* Left Photo */}
            <div className="lg:col-span-5 relative">
              <div className="rounded-2xl overflow-hidden border border-[#E4E7EC] shadow-lg bg-white">
                <img
                  src="https://lh3.googleusercontent.com/aida-public/AB6AXuDL8KGLk9WBdURz4Vfpz1xflvMhnQr7PDMYyZNghKrCt2Nlmy2DObTEtVSRJjsIWN-_gKRyD529_owAQ7szWMEdzUhURy6Wxxlj7m84aNKjYm3h2YkHWXA7oP2ksErYHWSziawRC4pSQNQGCloJdmfgPa_hYL752-f1gV3bgVRysl-qrD8XmiRogStVppbkvcNRiRSVkAac4DM_ohWHRr8P4NBFdQ_ECz4YVRiLbUNOReTNDNtYdn0okg"
                  alt="Técnico aplicando composto térmico de precisão em bancada ESD"
                  className="w-full aspect-[4/3] object-cover"
                />
              </div>
              <div className="absolute -bottom-4 -right-4 hidden sm:flex items-center gap-3 bg-white p-4 rounded-xl border border-[#E4E7EC] shadow-md max-w-xs">
                <CheckCircle2 className="w-7 h-7 text-[#D71920] shrink-0" />
                <div>
                  <p className="font-heading font-bold text-xs text-[#202124]">Padrão ESD Anti-estática</p>
                  <p className="text-[11px] text-[#697386]">Proteção de chips e circuitos sensíveis</p>
                </div>
              </div>
            </div>

            {/* Right Pillars */}
            <div className="lg:col-span-7 flex flex-col space-y-6">
              <span className="text-xs font-bold uppercase tracking-wider text-[#D71920]">Compromisso Técnico</span>
              <h2 className="font-heading font-extrabold text-2xl sm:text-3xl lg:text-4xl text-[#202124] leading-tight">
                Seu equipamento merece cuidado especializado
              </h2>
              <p className="text-sm sm:text-base text-[#697386] leading-relaxed">
                Trabalhamos com metodologia criteriosa e ferramentas calibradas para diagnosticar falhas com exatidão, sem substituições desnecessárias de peças.
              </p>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div className="bg-white p-5 rounded-xl border border-[#E4E7EC]">
                  <div className="w-9 h-9 rounded-lg bg-[#F5F6F8] flex items-center justify-center text-[#D71920] mb-3">
                    <Eye className="w-5 h-5 text-[#D71920]" />
                  </div>
                  <h4 className="font-heading font-bold text-sm text-[#202124] mb-1">Atendimento transparente</h4>
                  <p className="text-xs text-[#697386] leading-relaxed">
                    Orçamento detalhado prévio, sem cobranças inesperadas ou custos ocultos.
                  </p>
                </div>

                <div className="bg-white p-5 rounded-xl border border-[#E4E7EC]">
                  <div className="w-9 h-9 rounded-lg bg-[#F5F6F8] flex items-center justify-center text-[#D71920] mb-3">
                    <Activity className="w-5 h-5 text-[#D71920]" />
                  </div>
                  <h4 className="font-heading font-bold text-sm text-[#202124] mb-1">Diagnóstico cuidadoso</h4>
                  <p className="text-xs text-[#697386] leading-relaxed">
                    Análise elétrica e lógica aprofundada antes de qualquer proposta de reparo.
                  </p>
                </div>

                <div className="bg-white p-5 rounded-xl border border-[#E4E7EC]">
                  <div className="w-9 h-9 rounded-lg bg-[#F5F6F8] flex items-center justify-center text-[#D71920] mb-3">
                    <MessageSquare className="w-5 h-5 text-[#D71920]" />
                  </div>
                  <h4 className="font-heading font-bold text-sm text-[#202124] mb-1">Comunicação clara</h4>
                  <p className="text-xs text-[#697386] leading-relaxed">
                    Linguagem acessível e explicações objetivas sobre o problema da sua máquina.
                  </p>
                </div>

                <div className="bg-white p-5 rounded-xl border border-[#E4E7EC]">
                  <div className="w-9 h-9 rounded-lg bg-[#F5F6F8] flex items-center justify-center text-[#D71920] mb-3">
                    <CheckCircle2 className="w-5 h-5 text-[#D71920]" />
                  </div>
                  <h4 className="font-heading font-bold text-sm text-[#202124] mb-1">Soluções adequadas</h4>
                  <p className="text-xs text-[#697386] leading-relaxed">
                    Foco no melhor custo-benefício e na longevidade do seu equipamento.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* 5. COMO FUNCIONA O ATENDIMENTO */}
      <section className="py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-8">
          <div className="text-center max-w-3xl mx-auto mb-16">
            <span className="text-xs font-bold uppercase tracking-wider text-[#D71920]">Fluxo Descomplicado</span>
            <h2 className="font-heading font-extrabold text-2xl sm:text-3xl lg:text-4xl text-[#202124] mt-2 mb-3">
              Atendimento simples, do primeiro contato à solução
            </h2>
            <p className="text-base text-[#697386]">
              Processo ágil e transparente para você acompanhar cada etapa do serviço com tranquilidade.
            </p>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div className="bg-[#F5F6F8] border border-[#E4E7EC] rounded-2xl p-6 flex flex-col justify-between">
              <div>
                <span className="w-10 h-10 rounded-xl bg-[#D71920] text-white font-heading font-extrabold text-sm flex items-center justify-center mb-4 shadow-xs">
                  01
                </span>
                <h3 className="font-heading font-bold text-base text-[#202124] mb-2">Entre em contato</h3>
                <p className="text-xs text-[#697386] leading-relaxed">
                  Fale conosco pelo WhatsApp ou envie uma mensagem através do formulário digital.
                </p>
              </div>
              <div className="mt-6 pt-3 border-t border-[#E4E7EC] text-[11px] text-[#697386] flex items-center gap-1.5 font-medium">
                <CheckCircle2 className="w-3.5 h-3.5 text-[#25D366]" />
                <span>Resposta ágil</span>
              </div>
            </div>

            <div className="bg-[#F5F6F8] border border-[#E4E7EC] rounded-2xl p-6 flex flex-col justify-between">
              <div>
                <span className="w-10 h-10 rounded-xl bg-white border border-[#E4E7EC] text-[#202124] font-heading font-extrabold text-sm flex items-center justify-center mb-4">
                  02
                </span>
                <h3 className="font-heading font-bold text-base text-[#202124] mb-2">Explique o problema</h3>
                <p className="text-xs text-[#697386] leading-relaxed">
                  Descreva o que está ocorrendo: lentidão, travamento, aquecimento ou falha ao ligar.
                </p>
              </div>
              <div className="mt-6 pt-3 border-t border-[#E4E7EC] text-[11px] text-[#697386] flex items-center gap-1.5 font-medium">
                <CheckCircle2 className="w-3.5 h-3.5 text-[#25D366]" />
                <span>Triagem inicial</span>
              </div>
            </div>

            <div className="bg-[#F5F6F8] border border-[#E4E7EC] rounded-2xl p-6 flex flex-col justify-between">
              <div>
                <span className="w-10 h-10 rounded-xl bg-white border border-[#E4E7EC] text-[#202124] font-heading font-extrabold text-sm flex items-center justify-center mb-4">
                  03
                </span>
                <h3 className="font-heading font-bold text-base text-[#202124] mb-2">Receba as orientações</h3>
                <p className="text-xs text-[#697386] leading-relaxed">
                  Apresentamos a análise técnica, opções de solução e orçamento claro com prazo estimado.
                </p>
              </div>
              <div className="mt-6 pt-3 border-t border-[#E4E7EC] text-[11px] text-[#697386] flex items-center gap-1.5 font-medium">
                <CheckCircle2 className="w-3.5 h-3.5 text-[#25D366]" />
                <span>Valores fixos sem surpresa</span>
              </div>
            </div>

            <div className="bg-[#F5F6F8] border border-[#E4E7EC] rounded-2xl p-6 flex flex-col justify-between">
              <div>
                <span className="w-10 h-10 rounded-xl bg-white border border-[#E4E7EC] text-[#202124] font-heading font-extrabold text-sm flex items-center justify-center mb-4">
                  04
                </span>
                <h3 className="font-heading font-bold text-base text-[#202124] mb-2">Autorize o serviço</h3>
                <p className="text-xs text-[#697386] leading-relaxed">
                  Execução do reparo em bancada com testes rigorosos de estabilidade e garantia de 90 dias.
                </p>
              </div>
              <div className="mt-6 pt-3 border-t border-[#E4E7EC] text-[11px] text-[#697386] flex items-center gap-1.5 font-medium">
                <CheckCircle2 className="w-3.5 h-3.5 text-[#25D366]" />
                <span>Garantia de 90 dias</span>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* 6. SOBRE A PC RESOLVE */}
      <section className="py-20 bg-[#F5F6F8] border-y border-[#E4E7EC]">
        <div className="max-w-7xl mx-auto px-4 sm:px-8">
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div className="lg:col-span-7 flex flex-col space-y-6">
              <span className="text-xs font-bold uppercase tracking-wider text-[#D71920]">Institucional</span>
              <h2 className="font-heading font-extrabold text-2xl sm:text-3xl lg:text-4xl text-[#202124] leading-tight">
                Tecnologia, cuidado e confiança em cada atendimento
              </h2>
              <div className="space-y-4 text-sm sm:text-base text-[#697386] leading-relaxed">
                <p>
                  A <strong className="text-[#202124]">{companySettings.name}</strong> atua em João Pessoa e região com a missão de oferecer uma assistência técnica diferenciada, que combina honestidade, rigor em bancada e total respeito pelos equipamentos e dados dos clientes.
                </p>
                <p>
                  Entendemos que computadores e notebooks são ferramentas essenciais de estudo, trabalho e vida pessoal. Por isso, mantemos um padrão de trabalho limpo, organizado e pautado pelo diagnóstico transparente.
                </p>
              </div>
              <div className="pt-2 flex flex-wrap items-center gap-4">
                <button
                  onClick={() => navigateTo('sobre')}
                  className="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white font-medium text-sm transition-all shadow-xs cursor-pointer"
                >
                  <span>Conheça nossa empresa</span>
                  <ArrowRight className="w-4 h-4" />
                </button>
                <a
                  href={whatsappUrl}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-white border border-[#E4E7EC] hover:bg-neutral-50 text-[#202124] font-medium text-sm transition-all"
                >
                  <MessageSquare className="w-4 h-4 text-[#25D366]" />
                  <span>Falar com a equipe</span>
                </a>
              </div>
            </div>

            <div className="lg:col-span-5">
              <div className="rounded-2xl overflow-hidden border border-[#E4E7EC] shadow-md bg-white">
                <img
                  src="https://lh3.googleusercontent.com/aida-public/AB6AXuDzQ6S_F71o4XH7pwUylwSTdj-NgkNBvlLFbtp_abAIyor_idUCTvrmSLjJ1oiG1Xsm3hdljifdml4ijzMxYS0QzpJAy9K_5PdJ3qRR6NMSRmPuzLpLeiY7oxWgRTcpjAfkoIaenMvgP3oMlVF-iONI43Wp4pG8TJEWAMSINweLC9ero_9Of3Xa3hc7NFv0v87yTuJ8piCUATReNCJELu2DsB_hE25xlWw9Tbe6bonRF53ZbNtlW4ZKVQ"
                  alt="Técnico em bancada com equipamento de segurança montando estação de trabalho"
                  className="w-full aspect-[4/3] object-cover"
                />
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* 7. CIDADES ATENDIDAS */}
      <section className="py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-8">
          <div className="text-center max-w-3xl mx-auto mb-16">
            <span className="text-xs font-bold uppercase tracking-wider text-[#D71920]">Cobertura Regional</span>
            <h2 className="font-heading font-extrabold text-2xl sm:text-3xl lg:text-4xl text-[#202124] mt-2 mb-3">
              Assistência técnica na Grande João Pessoa
            </h2>
            <p className="text-base text-[#697386]">
              Atendimento presencial e suporte corporativo nas cidades da região metropolitana:
            </p>
          </div>

          <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 gap-4 mb-8">
            {cities.map((city) => (
              <div
                key={city.id}
                className="p-5 rounded-xl border border-[#E4E7EC] bg-[#F5F6F8] flex items-center justify-between"
              >
                <div className="flex items-center gap-3">
                  <MapPin className="w-5 h-5 text-[#D71920] shrink-0" />
                  <span className="font-heading font-bold text-sm text-[#202124]">{city.name}</span>
                </div>
                <span className="text-[11px] text-[#697386] font-medium hidden sm:inline truncate max-w-[130px]">
                  {city.coverage.split('(')[0]}
                </span>
              </div>
            ))}
          </div>

          {/* Banner Leva e Traz */}
          <div className="p-6 rounded-2xl bg-[#F5F6F8] border border-[#E4E7EC] flex flex-col sm:flex-row items-center justify-between gap-4">
            <div className="flex items-center gap-4">
              <div className="w-12 h-12 rounded-xl bg-white border border-[#E4E7EC] flex items-center justify-center text-[#D71920] shrink-0">
                <Truck className="w-6 h-6 text-[#D71920]" />
              </div>
              <div>
                <h4 className="font-heading font-bold text-sm text-[#202124]">Serviço de Coleta e Entrega (Leva e Traz)</h4>
                <p className="text-xs text-[#697386] mt-0.5">
                  Consulte a disponibilidade de coleta e entrega ou atendimento corporativo no seu município.
                </p>
              </div>
            </div>
            <button
              onClick={() => navigateTo('areas')}
              className="px-5 py-2.5 rounded-lg bg-white hover:bg-neutral-100 text-[#202124] border border-[#E4E7EC] text-xs font-semibold shrink-0 transition-colors cursor-pointer"
            >
              Consultar Regras de Coleta
            </button>
          </div>
        </div>
      </section>

      {/* 8. AVALIAÇÕES REAIS */}
      <section className="py-20 bg-[#F5F6F8] border-y border-[#E4E7EC]">
        <div className="max-w-7xl mx-auto px-4 sm:px-8">
          <div className="flex flex-col sm:flex-row sm:items-end justify-between mb-12 gap-4">
            <div>
              <span className="text-xs font-bold uppercase tracking-wider text-[#D71920]">Experiência</span>
              <h2 className="font-heading font-extrabold text-2xl sm:text-3xl lg:text-4xl text-[#202124] mt-2">
                Avaliações de clientes atendidos
              </h2>
            </div>
            <div className="flex items-center gap-2 bg-white px-3.5 py-1.5 rounded-lg border border-[#E4E7EC]">
              <Star className="w-4 h-4 text-amber-500 fill-amber-500" />
              <span className="font-heading font-bold text-xs text-[#202124]">Avaliações verificadas</span>
            </div>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {approvedTestimonials.slice(0, 2).map((test) => (
              <div
                key={test.id}
                className="bg-white p-6 rounded-2xl border border-[#E4E7EC] shadow-xs flex flex-col justify-between"
              >
                <div>
                  <div className="flex items-center gap-1 text-amber-500 mb-3">
                    {[...Array(test.rating)].map((_, i) => (
                      <Star key={i} className="w-4 h-4 fill-amber-500 text-amber-500" />
                    ))}
                  </div>
                  <p className="text-xs text-[#697386] leading-relaxed italic">
                    "{test.text}"
                  </p>
                </div>
                <div className="mt-6 pt-4 border-t border-[#E4E7EC] flex items-center justify-between text-xs">
                  <span className="font-semibold text-[#202124]">{test.author}</span>
                  <span className="text-[#697386]">{test.location}</span>
                </div>
              </div>
            ))}

            {/* Convite a enviar feedback */}
            <div className="bg-white p-6 rounded-2xl border border-dashed border-[#E4E7EC] flex flex-col items-center justify-center text-center">
              <div className="w-12 h-12 rounded-full bg-[#F5F6F8] flex items-center justify-center text-[#D71920] mb-3">
                <Star className="w-6 h-6 text-[#D71920]" />
              </div>
              <h4 className="font-heading font-bold text-sm text-[#202124] mb-1">Já é nosso cliente?</h4>
              <p className="text-xs text-[#697386] max-w-xs mb-4">
                Sua avaliação é publicada após a conclusão e entrega do equipamento.
              </p>
              <a
                href={`https://wa.me/55${cleanPhone}?text=${encodeURIComponent(
                  'Olá! Gostaria de enviar meu feedback sobre o atendimento que recebi.'
                )}`}
                target="_blank"
                rel="noopener noreferrer"
                className="text-xs font-semibold text-[#D71920] hover:underline"
              >
                Enviar feedback pelo WhatsApp &rarr;
              </a>
            </div>
          </div>
        </div>
      </section>

      {/* 9. FAQ ACORDEÃO */}
      <section className="py-20 bg-white">
        <div className="max-w-4xl mx-auto px-4 sm:px-8">
          <div className="text-center mb-16">
            <span className="text-xs font-bold uppercase tracking-wider text-[#D71920]">Dúvidas Frequentes</span>
            <h2 className="font-heading font-extrabold text-2xl sm:text-3xl text-[#202124] mt-2 mb-3">
              Perguntas Frequentes
            </h2>
            <p className="text-sm text-[#697386]">Tire suas dúvidas antes de solicitar seu atendimento</p>
          </div>

          <div className="space-y-3">
            {faqs.map((faq) => (
              <details
                key={faq.id}
                className="group bg-[#F5F6F8] border border-[#E4E7EC] rounded-xl p-5 [&_svg]:open:-rotate-180 transition-all"
              >
                <summary className="flex items-center justify-between cursor-pointer font-heading font-bold text-sm text-[#202124] list-none">
                  <span>{faq.question}</span>
                  <ChevronDown className="w-4 h-4 text-[#697386] transition-transform duration-200" />
                </summary>
                <p className="text-xs text-[#697386] mt-3 leading-relaxed border-t border-[#E4E7EC]/60 pt-3">
                  {faq.answer}
                </p>
              </details>
            ))}
          </div>
        </div>
      </section>

      {/* 10. FORMULÁRIO DE ORÇAMENTO COM PERSISTÊNCIA REAL */}
      <section id="contato" className="py-20 bg-[#F5F6F8] border-t border-[#E4E7EC]">
        <div className="max-w-7xl mx-auto px-4 sm:px-8">
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-12">
            {/* Contact details */}
            <div className="lg:col-span-5 flex flex-col space-y-6">
              <div>
                <span className="text-xs font-bold uppercase tracking-wider text-[#D71920]">Fale Conosco</span>
                <h2 className="font-heading font-extrabold text-2xl sm:text-3xl text-[#202124] mt-2 mb-3">
                  Canais de Atendimento
                </h2>
                <p className="text-sm text-[#697386] leading-relaxed">
                  Estamos prontos para atender você presencialmente ou responder suas dúvidas técnicas online.
                </p>
              </div>

              <div className="space-y-4 pt-2">
                <div className="flex items-start gap-3 p-4 rounded-xl bg-white border border-[#E4E7EC]">
                  <Phone className="w-5 h-5 text-[#D71920] shrink-0 mt-0.5" />
                  <div>
                    <h4 className="font-heading font-bold text-xs text-[#202124]">Telefone Fixo</h4>
                    <p className="text-xs text-[#697386] mt-0.5">{companySettings.phone}</p>
                  </div>
                </div>

                <div className="flex items-start gap-3 p-4 rounded-xl bg-white border border-[#E4E7EC]">
                  <div className="w-5 h-5 flex items-center justify-center text-[#25D366] font-bold text-sm shrink-0">
                    W
                  </div>
                  <div>
                    <h4 className="font-heading font-bold text-xs text-[#202124]">WhatsApp Técnico</h4>
                    <p className="text-xs text-[#697386] mt-0.5">{companySettings.whatsapp}</p>
                  </div>
                </div>

                <div className="flex items-start gap-3 p-4 rounded-xl bg-white border border-[#E4E7EC]">
                  <Clock className="w-5 h-5 text-[#697386] shrink-0 mt-0.5" />
                  <div>
                    <h4 className="font-heading font-bold text-xs text-[#202124]">Horário de Atendimento</h4>
                    <p className="text-xs text-[#697386] mt-0.5">
                      {companySettings.workingHoursWeekday} | {companySettings.workingHoursSaturday}
                    </p>
                  </div>
                </div>

                <div className="flex items-start gap-3 p-4 rounded-xl bg-white border border-[#E4E7EC]">
                  <MapPin className="w-5 h-5 text-[#D71920] shrink-0 mt-0.5" />
                  <div>
                    <h4 className="font-heading font-bold text-xs text-[#202124]">Localização</h4>
                    <p className="text-xs text-[#697386] mt-0.5">
                      {companySettings.address} ({companySettings.city} - {companySettings.state})
                    </p>
                  </div>
                </div>
              </div>
            </div>

            {/* The Form */}
            <div className="lg:col-span-7 bg-white p-6 sm:p-8 rounded-2xl border border-[#E4E7EC] shadow-sm">
              <h3 className="font-heading font-bold text-lg text-[#202124] mb-1">
                Solicitar Orçamento Técnico
              </h3>
              <p className="text-xs text-[#697386] mb-6">
                Preencha os campos abaixo com os detalhes da máquina para retorno com o pré-diagnóstico.
              </p>

              {formSubmittedProtocol ? (
                <div className="p-6 rounded-xl bg-emerald-50 border border-emerald-200 text-center space-y-4 animate-in fade-in">
                  <div className="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto">
                    <CheckCircle2 className="w-6 h-6" />
                  </div>
                  <div>
                    <h4 className="font-heading font-bold text-base text-emerald-950">
                      Solicitação Registrada com Sucesso!
                    </h4>
                    <p className="text-xs text-emerald-800 mt-1">
                      Seu protocolo de atendimento é{' '}
                      <span className="font-mono font-bold bg-white px-2 py-0.5 rounded border border-emerald-300">
                        {formSubmittedProtocol}
                      </span>
                    </p>
                    <p className="text-xs text-emerald-700 mt-2">
                      Nossa equipe técnica já recebeu as informações e entrará em contato via WhatsApp em poucos minutos.
                    </p>
                  </div>
                  <div className="flex flex-wrap items-center justify-center gap-3 pt-2">
                    <button
                      onClick={() => setFormSubmittedProtocol(null)}
                      className="px-4 py-2 rounded-lg bg-white border border-emerald-300 text-xs font-semibold text-emerald-900 hover:bg-emerald-50 transition-colors cursor-pointer"
                    >
                      Enviar Outra Solicitação
                    </button>
                    <button
                      onClick={() => navigateTo('admin-solicitacoes')}
                      className="px-4 py-2 rounded-lg bg-emerald-700 text-white text-xs font-semibold hover:bg-emerald-800 transition-colors cursor-pointer"
                    >
                      Ver no Painel Administrativo
                    </button>
                  </div>
                </div>
              ) : (
                <form onSubmit={handleFormSubmit} className="space-y-4">
                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                      <label className="block text-xs font-semibold text-[#202124] mb-1.5">
                        Nome completo *
                      </label>
                      <input
                        type="text"
                        required
                        value={formData.customerName}
                        onChange={(e) => setFormData({ ...formData, customerName: e.target.value })}
                        placeholder="Ex: Carlos Eduardo"
                        className="w-full px-3.5 py-2.5 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]"
                      />
                    </div>
                    <div>
                      <label className="block text-xs font-semibold text-[#202124] mb-1.5">
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
                      <label className="block text-xs font-semibold text-[#202124] mb-1.5">
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
                        <option value="Outra">Outro município da região</option>
                      </select>
                    </div>

                    <div>
                      <label className="block text-xs font-semibold text-[#202124] mb-1.5">
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
                        <option value="desktop">Computador Desktop / PC Gamer</option>
                        <option value="all-in-one">All-in-One</option>
                        <option value="macbook">Apple MacBook</option>
                        <option value="corporativo">Parque de máquinas / Empresa</option>
                        <option value="outro">Outro dispositivo</option>
                      </select>
                    </div>
                  </div>

                  <div>
                    <label className="block text-xs font-semibold text-[#202124] mb-1.5">
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

                  <div>
                    <label className="block text-xs font-semibold text-[#202124] mb-1.5">
                      Descrição do problema ou sintomas
                    </label>
                    <textarea
                      rows={3}
                      value={formData.description}
                      onChange={(e) => setFormData({ ...formData, description: e.target.value })}
                      placeholder="Ex: O notebook liga mas a tela fica preta e o cooler gira muito forte após alguns segundos..."
                      className="w-full px-3.5 py-2.5 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]"
                    />
                  </div>

                  <div className="flex items-start gap-2 pt-1">
                    <input
                      type="checkbox"
                      id="consent"
                      checked={formData.consent}
                      onChange={(e) => setFormData({ ...formData, consent: e.target.checked })}
                      className="mt-0.5 accent-[#D71920]"
                    />
                    <label htmlFor="consent" className="text-[11px] text-[#697386] leading-tight">
                      Concordo com o envio das informações para contato técnico e orçamento, em conformidade com as diretrizes de privacidade e LGPD.
                    </label>
                  </div>

                  <button
                    type="submit"
                    className="w-full py-3.5 px-6 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white font-semibold text-xs tracking-wide transition-all shadow-xs flex items-center justify-center gap-2 cursor-pointer"
                  >
                    <Send className="w-4 h-4" />
                    <span>Enviar solicitação de atendimento</span>
                  </button>
                </form>
              )}
            </div>
          </div>
        </div>
      </section>
    </main>
  );
};
