import React, { useState } from 'react';
import { useApp } from '../../context/AppContext';
import { AdminLayout } from '../../components/admin/AdminLayout';
import {
  User,
  ShieldCheck,
  KeyRound,
  Bell,
  Save,
  CheckCircle2,
  Laptop,
  Smartphone,
  LogOut,
} from 'lucide-react';

export const AdminProfile: React.FC = () => {
  const { userProfile, updateUserProfile, changePassword, showToast } = useApp();

  const [name, setName] = useState(userProfile.name);
  const [email, setEmail] = useState(userProfile.email);
  const [phone, setPhone] = useState(userProfile.phone);
  const [role, setRole] = useState(userProfile.role);
  const [department, setDepartment] = useState(userProfile.department);

  const [currentPassword, setCurrentPassword] = useState('');
  const [newPassword, setNewPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');

  const [emailAlerts, setEmailAlerts] = useState(
    userProfile.notificationPreferences?.emailOnNewLead ?? true
  );
  const [whatsappAlerts, setWhatsappAlerts] = useState(
    userProfile.notificationPreferences?.whatsappAlerts ?? true
  );
  const [browserSound, setBrowserSound] = useState(
    userProfile.notificationPreferences?.browserSound ?? true
  );

  const handleSaveProfile = (e: React.FormEvent) => {
    e.preventDefault();
    updateUserProfile({
      name,
      email,
      phone,
      role,
      department,
      notificationPreferences: {
        emailOnNewLead: emailAlerts,
        whatsappAlerts,
        browserSound,
      },
    });
  };

  const handleChangePassword = (e: React.FormEvent) => {
    e.preventDefault();
    if (!currentPassword) {
      showToast('Digite a senha atual.', 'warning');
      return;
    }
    if (newPassword.length < 6) {
      showToast('A nova senha deve possuir pelo menos 6 caracteres.', 'warning');
      return;
    }
    if (newPassword !== confirmPassword) {
      showToast('A confirmação de senha não confere.', 'error');
      return;
    }

    changePassword(currentPassword, newPassword);
    setCurrentPassword('');
    setNewPassword('');
    setConfirmPassword('');
  };

  return (
    <AdminLayout
      title="Perfil do Usuário"
      subtitle="Gerencie suas credenciais de acesso, nível de permissão e preferências de notificação do sistema"
    >
      <div className="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        {/* Left Column: User Card & Sessions (Col 4) */}
        <div className="lg:col-span-4 space-y-6">
          <div className="bg-white rounded-2xl border border-[#E4E7EC] p-6 shadow-2xs text-center space-y-4">
            <div className="relative w-24 h-24 mx-auto rounded-full overflow-hidden border-2 border-[#D71920] shadow-sm">
              <img
                src={userProfile.avatar}
                alt={userProfile.name}
                className="w-full h-full object-cover"
              />
            </div>

            <div>
              <h2 className="font-heading font-extrabold text-base text-[#202124]">
                {userProfile.name}
              </h2>
              <span className="text-xs text-[#697386] block mt-0.5">{userProfile.email}</span>
              <div className="mt-2 inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#F5F6F8] text-[#D71920] border border-[#E4E7EC]">
                <ShieldCheck className="w-3 h-3" />
                <span className="uppercase">{userProfile.role}</span>
              </div>
            </div>

            <div className="pt-3 border-t border-[#E4E7EC] text-left text-xs space-y-2">
              <div className="flex justify-between text-[#697386]">
                <span>Departamento:</span>
                <span className="font-bold text-[#202124]">{userProfile.department}</span>
              </div>
              <div className="flex justify-between text-[#697386]">
                <span>Último Acesso:</span>
                <span className="font-medium text-[#202124]">{userProfile.lastLogin}</span>
              </div>
            </div>
          </div>

          {/* Active Sessions */}
          <div className="bg-white rounded-2xl border border-[#E4E7EC] p-6 shadow-2xs space-y-4 text-xs">
            <h3 className="font-heading font-bold text-sm text-[#202124]">
              Dispositivos Conectados
            </h3>

            <div className="space-y-3">
              <div className="flex items-center gap-3 p-3 rounded-xl bg-[#F5F6F8]">
                <Laptop className="w-5 h-5 text-[#D71920] shrink-0" />
                <div className="flex-1 min-w-0">
                  <div className="font-bold text-[#202124]">Apple MacBook Pro (Sessão Atual)</div>
                  <div className="text-[10px] text-emerald-700 font-semibold">João Pessoa, PB · Ativo agora</div>
                </div>
              </div>

              <div className="flex items-center gap-3 p-3 rounded-xl bg-[#F5F6F8]">
                <Smartphone className="w-5 h-5 text-[#697386] shrink-0" />
                <div className="flex-1 min-w-0">
                  <div className="font-bold text-[#202124]">iPhone 15 Pro Max</div>
                  <div className="text-[10px] text-[#697386]">João Pessoa, PB · Hoje às 09:12</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* Right Column: Edit Profile & Password (Col 8) */}
        <div className="lg:col-span-8 space-y-6">
          {/* Personal Information Form */}
          <div className="bg-white rounded-2xl border border-[#E4E7EC] p-6 shadow-2xs space-y-5">
            <div className="pb-3 border-b border-[#E4E7EC]">
              <h3 className="font-heading font-bold text-base text-[#202124]">
                Informações Pessoais &amp; Cadastro
              </h3>
              <p className="text-xs text-[#697386]">
                Dados cadastrais utilizados no sistema interno e nas assinaturas de ordens de serviço
              </p>
            </div>

            <form onSubmit={handleSaveProfile} className="space-y-4 text-xs">
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-semibold text-[#202124] mb-1">
                    Nome Completo *
                  </label>
                  <input
                    type="text"
                    required
                    value={name}
                    onChange={(e) => setName(e.target.value)}
                    className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]"
                  />
                </div>

                <div>
                  <label className="block text-xs font-semibold text-[#202124] mb-1">
                    E-mail Corporativo *
                  </label>
                  <input
                    type="email"
                    required
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]"
                  />
                </div>
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                  <label className="block text-xs font-semibold text-[#202124] mb-1">
                    Telefone Direto
                  </label>
                  <input
                    type="text"
                    value={phone}
                    onChange={(e) => setPhone(e.target.value)}
                    className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124]"
                  />
                </div>

                <div>
                  <label className="block text-xs font-semibold text-[#202124] mb-1">
                    Nível de Acesso (Role)
                  </label>
                  <select
                    value={role}
                    onChange={(e) => setRole(e.target.value as any)}
                    className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124]"
                  >
                    <option value="superadmin">Superadministrador</option>
                    <option value="tecnico">Técnico de Bancada</option>
                    <option value="atendente">Atendente de Balcão</option>
                  </select>
                </div>

                <div>
                  <label className="block text-xs font-semibold text-[#202124] mb-1">
                    Departamento
                  </label>
                  <input
                    type="text"
                    value={department}
                    onChange={(e) => setDepartment(e.target.value)}
                    className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124]"
                  />
                </div>
              </div>

              {/* Notification Preferences */}
              <div className="pt-3 border-t border-[#E4E7EC] space-y-3">
                <span className="font-heading font-bold text-xs text-[#202124] uppercase tracking-wider block">
                  Alertas &amp; Notificações
                </span>

                <div className="space-y-2">
                  <label className="flex items-center gap-2 cursor-pointer">
                    <input
                      type="checkbox"
                      checked={emailAlerts}
                      onChange={(e) => setEmailAlerts(e.target.checked)}
                      className="accent-[#D71920]"
                    />
                    <span className="text-[#202124]">
                      Receber e-mail instantâneo a cada novo lead recebido no site
                    </span>
                  </label>

                  <label className="flex items-center gap-2 cursor-pointer">
                    <input
                      type="checkbox"
                      checked={whatsappAlerts}
                      onChange={(e) => setWhatsappAlerts(e.target.checked)}
                      className="accent-[#D71920]"
                    />
                    <span className="text-[#202124]">
                      Ativar pré-visualização de mensagem para disparo no WhatsApp
                    </span>
                  </label>

                  <label className="flex items-center gap-2 cursor-pointer">
                    <input
                      type="checkbox"
                      checked={browserSound}
                      onChange={(e) => setBrowserSound(e.target.checked)}
                      className="accent-[#D71920]"
                    />
                    <span className="text-[#202124]">
                      Alerta sonoro no navegador ao receber nova solicitação
                    </span>
                  </label>
                </div>
              </div>

              <div className="pt-2 text-right">
                <button
                  type="submit"
                  className="px-5 py-2.5 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold transition-colors inline-flex items-center gap-2 shadow-xs cursor-pointer"
                >
                  <Save className="w-3.5 h-3.5" />
                  <span>Salvar Dados do Perfil</span>
                </button>
              </div>
            </form>
          </div>

          {/* Password Change Form */}
          <div className="bg-white rounded-2xl border border-[#E4E7EC] p-6 shadow-2xs space-y-5">
            <div className="pb-3 border-b border-[#E4E7EC]">
              <h3 className="font-heading font-bold text-base text-[#202124]">
                Alteração de Senha
              </h3>
              <p className="text-xs text-[#697386]">
                Para sua segurança, utilize senhas com letras maiúsculas, minúsculas, números e caracteres especiais
              </p>
            </div>

            <form onSubmit={handleChangePassword} className="space-y-4 text-xs">
              <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                  <label className="block text-xs font-semibold text-[#202124] mb-1">
                    Senha Atual *
                  </label>
                  <input
                    type="password"
                    required
                    value={currentPassword}
                    onChange={(e) => setCurrentPassword(e.target.value)}
                    placeholder="••••••••"
                    className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white text-xs"
                  />
                </div>

                <div>
                  <label className="block text-xs font-semibold text-[#202124] mb-1">
                    Nova Senha *
                  </label>
                  <input
                    type="password"
                    required
                    value={newPassword}
                    onChange={(e) => setNewPassword(e.target.value)}
                    placeholder="Mínimo 6 dígitos"
                    className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white text-xs"
                  />
                </div>

                <div>
                  <label className="block text-xs font-semibold text-[#202124] mb-1">
                    Confirmar Nova Senha *
                  </label>
                  <input
                    type="password"
                    required
                    value={confirmPassword}
                    onChange={(e) => setConfirmPassword(e.target.value)}
                    placeholder="Repita a senha"
                    className="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white text-xs"
                  />
                </div>
              </div>

              <div className="pt-2 text-right">
                <button
                  type="submit"
                  className="px-5 py-2.5 rounded-lg bg-neutral-900 hover:bg-neutral-800 text-white text-xs font-semibold transition-colors inline-flex items-center gap-2 cursor-pointer shadow-xs"
                >
                  <KeyRound className="w-3.5 h-3.5" />
                  <span>Atualizar Senha</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </AdminLayout>
  );
};
