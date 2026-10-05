import React, { createContext, useContext, useState, useEffect } from 'react';
import {
  ServiceItem,
  LeadRequest,
  Testimonial,
  FaqItem,
  CityArea,
  CompanySettings,
  UserProfile,
  LeadStatus,
} from '../types';
import {
  INITIAL_SERVICES,
  INITIAL_LEADS,
  INITIAL_TESTIMONIALS,
  INITIAL_FAQS,
  INITIAL_CITIES,
  INITIAL_COMPANY_SETTINGS,
  INITIAL_USER_PROFILE,
} from '../data/initialData';
import { ANALYTICS_DATA, TimeframeAnalytics } from '../data/analyticsData';

export type AppRoute =
  | 'home'
  | 'servicos'
  | 'servico-detalhe'
  | 'sobre'
  | 'areas'
  | 'contato'
  | 'termos'
  | 'privacidade'
  | 'admin-dashboard'
  | 'admin-analytics'
  | 'admin-solicitacoes'
  | 'admin-servicos'
  | 'admin-cidades'
  | 'admin-depoimentos'
  | 'admin-faq'
  | 'admin-perfil'
  | 'admin-configuracoes';

export interface ToastMessage {
  id: string;
  text: string;
  type: 'success' | 'info' | 'error' | 'warning';
}

interface AppContextType {
  currentRoute: AppRoute;
  selectedServiceSlug: string | null;
  navigateTo: (route: AppRoute, serviceSlug?: string) => void;
  // Services
  services: ServiceItem[];
  addService: (item: Omit<ServiceItem, 'id'>) => void;
  updateService: (id: string, item: Partial<ServiceItem>) => void;
  toggleServiceStatus: (id: string) => void;
  deleteService: (id: string) => void;
  // Leads / OS
  leads: LeadRequest[];
  addLead: (lead: Omit<LeadRequest, 'id' | 'protocol' | 'createdAt' | 'updatedAt' | 'status'>) => string;
  updateLeadStatus: (id: string, status: LeadStatus) => void;
  updateLeadBudget: (id: string, budget: number) => void;
  addLeadNote: (id: string, note: string) => void;
  // Testimonials
  testimonials: Testimonial[];
  addTestimonial: (t: Omit<Testimonial, 'id' | 'date' | 'verified' | 'approved'>) => void;
  approveTestimonial: (id: string) => void;
  rejectTestimonial: (id: string) => void;
  // Cities
  cities: CityArea[];
  toggleCityStatus: (id: string) => void;
  updateCityFee: (id: string, fee: number) => void;
  // Faqs
  faqs: FaqItem[];
  addFaq: (faq: Omit<FaqItem, 'id' | 'order'>) => void;
  updateFaq: (id: string, faq: Partial<FaqItem>) => void;
  deleteFaq: (id: string) => void;
  // Company & User
  companySettings: CompanySettings;
  updateCompanySettings: (settings: Partial<CompanySettings>) => void;
  userProfile: UserProfile;
  updateUserProfile: (profile: Partial<UserProfile>) => void;
  changePassword: (oldPass: string, newPass: string) => boolean;
  // Analytics
  analyticsTimeframe: 'hoje' | '7dias' | '30dias' | 'mes';
  setAnalyticsTimeframe: (tf: 'hoje' | '7dias' | '30dias' | 'mes') => void;
  currentAnalytics: TimeframeAnalytics;
  // Toast
  toasts: ToastMessage[];
  showToast: (text: string, type?: 'success' | 'info' | 'error' | 'warning') => void;
  removeToast: (id: string) => void;
  // Cookies
  cookieAccepted: boolean;
  acceptCookies: () => void;
  resetAllData: () => void;
}

const AppContext = createContext<AppContextType | undefined>(undefined);

export const AppProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [currentRoute, setCurrentRoute] = useState<AppRoute>('home');
  const [selectedServiceSlug, setSelectedServiceSlug] = useState<string | null>(null);

  // Local storage state with initial fallback
  const [services, setServices] = useState<ServiceItem[]>(() => {
    const saved = localStorage.getItem('pcresolve_services');
    return saved ? JSON.parse(saved) : INITIAL_SERVICES;
  });

  const [leads, setLeads] = useState<LeadRequest[]>(() => {
    const saved = localStorage.getItem('pcresolve_leads');
    return saved ? JSON.parse(saved) : INITIAL_LEADS;
  });

  const [testimonials, setTestimonials] = useState<Testimonial[]>(() => {
    const saved = localStorage.getItem('pcresolve_testimonials');
    return saved ? JSON.parse(saved) : INITIAL_TESTIMONIALS;
  });

  const [cities, setCities] = useState<CityArea[]>(() => {
    const saved = localStorage.getItem('pcresolve_cities');
    return saved ? JSON.parse(saved) : INITIAL_CITIES;
  });

  const [faqs, setFaqs] = useState<FaqItem[]>(() => {
    const saved = localStorage.getItem('pcresolve_faqs');
    return saved ? JSON.parse(saved) : INITIAL_FAQS;
  });

  const [companySettings, setCompanySettings] = useState<CompanySettings>(() => {
    const saved = localStorage.getItem('pcresolve_settings');
    return saved ? JSON.parse(saved) : INITIAL_COMPANY_SETTINGS;
  });

  const [userProfile, setUserProfile] = useState<UserProfile>(() => {
    const saved = localStorage.getItem('pcresolve_profile');
    return saved ? JSON.parse(saved) : INITIAL_USER_PROFILE;
  });

  const [analyticsTimeframe, setAnalyticsTimeframe] = useState<'hoje' | '7dias' | '30dias' | 'mes'>('7dias');
  const [toasts, setToasts] = useState<ToastMessage[]>([]);
  const [cookieAccepted, setCookieAccepted] = useState<boolean>(() => {
    return localStorage.getItem('pcresolve_cookie_consent') === 'true';
  });

  // Sync state to LocalStorage
  useEffect(() => {
    localStorage.setItem('pcresolve_services', JSON.stringify(services));
  }, [services]);

  useEffect(() => {
    localStorage.setItem('pcresolve_leads', JSON.stringify(leads));
  }, [leads]);

  useEffect(() => {
    localStorage.setItem('pcresolve_testimonials', JSON.stringify(testimonials));
  }, [testimonials]);

  useEffect(() => {
    localStorage.setItem('pcresolve_cities', JSON.stringify(cities));
  }, [cities]);

  useEffect(() => {
    localStorage.setItem('pcresolve_faqs', JSON.stringify(faqs));
  }, [faqs]);

  useEffect(() => {
    localStorage.setItem('pcresolve_settings', JSON.stringify(companySettings));
  }, [companySettings]);

  useEffect(() => {
    localStorage.setItem('pcresolve_profile', JSON.stringify(userProfile));
  }, [userProfile]);

  const showToast = (text: string, type: 'success' | 'info' | 'error' | 'warning' = 'success') => {
    const id = Date.now().toString() + Math.random().toString(36).substring(2, 5);
    setToasts((prev) => [...prev, { id, text, type }]);
    setTimeout(() => {
      removeToast(id);
    }, 4000);
  };

  const removeToast = (id: string) => {
    setToasts((prev) => prev.filter((t) => t.id !== id));
  };

  const navigateTo = (route: AppRoute, serviceSlug?: string) => {
    setCurrentRoute(route);
    if (serviceSlug) {
      setSelectedServiceSlug(serviceSlug);
    }
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  const acceptCookies = () => {
    setCookieAccepted(true);
    localStorage.setItem('pcresolve_cookie_consent', 'true');
    showToast('Preferências de cookies salvas.', 'info');
  };

  // Service CRUD
  const addService = (item: Omit<ServiceItem, 'id'>) => {
    const newService: ServiceItem = {
      ...item,
      id: 'srv-' + Date.now(),
    };
    setServices((prev) => [...prev, newService]);
    showToast(`Serviço "${newService.title}" cadastrado com sucesso!`);
  };

  const updateService = (id: string, updated: Partial<ServiceItem>) => {
    setServices((prev) => prev.map((s) => (s.id === id ? { ...s, ...updated } : s)));
    showToast('Serviço atualizado com sucesso!');
  };

  const toggleServiceStatus = (id: string) => {
    setServices((prev) =>
      prev.map((s) => {
        if (s.id === id) {
          const next = !s.active;
          showToast(`Serviço ${next ? 'ativado' : 'desativado'} no catálogo público.`);
          return { ...s, active: next };
        }
        return s;
      })
    );
  };

  const deleteService = (id: string) => {
    setServices((prev) => prev.filter((s) => s.id !== id));
    showToast('Serviço removido com sucesso.', 'info');
  };

  // Leads
  const addLead = (data: Omit<LeadRequest, 'id' | 'protocol' | 'createdAt' | 'updatedAt' | 'status'>) => {
    const randomSuffix = Math.floor(1000 + Math.random() * 9000);
    const protocol = `OS-2026-${randomSuffix}`;
    const now = new Date();
    const formattedDate = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(
      now.getDate()
    ).padStart(2, '0')} ${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;

    const newLead: LeadRequest = {
      ...data,
      id: 'lead-' + Date.now(),
      protocol,
      status: 'pendente',
      createdAt: formattedDate,
      updatedAt: formattedDate,
    };

    setLeads((prev) => [newLead, ...prev]);
    showToast(`Solicitação registrada! Protocolo ${protocol}`, 'success');
    return protocol;
  };

  const updateLeadStatus = (id: string, status: LeadStatus) => {
    const now = new Date();
    const formattedDate = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(
      now.getDate()
    ).padStart(2, '0')} ${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;

    setLeads((prev) =>
      prev.map((l) => (l.id === id ? { ...l, status, updatedAt: formattedDate } : l))
    );
    showToast('Status da Ordem de Serviço atualizado!', 'info');
  };

  const updateLeadBudget = (id: string, budget: number) => {
    setLeads((prev) =>
      prev.map((l) => (l.id === id ? { ...l, finalBudget: budget, estimatedBudget: budget } : l))
    );
    showToast(`Orçamento definido: R$ ${budget.toFixed(2)}`, 'success');
  };

  const addLeadNote = (id: string, note: string) => {
    setLeads((prev) =>
      prev.map((l) => (l.id === id ? { ...l, internalNotes: note } : l))
    );
    showToast('Anotação técnica salva.');
  };

  // Testimonials
  const addTestimonial = (t: Omit<Testimonial, 'id' | 'date' | 'verified' | 'approved'>) => {
    const now = new Date();
    const dateFormatted = `${String(now.getDate()).padStart(2, '0')}/${String(now.getMonth() + 1).padStart(2, '0')}/${now.getFullYear()}`;
    const newT: Testimonial = {
      ...t,
      id: 'test-' + Date.now(),
      date: dateFormatted,
      verified: true,
      approved: false, // Requires admin approval per PRD requirement!
    };
    setTestimonials((prev) => [newT, ...prev]);
    showToast('Avaliação enviada com sucesso! Ela será publicada após aprovação.', 'success');
  };

  const approveTestimonial = (id: string) => {
    setTestimonials((prev) =>
      prev.map((t) => (t.id === id ? { ...t, approved: true } : t))
    );
    showToast('Depoimento aprovado e publicado no site público!', 'success');
  };

  const rejectTestimonial = (id: string) => {
    setTestimonials((prev) =>
      prev.map((t) => (t.id === id ? { ...t, approved: false } : t))
    );
    showToast('Depoimento despublicado.', 'info');
  };

  // Cities
  const toggleCityStatus = (id: string) => {
    setCities((prev) =>
      prev.map((c) => (c.id === id ? { ...c, active: !c.active } : c))
    );
    showToast('Área de cobertura atualizada.');
  };

  const updateCityFee = (id: string, fee: number) => {
    setCities((prev) =>
      prev.map((c) => (c.id === id ? { ...c, deliveryFee: fee } : c))
    );
    showToast('Taxa de Leva e Traz ajustada.');
  };

  // Faqs
  const addFaq = (faq: Omit<FaqItem, 'id' | 'order'>) => {
    const newFaq: FaqItem = {
      ...faq,
      id: 'faq-' + Date.now(),
      order: faqs.length + 1,
    };
    setFaqs((prev) => [...prev, newFaq]);
    showToast('Pergunta frequente adicionada!');
  };

  const updateFaq = (id: string, data: Partial<FaqItem>) => {
    setFaqs((prev) => prev.map((f) => (f.id === id ? { ...f, ...data } : f)));
    showToast('FAQ atualizado.');
  };

  const deleteFaq = (id: string) => {
    setFaqs((prev) => prev.filter((f) => f.id !== id));
    showToast('Pergunta removida.');
  };

  // Settings & User
  const updateCompanySettings = (updated: Partial<CompanySettings>) => {
    setCompanySettings((prev) => ({ ...prev, ...updated }));
    showToast('Configurações da empresa salvas com sucesso!');
  };

  const updateUserProfile = (updated: Partial<UserProfile>) => {
    setUserProfile((prev) => ({ ...prev, ...updated }));
    showToast('Perfil de usuário atualizado com sucesso!');
  };

  const changePassword = (_oldPass: string, _newPass: string): boolean => {
    showToast('Senha alterada com sucesso!', 'success');
    return true;
  };

  const resetAllData = () => {
    localStorage.clear();
    setServices(INITIAL_SERVICES);
    setLeads(INITIAL_LEADS);
    setTestimonials(INITIAL_TESTIMONIALS);
    setCities(INITIAL_CITIES);
    setFaqs(INITIAL_FAQS);
    setCompanySettings(INITIAL_COMPANY_SETTINGS);
    setUserProfile(INITIAL_USER_PROFILE);
    showToast('Dados restaurados para o padrão de demonstração.', 'info');
  };

  const currentAnalytics = ANALYTICS_DATA[analyticsTimeframe];

  return (
    <AppContext.Provider
      value={{
        currentRoute,
        selectedServiceSlug,
        navigateTo,
        services,
        addService,
        updateService,
        toggleServiceStatus,
        deleteService,
        leads,
        addLead,
        updateLeadStatus,
        updateLeadBudget,
        addLeadNote,
        testimonials,
        addTestimonial,
        approveTestimonial,
        rejectTestimonial,
        cities,
        toggleCityStatus,
        updateCityFee,
        faqs,
        addFaq,
        updateFaq,
        deleteFaq,
        companySettings,
        updateCompanySettings,
        userProfile,
        updateUserProfile,
        changePassword,
        analyticsTimeframe,
        setAnalyticsTimeframe,
        currentAnalytics,
        toasts,
        showToast,
        removeToast,
        cookieAccepted,
        acceptCookies,
        resetAllData,
      }}
    >
      {children}
    </AppContext.Provider>
  );
};

export const useApp = () => {
  const context = useContext(AppContext);
  if (!context) {
    throw new Error('useApp must be used within an AppProvider');
  }
  return context;
};
