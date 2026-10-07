<?php if (($_COOKIE['pcresolve_cookie_consent'] ?? '') !== 'true'): ?>
<div x-data="{ show: true }" x-show="show" class="fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-[#E4E7EC] p-4 sm:p-5 shadow-2xl">
  <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
    <div class="flex items-start sm:items-center gap-3">
      <div class="w-9 h-9 rounded-lg bg-[#F5F6F8] border border-[#E4E7EC] flex items-center justify-center text-[#D71920] shrink-0">
        <?= icon('ShieldCheck', 'w-5 h-5') ?>
      </div>
      <p class="text-xs text-[#697386] leading-relaxed">
        Utilizamos cookies essenciais e tecnologias de medição para otimizar sua experiência de navegação e aprimorar nossos atendimentos na Grande João Pessoa, em conformidade com a LGPD (Lei Geral de Proteção de Dados).
        <a href="/privacidade" class="text-[#D71920] hover:underline font-semibold">Conheça nossa Política de Privacidade</a>.
      </p>
    </div>

    <div class="flex items-center gap-3 shrink-0 w-full sm:w-auto">
      <button type="button" @click="acceptCookies(); show = false" class="w-full sm:w-auto px-5 py-2 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold transition-colors shadow-xs">
        Aceitar e Continuar
      </button>
    </div>
  </div>
</div>
<?php endif; ?>
