import {
  AnalyticsMetricSummary,
  TrafficSource,
  CityVisitorStat,
  PageStat,
} from '../types';

export interface TimeframeAnalytics {
  summary: AnalyticsMetricSummary;
  dailyVisits: { date: string; visitors: number; pageViews: number; leads: number }[];
  trafficSources: TrafficSource[];
  cityStats: CityVisitorStat[];
  deviceBreakdown: { device: string; percentage: number; visits: number }[];
  topPages: PageStat[];
  conversionFunnel: { stage: string; count: number; percentage: number }[];
}

export const ANALYTICS_DATA: Record<'hoje' | '7dias' | '30dias' | 'mes', TimeframeAnalytics> = {
  hoje: {
    summary: {
      totalVisits: 142,
      uniqueVisitors: 118,
      pageViews: 412,
      avgTimeOnSite: '2m 45s',
      bounceRate: '28.4%',
      conversionRate: '8.4%',
      totalLeads: 12,
      whatsappClicks: 9,
      phoneCalls: 3,
    },
    dailyVisits: [
      { date: '08:00', visitors: 8, pageViews: 22, leads: 1 },
      { date: '10:00', visitors: 26, pageViews: 84, leads: 2 },
      { date: '12:00', visitors: 19, pageViews: 54, leads: 1 },
      { date: '14:00', visitors: 34, pageViews: 112, leads: 4 },
      { date: '16:00', visitors: 38, pageViews: 98, leads: 3 },
      { date: '18:00', visitors: 17, pageViews: 42, leads: 1 },
    ],
    trafficSources: [
      { source: 'Google Orgânico (SEO)', visits: 68, percentage: 48, conversions: 6 },
      { source: 'Google Meu Negócio (Maps)', visits: 41, percentage: 29, conversions: 4 },
      { source: 'Acesso Direto / Cartão', visits: 18, percentage: 13, conversions: 1 },
      { source: 'Instagram / Redes Sociais', visits: 15, percentage: 10, conversions: 1 },
    ],
    cityStats: [
      { city: 'João Pessoa', visits: 95, percentage: 67, leads: 8 },
      { city: 'Cabedelo', visits: 24, percentage: 17, leads: 2 },
      { city: 'Bayeux', visits: 12, percentage: 8, leads: 1 },
      { city: 'Santa Rita', visits: 8, percentage: 6, leads: 1 },
      { city: 'Conde / Outros', visits: 3, percentage: 2, leads: 0 },
    ],
    deviceBreakdown: [
      { device: 'Mobile (Smartphones)', percentage: 71, visits: 101 },
      { device: 'Desktop / Notebook', percentage: 26, visits: 37 },
      { device: 'Tablet', percentage: 3, visits: 4 },
    ],
    topPages: [
      { path: '/', title: 'Início | Assistência Técnica João Pessoa', views: 184, uniqueViews: 118, avgTime: '1m 50s', conversionCount: 5 },
      { path: '/servicos', title: 'Catálogo de Serviços', views: 98, uniqueViews: 72, avgTime: '2m 10s', conversionCount: 3 },
      { path: '/servicos/manutencao-notebooks', title: 'Manutenção de Notebooks', views: 56, uniqueViews: 44, avgTime: '3m 05s', conversionCount: 2 },
      { path: '/servicos/upgrade-ssd-memoria', title: 'Upgrade de SSD e Memória RAM', views: 42, uniqueViews: 35, avgTime: '2m 40s', conversionCount: 2 },
      { path: '/contato', title: 'Solicitar Orçamento', views: 32, uniqueViews: 28, avgTime: '1m 15s', conversionCount: 0 },
    ],
    conversionFunnel: [
      { stage: '1. Visitantes no Site', count: 142, percentage: 100 },
      { stage: '2. Visualizou Página de Serviços', count: 98, percentage: 69 },
      { stage: '3. Acessou Detalhes / Formulário', count: 48, percentage: 34 },
      { stage: '4. Lead Gerado (WhatsApp / Formulário)', count: 12, percentage: 8.4 },
    ],
  },
  '7dias': {
    summary: {
      totalVisits: 1084,
      uniqueVisitors: 842,
      pageViews: 3240,
      avgTimeOnSite: '2m 38s',
      bounceRate: '29.1%',
      conversionRate: '7.8%',
      totalLeads: 85,
      whatsappClicks: 62,
      phoneCalls: 23,
    },
    dailyVisits: [
      { date: 'Segunda', visitors: 165, pageViews: 520, leads: 14 },
      { date: 'Terça', visitors: 182, pageViews: 574, leads: 16 },
      { date: 'Quarta', visitors: 194, pageViews: 610, leads: 18 },
      { date: 'Quinta', visitors: 178, pageViews: 540, leads: 13 },
      { date: 'Sexta', visitors: 162, pageViews: 490, leads: 12 },
      { date: 'Sábado', visitors: 115, pageViews: 310, leads: 8 },
      { date: 'Domingo', visitors: 88, pageViews: 196, leads: 4 },
    ],
    trafficSources: [
      { source: 'Google Orgânico (SEO)', visits: 520, percentage: 48, conversions: 42 },
      { source: 'Google Meu Negócio (Maps)', visits: 314, percentage: 29, conversions: 27 },
      { source: 'Acesso Direto / Cartão', visits: 141, percentage: 13, conversions: 9 },
      { source: 'Instagram / Redes Sociais', visits: 109, percentage: 10, conversions: 7 },
    ],
    cityStats: [
      { city: 'João Pessoa', visits: 726, percentage: 67, leads: 59 },
      { city: 'Cabedelo', visits: 184, percentage: 17, leads: 15 },
      { city: 'Bayeux', visits: 87, percentage: 8, leads: 6 },
      { city: 'Santa Rita', visits: 54, percentage: 5, leads: 3 },
      { city: 'Conde / Litoral Sul', visits: 33, percentage: 3, leads: 2 },
    ],
    deviceBreakdown: [
      { device: 'Mobile (Smartphones)', percentage: 69, visits: 748 },
      { device: 'Desktop / Notebook', percentage: 28, visits: 303 },
      { device: 'Tablet', percentage: 3, visits: 33 },
    ],
    topPages: [
      { path: '/', title: 'Início | Assistência Técnica João Pessoa', views: 1420, uniqueViews: 842, avgTime: '1m 55s', conversionCount: 38 },
      { path: '/servicos', title: 'Catálogo de Serviços', views: 760, uniqueViews: 520, avgTime: '2m 15s', conversionCount: 21 },
      { path: '/servicos/manutencao-notebooks', title: 'Manutenção de Notebooks', views: 410, uniqueViews: 312, avgTime: '3m 10s', conversionCount: 12 },
      { path: '/servicos/upgrade-ssd-memoria', title: 'Upgrade de SSD e Memória RAM', views: 335, uniqueViews: 248, avgTime: '2m 50s', conversionCount: 8 },
      { path: '/areas-atendidas', title: 'Cidades Atendidas Grande JP', views: 185, uniqueViews: 140, avgTime: '1m 40s', conversionCount: 4 },
      { path: '/contato', title: 'Solicitar Orçamento', views: 130, uniqueViews: 110, avgTime: '1m 20s', conversionCount: 2 },
    ],
    conversionFunnel: [
      { stage: '1. Visitantes no Site', count: 1084, percentage: 100 },
      { stage: '2. Visualizou Página de Serviços', count: 760, percentage: 70 },
      { stage: '3. Acessou Detalhes / Formulário', count: 380, percentage: 35 },
      { stage: '4. Lead Gerado (WhatsApp / Formulário)', count: 85, percentage: 7.8 },
    ],
  },
  '30dias': {
    summary: {
      totalVisits: 4720,
      uniqueVisitors: 3650,
      pageViews: 14160,
      avgTimeOnSite: '2m 42s',
      bounceRate: '28.9%',
      conversionRate: '8.1%',
      totalLeads: 382,
      whatsappClicks: 284,
      phoneCalls: 98,
    },
    dailyVisits: [
      { date: 'Semana 1', visitors: 1120, pageViews: 3360, leads: 91 },
      { date: 'Semana 2', visitors: 1180, pageViews: 3540, leads: 96 },
      { date: 'Semana 3', visitors: 1240, pageViews: 3720, leads: 102 },
      { date: 'Semana 4', visitors: 1180, pageViews: 3540, leads: 93 },
    ],
    trafficSources: [
      { source: 'Google Orgânico (SEO)', visits: 2265, percentage: 48, conversions: 188 },
      { source: 'Google Meu Negócio (Maps)', visits: 1369, percentage: 29, conversions: 115 },
      { source: 'Acesso Direto / Cartão', visits: 614, percentage: 13, conversions: 48 },
      { source: 'Instagram / Redes Sociais', visits: 472, percentage: 10, conversions: 31 },
    ],
    cityStats: [
      { city: 'João Pessoa', visits: 3162, percentage: 67, leads: 260 },
      { city: 'Cabedelo', visits: 802, percentage: 17, leads: 64 },
      { city: 'Bayeux', visits: 378, percentage: 8, leads: 29 },
      { city: 'Santa Rita', visits: 236, percentage: 5, leads: 18 },
      { city: 'Conde / Litoral Sul', visits: 142, percentage: 3, leads: 11 },
    ],
    deviceBreakdown: [
      { device: 'Mobile (Smartphones)', percentage: 68, visits: 3210 },
      { device: 'Desktop / Notebook', percentage: 29, visits: 1369 },
      { device: 'Tablet', percentage: 3, visits: 141 },
    ],
    topPages: [
      { path: '/', title: 'Início | Assistência Técnica João Pessoa', views: 6240, uniqueViews: 3650, avgTime: '1m 58s', conversionCount: 168 },
      { path: '/servicos', title: 'Catálogo de Serviços', views: 3310, uniqueViews: 2280, avgTime: '2m 18s', conversionCount: 94 },
      { path: '/servicos/manutencao-notebooks', title: 'Manutenção de Notebooks', views: 1820, uniqueViews: 1340, avgTime: '3m 12s', conversionCount: 52 },
      { path: '/servicos/upgrade-ssd-memoria', title: 'Upgrade de SSD e Memória RAM', views: 1450, uniqueViews: 1080, avgTime: '2m 54s', conversionCount: 36 },
      { path: '/areas-atendidas', title: 'Cidades Atendidas Grande JP', views: 780, uniqueViews: 610, avgTime: '1m 45s', conversionCount: 18 },
      { path: '/contato', title: 'Solicitar Orçamento', views: 560, uniqueViews: 490, avgTime: '1m 25s', conversionCount: 14 },
    ],
    conversionFunnel: [
      { stage: '1. Visitantes no Site', count: 4720, percentage: 100 },
      { stage: '2. Visualizou Página de Serviços', count: 3310, percentage: 70 },
      { stage: '3. Acessou Detalhes / Formulário', count: 1650, percentage: 35 },
      { stage: '4. Lead Gerado (WhatsApp / Formulário)', count: 382, percentage: 8.1 },
    ],
  },
  mes: {
    summary: {
      totalVisits: 5210,
      uniqueVisitors: 4020,
      pageViews: 15630,
      avgTimeOnSite: '2m 44s',
      bounceRate: '28.5%',
      conversionRate: '8.3%',
      totalLeads: 432,
      whatsappClicks: 320,
      phoneCalls: 112,
    },
    dailyVisits: [
      { date: '01 a 07', visitors: 1180, pageViews: 3540, leads: 98 },
      { date: '08 a 14', visitors: 1240, pageViews: 3720, leads: 104 },
      { date: '15 a 21', visitors: 1320, pageViews: 3960, leads: 112 },
      { date: '22 a 28', visitors: 1190, pageViews: 3570, leads: 96 },
      { date: '29 a 31', visitors: 280, pageViews: 840, leads: 22 },
    ],
    trafficSources: [
      { source: 'Google Orgânico (SEO)', visits: 2501, percentage: 48, conversions: 212 },
      { source: 'Google Meu Negócio (Maps)', visits: 1511, percentage: 29, conversions: 129 },
      { source: 'Acesso Direto / Cartão', visits: 677, percentage: 13, conversions: 54 },
      { source: 'Instagram / Redes Sociais', visits: 521, percentage: 10, conversions: 37 },
    ],
    cityStats: [
      { city: 'João Pessoa', visits: 3491, percentage: 67, leads: 292 },
      { city: 'Cabedelo', visits: 886, percentage: 17, leads: 74 },
      { city: 'Bayeux', visits: 417, percentage: 8, leads: 34 },
      { city: 'Santa Rita', visits: 260, percentage: 5, leads: 21 },
      { city: 'Conde / Litoral Sul', visits: 156, percentage: 3, leads: 11 },
    ],
    deviceBreakdown: [
      { device: 'Mobile (Smartphones)', percentage: 68, visits: 3543 },
      { device: 'Desktop / Notebook', percentage: 29, visits: 1511 },
      { device: 'Tablet', percentage: 3, visits: 156 },
    ],
    topPages: [
      { path: '/', title: 'Início | Assistência Técnica João Pessoa', views: 6890, uniqueViews: 4020, avgTime: '2m 00s', conversionCount: 190 },
      { path: '/servicos', title: 'Catálogo de Serviços', views: 3650, uniqueViews: 2510, avgTime: '2m 20s', conversionCount: 108 },
      { path: '/servicos/manutencao-notebooks', title: 'Manutenção de Notebooks', views: 2010, uniqueViews: 1480, avgTime: '3m 15s', conversionCount: 58 },
      { path: '/servicos/upgrade-ssd-memoria', title: 'Upgrade de SSD e Memória RAM', views: 1610, uniqueViews: 1190, avgTime: '2m 58s', conversionCount: 42 },
      { path: '/areas-atendidas', title: 'Cidades Atendidas Grande JP', views: 860, uniqueViews: 680, avgTime: '1m 48s', conversionCount: 21 },
      { path: '/contato', title: 'Solicitar Orçamento', views: 610, uniqueViews: 530, avgTime: '1m 30s', conversionCount: 13 },
    ],
    conversionFunnel: [
      { stage: '1. Visitantes no Site', count: 5210, percentage: 100 },
      { stage: '2. Visualizou Página de Serviços', count: 3650, percentage: 70 },
      { stage: '3. Acessou Detalhes / Formulário', count: 1820, percentage: 35 },
      { stage: '4. Lead Gerado (WhatsApp / Formulário)', count: 432, percentage: 8.3 },
    ],
  },
};
