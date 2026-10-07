<?php
/**
 * Logomarca em imagem (cabeçalho e rodapé do site).
 * Arquivo original: 600×168 px, fundo transparente.
 *
 * @var string|null $class Classes de altura (a largura acompanha a proporção)
 * @var bool|null   $eager Carregar imediatamente (acima da dobra)
 */
?>
<img
  src="/assets/img/logo-pcresolve-600a.png"
  alt="<?= e(setting('name', 'PC Resolve')) ?>"
  width="600"
  height="168"
  class="w-auto select-none <?= e($class ?? 'h-12') ?>"
  <?= !empty($eager) ? 'fetchpriority="high"' : 'loading="lazy"' ?>
  decoding="async"
>
