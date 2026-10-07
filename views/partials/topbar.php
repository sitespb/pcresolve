<aside class="bg-[#F5F6F8] border-b border-[#E4E7EC] text-xs text-[#697386] py-2 px-4 sm:px-8">
  <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-3">
    <div class="flex items-center gap-6">
      <a href="tel:<?= e(digits(setting('phone'))) ?>" class="inline-flex items-center gap-1.5 hover:text-[#D71920] transition-colors">
        <?= icon('Phone', 'w-3.5 h-3.5 text-[#D71920]') ?>
        <span class="font-semibold text-[#202124]"><?= e(setting('phone')) ?></span>
      </a>
      <span class="hidden md:inline-flex items-center gap-1.5">
        <?= icon('Clock', 'w-3.5 h-3.5 text-[#697386]') ?>
        <span><?= e(setting('workingHoursWeekday')) ?> | <?= e(setting('workingHoursSaturday')) ?></span>
      </span>
    </div>

    <div class="flex items-center gap-4">
      <div class="flex items-center gap-1.5">
        <?= icon('MapPin', 'w-3.5 h-3.5 text-[#D71920]') ?>
        <span>João Pessoa - PB • Grande João Pessoa</span>
      </div>

      <?php if (is_superadmin()): ?>
        <div class="h-3.5 w-px bg-[#E4E7EC] hidden sm:block"></div>

        <a href="/painel" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#202124] hover:text-[#D71920] bg-white hover:bg-neutral-50 px-2.5 py-1 rounded-md border border-[#E4E7EC] transition-all shadow-2xs" title="Acessar o painel administrativo, estatísticas e configurações" rel="nofollow">
          <?= icon('LayoutDashboard', 'w-3.5 h-3.5 text-[#D71920]') ?>
          <span>Painel Administrativo</span>
        </a>
      <?php endif; ?>
    </div>
  </div>
</aside>
