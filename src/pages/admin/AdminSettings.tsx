import React, { useState } from 'react';
import { useApp } from '../../context/AppContext';
import { AdminLayout } from '../../components/admin/AdminLayout';
import {
  Settings,
  Building,
  Globe,
  MessageSquare,
  ShieldCheck,
  Save,
  RotateCcw,
  CheckCircle2,
} from 'lucide-react';

export const AdminSettings: React.FC = () => {
  const { companySettings, updateCompanySettings, resetAllData, showToast } = useApp();

  const [activeTab, setActiveTab] = useState<'empresa' | 'seo' | 'whatsapp' | 'privacidade'>('empresa');

  // Form states initialized with context
  const [formData, setFormData] = useState({ ...companySettings });

  const handleChange = (field: keyof typeof companySettings, value: string) => {
    setFormData((prev) => ({ ...prev, [field]: value }));
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    updateCompanySettings(formData);
  };

  const handleResetData = () => {
    if (confirm('Tem certeza que deseja restaurar os dados do sistema para a demonstração inicial?')) {
      resetAllData();
      setFormData({ ...companySettings });
    }
  };

  return (
    <AdminLayout
      title="Configurações Gerais do Sistema"
      subtitle="Defina os parâmetros corporativos da empresa, identificadores de SEO e integrações"
    >
      <div className="bg-white rounded-2xl border border-[#E4E7EC] shadow-2xs overflow-hidden">
        {/* Tab Navigation */}
        <div className="flex items-center gap-2 border-b border-[#E4E7EC] px-6 pt-3 bg-[#F5F6F8] overflow-x-auto">
          {[
            { key: 'empresa', label: 'Dados da Empresa', icon: <Building className="w-4 h-4" /> },
            { key: 'seo', label: 'SEO & Rastreamento (Google)', icon: <Globe className="w-4 h-4" /> },
            { key: 'whatsapp', label: 'Mensagens & WhatsApp', icon: <MessageSquare className="w-4 h-4" /> },
            { key: 'privacidade', label: 'Privacidade & LGPD', icon: <ShieldCheck className="w-4 h-4" /> },
          ].map((tab) => (
            <button
              key={tab.key}
              onClick={() => setActiveTab(tab.key as any)}
              className={`flex items-center gap-2 px-4 py-3 text-xs font-semibold border-b-2 transition-all cursor-pointer whitespace-nowrap ${
                activeTab === tab.key
                  ? 'border-[#D71920] text-[#D71920] bg-white rounded-t-xl shadow-2xs'
                  : 'border-transparent text-[#697386] hover:text-[#202124]'
              }`}
            >
              <span>{tab.icon}</span>
              <span>{tab.label}</span>
            </button>
          ))}
        </div>

        {/* Tab Contents */}
        <form onSubmit={handleSubmit} className="p-6 space-y-6 text-xs">
          {activeTab === 'empresa' && (
            <div className="space-y-4">
              <div className="pb-3 border-b border-[#E4E7EC]">
                <h3 className="font-heading font-bold text-base text-[#202124]">
                  Identificação Institucional &amp; Comercial
                </h3>
                <p className="text-xs text-[#697386]">
                  Essas informações são refletidas no cabeçalho, rodapé e nos termos do site
                </p>
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-semibold text-[#202124] mb-1">
                    Nome Fantasia *
                  </label>
                  <input
                    type="text"
                    required
                    value={formData.name}
                    onChange={(e) => handleChange('name', e.target.value)}
                    className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white"
                  />
                </div>
                <div>
                  <label className="block text-xs font-semibold text-[#202124] mb-1">
                    Razão Social
                  </label>
                  <input
                    type="text"
                    value={formData.corporateName}
                    onChange={(e) => handleChange('corporateName', e.target.value)}
                    className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white"
                  />
                </div>
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                  <label className="block text-xs font-semibold text-[#202124] mb-1">
                    CNPJ
                  </label>
                  <input
                    type="text"
                    value={formData.cnpj}
                    onChange={(e) => handleChange('cnpj', e.target.value)}
                    className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white"
                  />
                </div>
                <div>
                  <label className="block text-xs font-semibold text-[#202124] mb-1">
                    Telefone Fixo *
                  </label>
                  <input
                    type="text"
                    required
                    value={formData.phone}
                    onChange={(e) => handleChange('phone', e.target.value)}
                    className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white"
                  />
                </div>
                <div>
                  <label className="block text-xs font-semibold text-[#202124] mb-1">
                    WhatsApp Comercial *
                  </label>
                  <input
                    type="text"
                    required
                    value={formData.whatsapp}
                    onChange={(e) => handleChange('whatsapp', e.target.value)}
                    className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white"
                  />
                </div>
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div className="sm:col-span-2">
                  <label className="block text-xs font-semibold text-[#202124] mb-1">
                    Endereço Completo
                  </label>
                  <input
                    type="text"
                    value={formData.address}
                    onChange={(e) => handleChange('address', e.target.value)}
                    className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white"
                  />
                </div>
                <div>
                  <label className="block text-xs font-semibold text-[#202124] mb-1">
                    Bairro / Cidade
                  </label>
                  <input
                    type="text"
                    value={`${formData.neighborhood} - ${formData.city}/${formData.state}`}
                    onChange={(e) => handleChange('neighborhood', e.target.value)}
                    className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white"
                  />
                </div>
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-semibold text-[#202124] mb-1">
                    Horário de Atendimento (Segunda a Sexta)
                  </label>
                  <input
                    type="text"
                    value={formData.workingHoursWeekday}
                    onChange={(e) => handleChange('workingHoursWeekday', e.target.value)}
                    className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white"
                  />
                </div>
                <div>
                  <label className="block text-xs font-semibold text-[#202124] mb-1">
                    Horário de Atendimento (Sábado)
                  </label>
                  <input
                    type="text"
                    value={formData.workingHoursSaturday}
                    onChange={(e) => handleChange('workingHoursSaturday', e.target.value)}
                    className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white"
                  />
                </div>
              </div>

              <div>
                <label className="block text-xs font-semibold text-[#202124] mb-1">
                  Resumo Institucional (Bio)
                </label>
                <textarea
                  rows={3}
                  value={formData.aboutText}
                  onChange={(e) => handleChange('aboutText', e.target.value)}
                  className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white"
                />
              </div>
            </div>
          )}

          {activeTab === 'seo' && (
            <div className="space-y-4">
              <div className="pb-3 border-b border-[#E4E7EC]">
                <h3 className="font-heading font-bold text-base text-[#202124]">
                  SEO &amp; Rastreamento Google
                </h3>
                <p className="text-xs text-[#697386]">
                  Configure os identificadores oficiais do Google Search Console, Google Analytics 4 e Google Tag Manager
                </p>
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                  <label className="block text-xs font-semibold text-[#202124] mb-1">
                    Google Analytics 4 ID
                  </label>
                  <input
                    type="text"
                    placeholder="G-XXXXXXXXXX"
                    value={formData.ga4MeasurementId}
                    onChange={(e) => handleChange('ga4MeasurementId', e.target.value)}
                    className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white font-mono"
                  />
                </div>

                <div>
                  <label className="block text-xs font-semibold text-[#202124] mb-1">
                    Google Tag Manager Container
                  </label>
                  <input
                    type="text"
                    placeholder="GTM-XXXXXXX"
                    value={formData.gtmContainerId}
                    onChange={(e) => handleChange('gtmContainerId', e.target.value)}
                    className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white font-mono"
                  />
                </div>

                <div>
                  <label className="block text-xs font-semibold text-[#202124] mb-1">
                    Google Ads Conversion ID
                  </label>
                  <input
                    type="text"
                    placeholder="AW-1122334455"
                    value={formData.googleAdsConversionId}
                    onChange={(e) => handleChange('googleAdsConversionId', e.target.value)}
                    className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white font-mono"
                  />
                </div>
              </div>

              <div>
                <label className="block text-xs font-semibold text-[#202124] mb-1">
                  Meta Title Padrão (Título no Google)
                </label>
                <input
                  type="text"
                  value={formData.metaTitleDefault}
                  onChange={(e) => handleChange('metaTitleDefault', e.target.value)}
                  className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white"
                />
              </div>

              <div>
                <label className="block text-xs font-semibold text-[#202124] mb-1">
                  Meta Description Padrão
                </label>
                <textarea
                  rows={2}
                  value={formData.metaDescriptionDefault}
                  onChange={(e) => handleChange('metaDescriptionDefault', e.target.value)}
                  className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white"
                />
              </div>
            </div>
          )}

          {activeTab === 'whatsapp' && (
            <div className="space-y-4">
              <div className="pb-3 border-b border-[#E4E7EC]">
                <h3 className="font-heading font-bold text-base text-[#202124]">
                  Comunicação &amp; Automação WhatsApp
                </h3>
                <p className="text-xs text-[#697386]">
                  Personalize os modelos de texto enviados aos clientes em cada estágio do atendimento
                </p>
              </div>

              <div>
                <label className="block text-xs font-semibold text-[#202124] mb-1">
                  Mensagem Padrão do Botão Flutuante
                </label>
                <input
                  type="text"
                  value={formData.defaultWhatsappMessage}
                  onChange={(e) => handleChange('defaultWhatsappMessage', e.target.value)}
                  className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white"
                />
              </div>

              <div className="p-4 rounded-xl bg-[#F5F6F8] border border-[#E4E7EC] space-y-2">
                <span className="font-bold text-[#202124] block">💡 Dica de Comunicação:</span>
                <p className="text-[#697386]">
                  Ao clicar no botão "Notificar no WhatsApp" dentro do painel de Ordens de Serviço, o sistema preencherá automaticamente o nome do cliente, protocolo e o valor aprovado do reparo.
                </p>
              </div>
            </div>
          )}

          {activeTab === 'privacidade' && (
            <div className="space-y-4">
              <div className="pb-3 border-b border-[#E4E7EC]">
                <h3 className="font-heading font-bold text-base text-[#202124]">
                  Privacidade, Garantia &amp; LGPD
                </h3>
                <p className="text-xs text-[#697386]">
                  Termos legais de garantia de 90 dias e política de retenção de dados
                </p>
              </div>

              <div>
                <label className="block text-xs font-semibold text-[#202124] mb-1">
                  Termo Padrão de Garantia Técnica
                </label>
                <textarea
                  rows={3}
                  value={formData.warrantyTerm}
                  onChange={(e) => handleChange('warrantyTerm', e.target.value)}
                  className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white"
                />
              </div>
            </div>
          )}

          {/* Action Buttons */}
          <div className="pt-4 border-t border-[#E4E7EC] flex flex-wrap items-center justify-between gap-4">
            <button
              type="button"
              onClick={handleResetData}
              className="px-4 py-2 rounded-lg border border-neutral-300 text-neutral-600 hover:bg-neutral-50 transition-colors inline-flex items-center gap-1.5 cursor-pointer"
            >
              <RotateCcw className="w-3.5 h-3.5" />
              <span>Restaurar Padrão de Demonstração</span>
            </button>

            <button
              type="submit"
              className="px-6 py-2.5 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold shadow-xs transition-colors inline-flex items-center gap-2 cursor-pointer"
            >
              <Save className="w-3.5 h-3.5" />
              <span>Salvar Configurações</span>
            </button>
          </div>
        </form>
      </div>
    </AdminLayout>
  );
};
