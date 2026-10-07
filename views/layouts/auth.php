<?php /** @var string $content */ ?>
<!doctype html>
<html lang="pt-BR">
<head>
<?= partial('head', ['pageTitle' => $pageTitle ?? null, 'noindex' => true]) ?>
</head>
<body class="bg-[#F5F6F8] text-[#202124] antialiased selection:bg-[#D71920] selection:text-white">
  <?= partial('toasts') ?>
  <?= $content ?>
</body>
</html>
