<footer class="bg-[#F5F6F8] border-t border-[#E4E7EC] text-[#202124] pt-16 pb-12">
  <div class="max-w-7xl mx-auto px-4 sm:px-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
      <div class="space-y-4">
        <a href="/" class="inline-block text-left focus:outline-none cursor-pointer" aria-label="Página inicial">
          <?= partial('brand-logo', ['class' => 'h-11']) ?>
        </a>
        <p class="text-xs text-[#697386] leading-relaxed"><?= e(setting('aboutText')) ?></p>
        <div class="flex items-center gap-2 pt-2 text-[11px] text-[#697386] font-medium">
          <?= icon('ShieldCheck', 'w-4 h-4 text-[#25D366]') ?>
          <span>Garantia de 90 dias conforme CDC art. 26</span>
        </div>
      </div>

      <div>
        <h4 class="font-heading font-bold text-xs uppercase tracking-wider text-[#202124] mb-3">Principais Serviços</h4>
        <ul class="space-y-2 text-xs text-[#697386]">
          <li><a href="/servicos/manutencao-notebooks" class="hover:text-[#D71920] transition-colors text-left">Manutenção de Notebooks</a></li>
          <li><a href="/servicos/manutencao-computadores" class="hover:text-[#D71920] transition-colors text-left">Manutenção de Desktops e PCs</a></li>
          <li><a href="/servicos/upgrade-ssd-memoria" class="hover:text-[#D71920] transition-colors text-left">Upgrade de SSD NVMe e Memória RAM</a></li>
          <li><a href="/servicos/diagnostico-reparo-hardware" class="hover:text-[#D71920] transition-colors text-left">Reparo de Placa-mãe em Bancada</a></li>
          <li><a href="/servicos/limpeza-preventiva-termica" class="hover:text-[#D71920] transition-colors text-left">Limpeza Preventiva e Pasta Térmica</a></li>
          <li><a href="/servicos" class="text-[#D71920] font-semibold hover:underline mt-1 inline-block">Ver todos os serviços &rarr;</a></li>
        </ul>
      </div>

      <div>
        <h4 class="font-heading font-bold text-xs uppercase tracking-wider text-[#202124] mb-3">Áreas Atendidas (Grande JP)</h4>
        <ul class="space-y-2 text-xs text-[#697386]">
          <li><a href="/areas-atendidas" class="hover:text-[#D71920] transition-colors text-left">João Pessoa (Todos os bairros)</a></li>
          <li><a href="/areas-atendidas" class="hover:text-[#D71920] transition-colors text-left">Cabedelo (Intermares e Centro)</a></li>
          <li><a href="/areas-atendidas" class="hover:text-[#D71920] transition-colors text-left">Bayeux e Santa Rita</a></li>
          <li><a href="/areas-atendidas" class="hover:text-[#D71920] transition-colors text-left">Conde e Litoral Sul</a></li>
          <li><a href="/areas-atendidas" class="text-[#D71920] font-semibold hover:underline mt-1 inline-block">Consultar serviço Leva e Traz &rarr;</a></li>
        </ul>
      </div>

      <div>
        <h4 class="font-heading font-bold text-xs uppercase tracking-wider text-[#202124] mb-3">Canais de Atendimento</h4>
        <ul class="space-y-2.5 text-xs text-[#697386]">
          <li class="flex items-center gap-2">
            <?= icon('Phone', 'w-3.5 h-3.5 text-[#D71920] shrink-0') ?>
            <span class="font-semibold text-[#202124]"><?= e(setting('phone')) ?></span>
          </li>
          <li class="flex items-center gap-2">
            <span class="w-3.5 h-3.5 flex items-center justify-center text-[#25D366] shrink-0 font-bold">W</span>
            <span class="font-semibold text-[#202124]"><?= e(setting('whatsapp')) ?></span>
          </li>
          <li class="flex items-center gap-2">
            <?= icon('Clock', 'w-3.5 h-3.5 text-[#697386] shrink-0') ?>
            <span><?= e(setting('workingHoursWeekday')) ?></span>
          </li>
          <li class="flex items-center gap-2">
            <?= icon('Clock', 'w-3.5 h-3.5 text-[#697386] shrink-0') ?>
            <span><?= e(setting('workingHoursSaturday')) ?></span>
          </li>
          <li class="flex items-start gap-2 pt-1">
            <?= icon('MapPin', 'w-3.5 h-3.5 text-[#D71920] shrink-0 mt-0.5') ?>
            <span><?= e(setting('address')) ?> - João Pessoa/PB</span>
          </li>
        </ul>
      </div>
    </div>

    <div class="border-t border-[#E4E7EC] pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-[#697386]">
      <p>© <?= date('Y') ?> <?= e(setting('name')) ?>. CNPJ: <?= e(setting('cnpj')) ?>. Todos os direitos reservados.</p>
      <div class="flex flex-wrap items-center gap-5">
        <a href="/termos" class="hover:text-[#D71920] transition-colors">Termos de Uso</a>
        <span>·</span>
        <a href="/privacidade" class="hover:text-[#D71920] transition-colors">Política de Privacidade (LGPD)</a>
      </div>
    </div>
  </div>
</footer>
