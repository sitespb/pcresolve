<?php
/**
 * Layout do site público (TopBar, Header, conteúdo, Footer, WhatsApp e cookies).
 *
 * @var string $content
 */
?>
<!doctype html>
<html lang="pt-BR">
<head>
<?= partial('head', [
    'pageTitle' => $pageTitle ?? null,
    'pageDescription' => $pageDescription ?? null,
    'ogImage' => $ogImage ?? null,
]) ?>
<?= partial('tracking') ?>
</head>
<body class="bg-white text-[#202124] antialiased selection:bg-[#D71920] selection:text-white">
  <div class="min-h-screen bg-white flex flex-col text-[#202124]">
    <?= partial('toasts') ?>
    <?= partial('topbar') ?>
    <?= partial('header', ['activeNav' => $activeNav ?? '']) ?>
    <div class="flex-1"><?= $content ?></div>
    <?= partial('footer') ?>
    <?= partial('whatsapp-button') ?>
    <?= partial('cookie-banner') ?>
  </div>
</body>
</html>
