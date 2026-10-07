<?php
/**
 * @var array<string, mixed> $user
 * @var list<array<string, mixed>> $logins
 * @var string $lastLogin
 * @var string $currentSessionHash
 * @var array<string, mixed> $imageConfig
 */
use App\Models\User;
use App\Support\ImageProcessor;

$accept = implode(',', array_map(
    fn (string $f) => $f === 'jpg' ? 'image/jpeg' : 'image/' . $f,
    (array) $imageConfig['allowed_formats']
));
$formatsLabel = implode(', ', array_map('strtoupper', (array) $imageConfig['allowed_formats']));

$adminTitle = 'Perfil do Usuário';
$adminSubtitle = 'Gerencie suas credenciais de acesso, nível de permissão e preferências de notificação do sistema';
?>
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
  <div class="lg:col-span-4 space-y-6">
    <div
      class="bg-white rounded-2xl border border-[#E4E7EC] p-6 shadow-2xs text-center space-y-4"
      x-data="avatarUpload(<?= json_attr([
          'accept' => (array) $imageConfig['allowed_formats'],
          'maxUploadKb' => $imageConfig['max_upload_kb'],
          'formatsLabel' => $formatsLabel,
      ]) ?>)"
    >
      <!-- Foto de perfil: arraste a imagem sobre o círculo ou clique para escolher -->
      <form method="post" action="/painel/perfil/foto" enctype="multipart/form-data" x-ref="form">
        <?= csrf_field() ?>
        <label
          class="group relative w-24 h-24 mx-auto rounded-full overflow-hidden border-2 border-[#D71920] shadow-sm bg-neutral-100 flex items-center justify-center text-2xl font-bold text-[#697386] cursor-pointer transition-all"
          :class="dragging ? 'ring-4 ring-[#D71920]/30 scale-105' : ''"
          @dragover.prevent="dragging = true"
          @dragleave.prevent="dragging = false"
          @drop.prevent="drop($event)"
          title="Arraste uma imagem aqui ou clique para enviar"
        >
          <template x-if="preview">
            <img :src="preview" alt="Pré-visualização" class="w-full h-full object-cover">
          </template>

          <template x-if="!preview">
            <span class="w-full h-full flex items-center justify-center">
              <?php if (!empty($user['avatar'])): ?>
                <img src="<?= e(image_url($user['avatar'])) ?>" alt="<?= e($user['name']) ?>" class="w-full h-full object-cover">
              <?php else: ?>
                <?= e(mb_strtoupper(mb_substr((string) $user['name'], 0, 1))) ?>
              <?php endif; ?>
            </span>
          </template>

          <!-- Camada de ajuda ao passar o mouse ou arrastar -->
          <span
            class="absolute inset-0 bg-[#202124]/60 text-white flex flex-col items-center justify-center gap-0.5 opacity-0 group-hover:opacity-100 transition-opacity"
            :class="(dragging || sending) ? 'opacity-100' : ''"
          >
            <span x-show="!sending"><?= icon('Plus', 'w-5 h-5') ?></span>
            <span x-show="sending" x-cloak><?= icon('RefreshCw', 'w-5 h-5 animate-spin') ?></span>
            <span class="text-[9px] font-bold uppercase tracking-wide" x-text="sending ? 'Enviando' : 'Alterar'"></span>
          </span>

          <input type="file" name="avatar" x-ref="input" accept="<?= e($accept) ?>" class="sr-only" @change="pick($event.target.files[0])">
        </label>
      </form>

      <div class="space-y-1">
        <p class="text-[10px] text-[#697386] leading-snug">
          Arraste uma imagem sobre a foto ou clique para escolher.<br>
          <?= e($formatsLabel) ?> · até <?= (int) $imageConfig['max_upload_kb'] >= 1024
              ? round((int) $imageConfig['max_upload_kb'] / 1024, 1) . ' MB'
              : (int) $imageConfig['max_upload_kb'] . ' KB' ?>
          · otimizada para <?= (int) $imageConfig['max_width'] ?>×<?= (int) $imageConfig['max_height'] ?> px
          e <?= (int) $imageConfig['target_kb'] ?> KB
        </p>
        <?php if (!empty($user['avatar'])): ?>
          <form method="post" action="/painel/perfil/foto/remover" onsubmit="return confirm('Remover sua foto de perfil?')">
            <?= csrf_field() ?>
            <button type="submit" class="text-[10px] font-semibold text-[#697386] hover:text-[#D71920] underline transition-colors">
              Remover foto
            </button>
          </form>
        <?php endif; ?>
      </div>

      <div>
        <h2 class="font-heading font-extrabold text-base text-[#202124]"><?= e($user['name']) ?></h2>
        <span class="text-xs text-[#697386] block mt-0.5"><?= e($user['email']) ?></span>
        <div class="mt-2 inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#F5F6F8] text-[#D71920] border border-[#E4E7EC]">
          <?= icon('ShieldCheck', 'w-3 h-3') ?>
          <span class="uppercase"><?= e($user['role']) ?></span>
        </div>
      </div>

      <div class="pt-3 border-t border-[#E4E7EC] text-left text-xs space-y-2">
        <div class="flex justify-between gap-3 text-[#697386]">
          <span class="shrink-0">Departamento:</span>
          <span class="font-bold text-[#202124] text-right"><?= e($user['department']) ?></span>
        </div>
        <div class="flex justify-between gap-3 text-[#697386]">
          <span class="shrink-0">Último Acesso:</span>
          <span class="font-medium text-[#202124] text-right"><?= e($lastLogin) ?></span>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-2xl border border-[#E4E7EC] p-6 shadow-2xs space-y-4 text-xs">
      <h3 class="font-heading font-bold text-sm text-[#202124]">Dispositivos Conectados</h3>
      <div class="space-y-3">
        <?php if ($logins === []): ?>
          <p class="text-[#697386]">Nenhum acesso registrado.</p>
        <?php endif; ?>
        <?php foreach ($logins as $login):
            $device = User::parseUserAgent((string) $login['user_agent']);
            $isCurrent = hash_equals((string) $login['session_hash'], $currentSessionHash);
            $ts = strtotime((string) $login['created_at']) ?: time();
            $when = date('Y-m-d', $ts) === date('Y-m-d') ? 'Hoje às ' . date('H:i', $ts) : date('d/m/Y \à\s H:i', $ts); ?>
          <div class="flex items-center gap-3 p-3 rounded-xl bg-[#F5F6F8]">
            <?= $device['mobile'] ? icon('Smartphone', 'w-5 h-5 shrink-0 ' . ($isCurrent ? 'text-[#D71920]' : 'text-[#697386]')) : icon('Laptop', 'w-5 h-5 shrink-0 ' . ($isCurrent ? 'text-[#D71920]' : 'text-[#697386]')) ?>
            <div class="flex-1 min-w-0">
              <div class="font-bold text-[#202124] truncate"><?= e($device['device']) ?><?= $isCurrent ? ' (Sessão Atual)' : '' ?></div>
              <?php if ($isCurrent): ?>
                <div class="text-[10px] text-emerald-700 font-semibold">IP <?= e($login['ip_address']) ?> · Ativo agora</div>
              <?php else: ?>
                <div class="text-[10px] text-[#697386]">IP <?= e($login['ip_address']) ?> · <?= e($when) ?></div>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <div class="lg:col-span-8 space-y-6">
    <div class="bg-white rounded-2xl border border-[#E4E7EC] p-6 shadow-2xs space-y-5">
      <div class="pb-3 border-b border-[#E4E7EC]">
        <h3 class="font-heading font-bold text-base text-[#202124]">Informações Pessoais &amp; Cadastro</h3>
        <p class="text-xs text-[#697386]">Dados cadastrais utilizados no sistema interno e nas assinaturas de ordens de serviço</p>
      </div>

      <form method="post" action="/painel/perfil" class="space-y-4 text-xs">
        <?= csrf_field() ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold text-[#202124] mb-1" for="p-name">Nome Completo *</label>
            <input id="p-name" type="text" name="name" required maxlength="150" value="<?= e($user['name']) ?>" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]">
          </div>
          <div>
            <label class="block text-xs font-semibold text-[#202124] mb-1" for="p-email">E-mail Corporativo *</label>
            <input id="p-email" type="email" name="email" required maxlength="190" value="<?= e($user['email']) ?>" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]">
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div>
            <label class="block text-xs font-semibold text-[#202124] mb-1" for="p-phone">Telefone Direto</label>
            <input id="p-phone" type="text" name="phone" maxlength="40" value="<?= e($user['phone']) ?>" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124]">
          </div>
          <div>
            <label class="block text-xs font-semibold text-[#202124] mb-1" for="p-role">Nível de Acesso (Role)</label>
            <select id="p-role" name="role" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124]">
              <?php foreach (User::ROLES as $value => $label): ?>
                <option value="<?= e($value) ?>"<?= $user['role'] === $value ? ' selected' : '' ?>><?= e($label) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="block text-xs font-semibold text-[#202124] mb-1" for="p-dept">Departamento</label>
            <input id="p-dept" type="text" name="department" maxlength="150" value="<?= e($user['department']) ?>" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124]">
          </div>
        </div>

        <div class="pt-3 border-t border-[#E4E7EC] space-y-3">
          <span class="font-heading font-bold text-xs text-[#202124] uppercase tracking-wider block">Alertas &amp; Notificações</span>
          <div class="space-y-2">
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" name="notify_email_new_lead" value="1"<?= $user['notify_email_new_lead'] ? ' checked' : '' ?> class="accent-[#D71920]">
              <span class="text-[#202124]">Receber e-mail instantâneo a cada novo lead recebido no site</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" name="notify_whatsapp_alerts" value="1"<?= $user['notify_whatsapp_alerts'] ? ' checked' : '' ?> class="accent-[#D71920]">
              <span class="text-[#202124]">Ativar pré-visualização de mensagem para disparo no WhatsApp</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" name="notify_browser_sound" value="1"<?= $user['notify_browser_sound'] ? ' checked' : '' ?> class="accent-[#D71920]">
              <span class="text-[#202124]">Alerta sonoro no navegador ao receber nova solicitação</span>
            </label>
          </div>
        </div>

        <div class="pt-2 text-right">
          <button type="submit" class="px-5 py-2.5 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold transition-colors inline-flex items-center gap-2 shadow-xs cursor-pointer">
            <?= icon('Save', 'w-3.5 h-3.5') ?>
            <span>Salvar Dados do Perfil</span>
          </button>
        </div>
      </form>
    </div>

    <div class="bg-white rounded-2xl border border-[#E4E7EC] p-6 shadow-2xs space-y-5">
      <div class="pb-3 border-b border-[#E4E7EC]">
        <h3 class="font-heading font-bold text-base text-[#202124]">Alteração de Senha</h3>
        <p class="text-xs text-[#697386]">Para sua segurança, utilize senhas com letras maiúsculas, minúsculas, números e caracteres especiais</p>
      </div>

      <form method="post" action="/painel/perfil/senha" class="space-y-4 text-xs" x-data @submit="
        const f = $event.target;
        if (f.new_password.value.length < 6) { $event.preventDefault(); toast('A nova senha deve possuir pelo menos 6 caracteres.', 'warning'); return; }
        if (f.new_password.value !== f.confirm_password.value) { $event.preventDefault(); toast('A confirmação de senha não confere.', 'error'); }
      ">
        <?= csrf_field() ?>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div>
            <label class="block text-xs font-semibold text-[#202124] mb-1" for="pw-current">Senha Atual *</label>
            <input id="pw-current" type="password" name="current_password" required autocomplete="current-password" placeholder="••••••••" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white text-xs">
          </div>
          <div>
            <label class="block text-xs font-semibold text-[#202124] mb-1" for="pw-new">Nova Senha *</label>
            <input id="pw-new" type="password" name="new_password" required autocomplete="new-password" placeholder="Mínimo 6 dígitos" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white text-xs">
          </div>
          <div>
            <label class="block text-xs font-semibold text-[#202124] mb-1" for="pw-confirm">Confirmar Nova Senha *</label>
            <input id="pw-confirm" type="password" name="confirm_password" required autocomplete="new-password" placeholder="Repita a senha" class="w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white text-xs">
          </div>
        </div>

        <div class="pt-2 text-right">
          <button type="submit" class="px-5 py-2.5 rounded-lg bg-neutral-900 hover:bg-neutral-800 text-white text-xs font-semibold transition-colors inline-flex items-center gap-2 cursor-pointer shadow-xs">
            <?= icon('KeyRound', 'w-3.5 h-3.5') ?>
            <span>Atualizar Senha</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
