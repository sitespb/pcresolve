import React from 'react';
import { useApp } from '../context/AppContext';
import { ShieldCheck, FileText, ArrowLeft } from 'lucide-react';

interface LegalPageProps {
  type: 'termos' | 'privacidade';
}

export const LegalPage: React.FC<LegalPageProps> = ({ type }) => {
  const { companySettings, navigateTo } = useApp();

  const isPrivacy = type === 'privacidade';

  return (
    <div className="bg-white py-12 lg:py-16">
      <div className="max-w-4xl mx-auto px-4 sm:px-8 space-y-8">
        <button
          onClick={() => navigateTo('home')}
          className="inline-flex items-center gap-2 text-xs font-semibold text-[#697386] hover:text-[#D71920] transition-colors cursor-pointer"
        >
          <ArrowLeft className="w-4 h-4" />
          <span>Voltar para a página inicial</span>
        </button>

        <div className="border-b border-[#E4E7EC] pb-6">
          <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#F5F6F8] text-xs font-semibold text-[#D71920] mb-2">
            {isPrivacy ? <ShieldCheck className="w-3.5 h-3.5" /> : <FileText className="w-3.5 h-3.5" />}
            <span>Documento Legal Oficial</span>
          </div>
          <h1 className="font-heading font-extrabold text-2xl sm:text-3xl text-[#202124]">
            {isPrivacy ? 'Política de Privacidade e Proteção de Dados (LGPD)' : 'Termos de Uso e Condições de Atendimento'}
          </h1>
          <p className="text-xs text-[#697386] mt-2">
            Última atualização: Outubro de 2026 • {companySettings.name} (CNPJ: {companySettings.cnpj})
          </p>
        </div>

        {isPrivacy ? (
          <div className="prose prose-sm text-xs sm:text-sm text-[#697386] space-y-6 leading-relaxed">
            <section className="space-y-2">
              <h2 className="font-heading font-bold text-base text-[#202124]">1. Introdução e Compromisso</h2>
              <p>
                A <strong>{companySettings.corporateName}</strong>, inscrita no CNPJ sob o nº {companySettings.cnpj}, com sede em {companySettings.address}, João Pessoa - PB, valoriza a privacidade e a segurança das informações de seus clientes e visitantes. Este documento esclarece as práticas de tratamento de dados conforme a Lei Geral de Proteção de Dados Pessoais (Lei Federal nº 13.709/2018 - LGPD).
              </p>
            </section>

            <section className="space-y-2">
              <h2 className="font-heading font-bold text-base text-[#202124]">2. Coleta Mínima de Dados</h2>
              <p>
                Coletamos estritamente os dados essenciais para identificação do cliente e execução do diagnóstico técnico:
              </p>
              <ul className="list-disc pl-5 space-y-1">
                <li>Nome completo;</li>
                <li>Número de telefone / WhatsApp;</li>
                <li>Município de atendimento;</li>
                <li>Descrição dos defeitos e histórico técnico do equipamento.</li>
              </ul>
            </section>

            <section className="space-y-2">
              <h2 className="font-heading font-bold text-base text-[#202124]">3. Sigilo Técnico e Arquivos do Cliente</h2>
              <p>
                A PC Resolve adota política rígida de inviolabilidade dos dados contidos nos discos rígidos (HDs, SSDs e pendrives) deixados para manutenção. Nossos técnicos realizam diagnósticos estritamente em nível de hardware, sistema operacional e testes de integridade. Em caso de necessidade de backup, os arquivos são clonados em ambiente seguro e descartados após confirmação de entrega ao cliente.
              </p>
            </section>

            <section className="space-y-2">
              <h2 className="font-heading font-bold text-base text-[#202124]">4. Cookies e Tecnologias de Medição</h2>
              <p>
                Utilizamos cookies técnicos essenciais para navegação e tags analíticas do Google Analytics para compreender os canais de busca e aprimorar o carregamento do site na Paraíba. O usuário pode desativar cookies a qualquer momento através do seu navegador.
              </p>
            </section>

            <section className="space-y-2">
              <h2 className="font-heading font-bold text-base text-[#202124]">5. Direitos do Titular</h2>
              <p>
                O titular dos dados pode solicitar confirmação de existência de tratamento, correção de dados incompletos ou eliminação de seus cadastros mediante contato pelo e-mail <strong>{companySettings.email}</strong>.
              </p>
            </section>
          </div>
        ) : (
          <div className="prose prose-sm text-xs sm:text-sm text-[#697386] space-y-6 leading-relaxed">
            <section className="space-y-2">
              <h2 className="font-heading font-bold text-base text-[#202124]">1. Condições Gerais do Atendimento</h2>
              <p>
                Os presentes termos regulamentam a prestação de serviços de diagnóstico, manutenção preventiva, corretiva e consultoria em informática pela <strong>{companySettings.corporateName}</strong>.
              </p>
            </section>

            <section className="space-y-2">
              <h2 className="font-heading font-bold text-base text-[#202124]">2. Entrada e Checklist de Equipamento</h2>
              <p>
                Ao dar entrada de qualquer máquina (notebook, desktop ou periférico) no laboratório ou via serviço de Leva e Traz, é emitido um protocolo de atendimento com checklist dos componentes externos, cabos, fontes e estado visual da carcaça.
              </p>
            </section>

            <section className="space-y-2">
              <h2 className="font-heading font-bold text-base text-[#202124]">3. Garantia de 90 Dias (CDC art. 26)</h2>
              <p>
                Todos os serviços executados e componentes substituídos contam com garantia legal de <strong>90 (noventa) dias</strong> a partir da data de entrega, cobrindo exclusivamente o reparo realizado ou a peça trocada.
              </p>
              <p>
                A garantia não cobre:
              </p>
              <ul className="list-disc pl-5 space-y-1">
                <li>Quedas, impactos mecânicos, quebra de dobradiças posteriores ou trincados em telas;</li>
                <li>Danos causados por descargas atmosféricas (raios), oscilações elétricas sem uso de proteção adequada ou umidade/líquidos derramados;</li>
                <li>Rompimento de lacres de segurança ou intervenção de terceiros não autorizados.</li>
              </ul>
            </section>

            <section className="space-y-2">
              <h2 className="font-heading font-bold text-base text-[#202124]">4. Orçamentos e Prazos</h2>
              <p>
                Nenhum serviço remunerado é iniciado sem autorização formal prévia do cliente (via WhatsApp ou assinatura digital/física de OS). O prazo de diagnóstico padrão varia de 24h a 48h úteis, dependendo da complexidade das medições elétricas.
              </p>
            </section>

            <section className="space-y-2">
              <h2 className="font-heading font-bold text-base text-[#202124]">5. Foro</h2>
              <p>
                Fica eleito o Foro da Comarca de João Pessoa, Estado da Paraíba, para dirimir quaisquer dúvidas oriundas deste instrumento.
              </p>
            </section>
          </div>
        )}
      </div>
    </div>
  );
};
