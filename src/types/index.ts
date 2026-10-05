export type DeviceType = 'notebook' | 'desktop' | 'all-in-one' | 'macbook' | 'corporativo' | 'outro';

export type ServiceCategory = 'todos' | 'hardware' | 'software' | 'preventiva' | 'corporativo';

export type LeadStatus =
  | 'pendente'
  | 'em_diagnostico'
  | 'aguardando_aprovacao'
  | 'em_execucao'
  | 'concluido'
  | 'entregue';

export interface ServiceItem {
  id: string;
  slug: string;
  title: string;
  shortDesc: string;
  fullDesc: string;
  category: ServiceCategory;
  priceStartingAt: number;
  turnaroundTime: string;
  warrantyDays: number;
  iconName: string;
  image: string;
  highlights: string[];
  recommendedFor: string[];
  active: boolean;
  order: number;
}

export interface LeadRequest {
  id: string;
  protocol: string;
  customerName: string;
  phone: string;
  email?: string;
  city: string;
  deviceType: DeviceType;
  serviceType: string;
  description: string;
  status: LeadStatus;
  estimatedBudget?: number;
  finalBudget?: number;
  internalNotes?: string;
  createdAt: string;
  updatedAt: string;
}

export interface Testimonial {
  id: string;
  author: string;
  location: string;
  rating: number;
  text: string;
  serviceTitle: string;
  date: string;
  verified: boolean;
  approved: boolean;
}

export interface FaqItem {
  id: string;
  question: string;
  answer: string;
  category: string;
  order: number;
}

export interface CityArea {
  id: string;
  name: string;
  coverage: string;
  deliveryAvailable: boolean;
  deliveryFee: number;
  notes: string;
  active: boolean;
}

export interface CompanySettings {
  name: string;
  corporateName: string;
  cnpj: string;
  phone: string;
  whatsapp: string;
  email: string;
  address: string;
  neighborhood: string;
  city: string;
  state: string;
  zipCode: string;
  workingHoursWeekday: string;
  workingHoursSaturday: string;
  aboutText: string;
  mission: string;
  warrantyTerm: string;
  ga4MeasurementId: string;
  gtmContainerId: string;
  googleAdsConversionId: string;
  metaTitleDefault: string;
  metaDescriptionDefault: string;
  defaultWhatsappMessage: string;
}

export interface UserProfile {
  id: string;
  name: string;
  email: string;
  phone: string;
  role: 'superadmin' | 'tecnico' | 'atendente';
  avatar: string;
  department: string;
  lastLogin: string;
  notificationPreferences: {
    emailOnNewLead: boolean;
    whatsappAlerts: boolean;
    browserSound: boolean;
  };
}

export interface AnalyticsMetricSummary {
  totalVisits: number;
  uniqueVisitors: number;
  pageViews: number;
  avgTimeOnSite: string;
  bounceRate: string;
  conversionRate: string;
  totalLeads: number;
  whatsappClicks: number;
  phoneCalls: number;
}

export interface TrafficSource {
  source: string;
  visits: number;
  percentage: number;
  conversions: number;
}

export interface CityVisitorStat {
  city: string;
  visits: number;
  percentage: number;
  leads: number;
}

export interface PageStat {
  path: string;
  title: string;
  views: number;
  uniqueViews: number;
  avgTime: string;
  conversionCount: number;
}
