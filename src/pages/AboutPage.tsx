import React from 'react';
import { useApp } from '../context/AppContext';
import {
  ShieldCheck,
  CheckCircle2,
  Sliders,
  Award,
  Users,
  Building,
  ArrowRight,
  Clock,
  Phone,
} from 'lucide-react';

export const AboutPage: React.FC = () => {
  const { companySettings, navigateTo } = useApp();

  const cleanPhone = companySettings.whatsapp.replace(/\D/g, '');
  const whatsappUrl = `https://wa.me/55${cleanPhone}?text=${encodeURIComponent(
    'Olá! Gostaria de saber mais sobre os serviços da PC Resolve.'
  )}`;

  return (
    <div className="bg-white py-12 lg:py-16">
      <div className="max-w-7xl mx-auto px-4 sm:px-8 space-y-16">
        {/* Intro Hero */}
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
          <div className="lg:col-span-7 space-y-6">
            <span className="text-xs font-bold uppercase tracking-wider text-[#D71920]">
              Institucional
            </span>
            <h1 className="font-heading font-extrabold text-3xl sm:text-4xl text-[#202124] tracking-tight">
              Tecnologia, rigor técnico e transparência na Grande João Pessoa
            </h1>
            <p className="text-base text-[#697386] leading-relaxed">
              A <strong>PC Resolve</strong> nasceu da necessidade de entregar aos paraibanos um serviço de informática que rompesse com o amadorismo e com diagnósticos nebulosos. Acreditamos que a confiança se constrói com bancadas organizadas, instrumentação de ponta e respeito irrestrito aos dados dos nossos clientes.
            </p>
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
              <div className="p-4 rounded-xl bg-[#F5F6F8] border border-[#E4E7EC]">
                <ShieldCheck className="w-6 h-6 text-[#D71920] mb-2" />
                <h4 className="font-heading font-bold text-sm text-[#202124]">Bancada Técnica ESD</h4>
                <p className="text-xs text-[#697386] mt-1">
                  Proteção anti-estática em todas as etapas para evitar danos ocultos a processadores e placas-mãe.
                </p>
              </div>
              <div className="p-4 rounded-xl bg-[#F5F6F8] border border-[#E4E7EC]">
                <Award className="w-6 h-6 text-[#D71920] mb-2" />
                <h4 className="font-heading font-bold text-sm text-[#202124]">Garantia Formal de 90 Dias</h4>
                <p className="text-xs text-[#697386] mt-1">
                  Emissão de Ordem de Serviço com checklist de entrada e saída conforme o CDC art. 26.
                </p>
              </div>
            </div>
          </div>

          <div className="lg:col-span-5">
            <div className="rounded-2xl overflow-hidden border border-[#E4E7EC] shadow-xl bg-neutral-100">
              <img
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuDfSe_gwOY_AdVah5Xm_Sn-sD2ei8fnIMOHmB1KdXZ8JTdndu6vUePLLl16K1iI-5WPIVT2ZuqkXg3cvpNbZ6XPH79gF4jqUSq8qLXVky0xZI5RAKqVbMCVqI4cYQyuoUh3hTeAXlHRqAwCQ4qDaZanaVWrg0Z2QGXvPCwrUG7GvTTJeEzGtbxp3C4dvXCnPIxZ7OuD6eRTGHQNs35LBR8Pot62ON4jwvZUhuN0muvfAyJKuU6p05awwg"
                alt="Ambiente laboratorial de manutenção técnica da PC Resolve"
                className="w-full aspect-[4/3] object-cover"
              />
            </div>
          </div>
        </div>

        {/* Pillars / Values */}
        <div className="bg-[#F5F6F8] rounded-3xl p-8 sm:p-12 border border-[#E4E7EC]">
          <div className="max-w-3xl mb-8">
            <span className="text-xs font-bold uppercase tracking-wider text-[#D71920]">Nossos Pilares</span>
            <h2 className="font-heading font-extrabold text-2xl sm:text-3xl text-[#202124] mt-1">
              Como trabalhamos em cada equipamento
            </h2>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div className="bg-white p-6 rounded-2xl border border-[#E4E7EC] space-y-3">
              <div className="w-10 h-10 rounded-xl bg-[#F5F6F8] flex items-center justify-center text-[#D71920] font-bold">
                1
              </div>
              <h3 className="font-heading font-bold text-base text-[#202124]">Diagnóstico Honesto</h3>
              <p className="text-xs text-[#697386] leading-relaxed">
                Antes de condenar uma placa ou cobrar por peças que não necessitam de troca, realizamos testes elétricos detalhados em bancada. Se for um capacitor ou fusível em curto, nós consertamos o componente.
              </p>
            </div>

            <div className="bg-white p-6 rounded-2xl border border-[#E4E7EC] space-y-3">
              <div className="w-10 h-10 rounded-xl bg-[#F5F6F8] flex items-center justify-center text-[#D71920] font-bold">
                2
              </div>
              <h3 className="font-heading font-bold text-base text-[#202124]">Proteção de Dados &amp; LGPD</h3>
              <p className="text-xs text-[#697386] leading-relaxed">
                Fotos, documentos de trabalho, processos jurídicos e prontuários médicos são tratados com sigilo absoluto. Não vasculhamos arquivos e seguimos diretrizes formais de privacidade.
              </p>
            </div>

            <div className="bg-white p-6 rounded-2xl border border-[#E4E7EC] space-y-3">
              <div className="w-10 h-10 rounded-xl bg-[#F5F6F8] flex items-center justify-center text-[#D71920] font-bold">
                3
              </div>
              <h3 className="font-heading font-bold text-base text-[#202124]">Materiais de Primeira Linha</h3>
              <p className="text-xs text-[#697386] leading-relaxed">
                Utilizamos pastas térmicas internacionais com alto índice de condutividade (Arctic MX-4 e MX-6), álcool isopropílico 99.8% e soldas com fluxo profissional para assegurar durabilidade extrema.
              </p>
            </div>
          </div>
        </div>

        {/* Gallery / Bancada */}
        <div className="space-y-6">
          <div className="text-center max-w-2xl mx-auto">
            <span className="text-xs font-bold uppercase tracking-wider text-[#D71920]">Infraestrutura</span>
            <h2 className="font-heading font-extrabold text-2xl sm:text-3xl text-[#202124] mt-1">
              Laboratório Técnico em João Pessoa
            </h2>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div className="rounded-2xl overflow-hidden border border-[#E4E7EC] bg-neutral-100 group">
              <img
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuDL8KGLk9WBdURz4Vfpz1xflvMhnQr7PDMYyZNghKrCt2Nlmy2DObTEtVSRJjsIWN-_gKRyD529_owAQ7szWMEdzUhURy6Wxxlj7m84aNKjYm3h2YkHWXA7oP2ksErYHWSziawRC4pSQNQGCloJdmfgPa_hYL752-f1gV3bgVRysl-qrD8XmiRogStVppbkvcNRiRSVkAac4DM_ohWHRr8P4NBFdQ_ECz4YVRiLbUNOReTNDNtYdn0okg"
                alt="Aplicação de pasta térmica na placa-mãe"
                className="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-500"
              />
              <div className="p-4 bg-white border-t border-[#E4E7EC]">
                <h4 className="font-heading font-bold text-xs text-[#202124]">Substituição Térmica Criteriosa</h4>
                <p className="text-[11px] text-[#697386] mt-0.5">Compostos térmicos premium para refrigeração máxima</p>
              </div>
            </div>

            <div className="rounded-2xl overflow-hidden border border-[#E4E7EC] bg-neutral-100 group">
              <img
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuDzQ6S_F71o4XH7pwUylwSTdj-NgkNBvlLFbtp_abAIyor_idUCTvrmSLjJ1oiG1Xsm3hdljifdml4ijzMxYS0QzpJAy9K_5PdJ3qRR6NMSRmPuzLpLeiY7oxWgRTcpjAfkoIaenMvgP3oMlVF-iONI43Wp4pG8TJEWAMSINweLC9ero_9Of3Xa3hc7NFv0v87yTuJ8piCUATReNCJELu2DsB_hE25xlWw9Tbe6bonRF53ZbNtlW4ZKVQ"
                alt="Montagem de workstation e organização de cabos"
                className="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-500"
              />
              <div className="p-4 bg-white border-t border-[#E4E7EC]">
                <h4 className="font-heading font-bold text-xs text-[#202124]">Cable Management &amp; Air-Flow</h4>
                <p className="text-[11px] text-[#697386] mt-0.5">Montagem limpa e fluxo de ar calibrado para desktops</p>
              </div>
            </div>

            <div className="rounded-2xl overflow-hidden border border-[#E4E7EC] bg-neutral-100 group">
              <img
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuDfSe_gwOY_AdVah5Xm_Sn-sD2ei8fnIMOHmB1KdXZ8JTdndu6vUePLLl16K1iI-5WPIVT2ZuqkXg3cvpNbZ6XPH79gF4jqUSq8qLXVky0xZI5RAKqVbMCVqI4cYQyuoUh3hTeAXlHRqAwCQ4qDaZanaVWrg0Z2QGXvPCwrUG7GvTTJeEzGtbxp3C4dvXCnPIxZ7OuD6eRTGHQNs35LBR8Pot62ON4jwvZUhuN0muvfAyJKuU6p05awwg"
                alt="Reparo de componentes e diagnóstico de notebooks"
                className="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-500"
              />
              <div className="p-4 bg-white border-t border-[#E4E7EC]">
                <h4 className="font-heading font-bold text-xs text-[#202124]">Diagnóstico de Dobradiças e Telas</h4>
                <p className="text-[11px] text-[#697386] mt-0.5">Recuperação estrutural sem danificar carcaças</p>
              </div>
            </div>
          </div>
        </div>

        {/* CTA Banner */}
        <div className="bg-[#D71920] rounded-3xl p-8 sm:p-12 text-white flex flex-col md:flex-row items-center justify-between gap-6 shadow-xl">
          <div className="space-y-2 text-center md:text-left">
            <h3 className="font-heading font-extrabold text-2xl sm:text-3xl">
              Agende uma visita ou traga seu equipamento
            </h3>
            <p className="text-white/80 text-sm max-w-xl">
              Atendimento com hora marcada ou serviço de coleta e entrega (Leva e Traz) na Grande João Pessoa.
            </p>
          </div>
          <div className="flex flex-wrap items-center gap-3 shrink-0">
            <a
              href={whatsappUrl}
              target="_blank"
              rel="noopener noreferrer"
              className="px-6 py-3.5 rounded-xl bg-white text-[#D71920] hover:bg-neutral-100 font-bold text-xs shadow-md transition-colors"
            >
              Falar no WhatsApp
            </a>
            <button
              onClick={() => navigateTo('contato')}
              className="px-6 py-3.5 rounded-xl bg-[#A90F17] hover:bg-black/30 text-white font-bold text-xs transition-colors cursor-pointer"
            >
              Enviar Formulário
            </button>
          </div>
        </div>
      </div>
    </div>
  );
};
