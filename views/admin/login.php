<div class="min-h-screen flex flex-col items-center justify-center px-4 py-12">
  <div class="w-full max-w-md space-y-6">
    <a href="/" class="flex justify-center" aria-label="Voltar ao site">
      <?= partial('brand-logo', ['class' => 'h-16', 'eager' => true]) ?>
    </a>

    <div class="bg-white rounded-2xl border border-[#E4E7EC] p-6 sm:p-8 shadow-sm space-y-6">
      <div class="space-y-1 text-center">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#F5F6F8] text-xs font-semibold text-[#D71920] border border-[#E4E7EC]">
          <?= icon('Lock', 'w-3.5 h-3.5') ?>
          <span>Acesso Restrito</span>
        </div>
        <h1 class="font-heading font-extrabold text-xl text-[#202124] pt-2">Painel de Gestão Técnica</h1>
        <p class="text-xs text-[#697386]">Entre com suas credenciais para acessar ordens de serviço, relatórios e configurações.</p>
      </div>

      <form method="post" action="/painel/login" class="space-y-4" x-data="{ show: false, sending: false }" @submit="sending = true">
        <?= csrf_field() ?>
        <div>
          <label for="login-email" class="block text-xs font-semibold text-[#202124] mb-1.5">E-mail corporativo</label>
          <input id="login-email" type="email" name="email" required autofocus autocomplete="username" value="<?= e(old('email')) ?>" placeholder="seu.email@empresa.com.br" class="w-full px-3.5 py-2.5 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]">
        </div>
        <div>
          <label for="login-password" class="block text-xs font-semibold text-[#202124] mb-1.5">Senha</label>
          <div class="relative">
            <input id="login-password" :type="show ? 'text' : 'password'" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" class="w-full pl-3.5 pr-10 py-2.5 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]">
            <button type="button" @click="show = !show" class="absolute right-2.5 top-1/2 -translate-y-1/2 p-1 text-[#697386] hover:text-[#202124]" :aria-label="show ? 'Ocultar senha' : 'Mostrar senha'">
              <span x-show="!show"><?= icon('Eye', 'w-4 h-4') ?></span>
              <span x-show="show" x-cloak><?= icon('EyeOff', 'w-4 h-4') ?></span>
            </button>
          </div>
        </div>

        <button type="submit" :disabled="sending" class="w-full py-3 px-6 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white font-semibold text-xs tracking-wide transition-all shadow-xs flex items-center justify-center gap-2 cursor-pointer disabled:opacity-70">
          <?= icon('LogIn', 'w-4 h-4') ?>
          <span>Entrar no painel</span>
        </button>
      </form>
    </div>

    <div class="text-center">
      <a href="/" class="inline-flex items-center gap-2 text-xs font-semibold text-[#697386] hover:text-[#D71920] transition-colors">
        <?= icon('ArrowLeft', 'w-4 h-4') ?>
        <span>Voltar para o site</span>
      </a>
    </div>
  </div>
</div>
