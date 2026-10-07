<?php
/*
 * Google Tag Manager / GA4 / Google Ads — carregados somente em produção e
 * depois que o visitante aceita os cookies (LGPD). IDs vêm de Configurações > SEO.
 */
if (config('app.env') !== 'production' || ($_COOKIE['pcresolve_cookie_consent'] ?? '') !== 'true') {
    return;
}

$gtm = setting('gtmContainerId');
$ga4 = setting('ga4MeasurementId');
$ads = setting('googleAdsConversionId');
$gtagIds = array_values(array_filter([$ga4, $ads], fn ($id) => preg_match('/^(G|AW)-[A-Z0-9]+$/i', $id)));
?>
<?php if (preg_match('/^GTM-[A-Z0-9]+$/i', $gtm)): ?>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer',<?= json_script($gtm) ?>);</script>
<?php endif; ?>
<?php if ($gtagIds !== []): ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= e($gtagIds[0]) ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  <?php foreach ($gtagIds as $id): ?>gtag('config', <?= json_script($id) ?>);
  <?php endforeach; ?>
</script>
<?php endif; ?>
