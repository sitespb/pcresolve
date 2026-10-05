import React from 'react';
import { AppProvider, useApp } from './context/AppContext';
import { TopBar } from './components/public/TopBar';
import { Header } from './components/public/Header';
import { Footer } from './components/public/Footer';
import { WhatsAppButton } from './components/common/WhatsAppButton';
import { CookieBanner } from './components/common/CookieBanner';
import { ToastContainer } from './components/common/ToastContainer';

// Public pages
import { HomePage } from './pages/HomePage';
import { ServicesPage } from './pages/ServicesPage';
import { ServiceDetailPage } from './pages/ServiceDetailPage';
import { AboutPage } from './pages/AboutPage';
import { AreasPage } from './pages/AreasPage';
import { ContactPage } from './pages/ContactPage';
import { LegalPage } from './pages/LegalPage';

// Admin pages
import { AdminDashboard } from './pages/admin/AdminDashboard';
import { AdminAnalytics } from './pages/admin/AdminAnalytics';
import { AdminRequests } from './pages/admin/AdminRequests';
import { AdminServices } from './pages/admin/AdminServices';
import { AdminCities } from './pages/admin/AdminCities';
import { AdminTestimonials } from './pages/admin/AdminTestimonials';
import { AdminFaq } from './pages/admin/AdminFaq';
import { AdminProfile } from './pages/admin/AdminProfile';
import { AdminSettings } from './pages/admin/AdminSettings';

const AppContent: React.FC = () => {
  const { currentRoute } = useApp();

  const isAdminRoute = currentRoute.startsWith('admin-');

  const renderContent = () => {
    switch (currentRoute) {
      // Public pages
      case 'home':
        return <HomePage />;
      case 'servicos':
        return <ServicesPage />;
      case 'servico-detalhe':
        return <ServiceDetailPage />;
      case 'sobre':
        return <AboutPage />;
      case 'areas':
        return <AreasPage />;
      case 'contato':
        return <ContactPage />;
      case 'termos':
        return <LegalPage type="termos" />;
      case 'privacidade':
        return <LegalPage type="privacidade" />;

      // Admin pages
      case 'admin-dashboard':
        return <AdminDashboard />;
      case 'admin-analytics':
        return <AdminAnalytics />;
      case 'admin-solicitacoes':
        return <AdminRequests />;
      case 'admin-servicos':
        return <AdminServices />;
      case 'admin-cidades':
        return <AdminCities />;
      case 'admin-depoimentos':
        return <AdminTestimonials />;
      case 'admin-faq':
        return <AdminFaq />;
      case 'admin-perfil':
        return <AdminProfile />;
      case 'admin-configuracoes':
        return <AdminSettings />;

      default:
        return <HomePage />;
    }
  };

  return (
    <div className="min-h-screen bg-white flex flex-col text-[#202124]">
      <ToastContainer />

      {isAdminRoute ? (
        // Admin views have their own layout
        renderContent()
      ) : (
        // Public website layout
        <>
          <TopBar />
          <Header />
          <div className="flex-1">{renderContent()}</div>
          <Footer />
          <WhatsAppButton />
          <CookieBanner />
        </>
      )}
    </div>
  );
};

export default function App() {
  return (
    <AppProvider>
      <AppContent />
    </AppProvider>
  );
}
