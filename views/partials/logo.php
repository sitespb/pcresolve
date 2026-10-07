<?php
/**
 * Logo PC Resolve (equivalente ao componente <Logo />).
 *
 * @var string|null $size    sm | md | lg
 * @var string|null $variant light | dark
 * @var string|null $class
 */
$size = $size ?? 'md';
$isDark = ($variant ?? 'light') === 'dark';

$iconSizes = ['sm' => 'w-8 h-8 rounded-lg', 'md' => 'w-10 h-10 rounded-xl', 'lg' => 'w-12 h-12 rounded-xl'];
$titleSizes = ['sm' => 'text-lg', 'md' => 'text-2xl', 'lg' => 'text-3xl'];
$subSizes = ['sm' => 'text-[7.5px]', 'md' => 'text-[9px]', 'lg' => 'text-[10.5px]'];
?>
<div class="flex items-center gap-3 select-none <?= e($class ?? '') ?>">
  <div class="<?= $iconSizes[$size] ?> bg-[#D71920] flex items-center justify-center text-white shrink-0 shadow-sm relative overflow-hidden" aria-hidden="true">
    <div class="absolute inset-0 flex items-center justify-center opacity-90">
      <div class="w-full h-[2px] bg-white/40 absolute"></div>
      <div class="h-full w-[2px] bg-white/40 absolute"></div>
    </div>
    <div class="w-5 h-5 rounded-md bg-white flex items-center justify-center shadow-xs z-10">
      <svg class="w-3.5 h-3.5 text-[#D71920]" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
      </svg>
    </div>
  </div>
  <div class="flex flex-col leading-none">
    <span class="font-heading font-extrabold tracking-tight <?= $titleSizes[$size] ?> <?= $isDark ? 'text-white' : 'text-[#202124]' ?>">
      PC <span class="text-[#D71920]">RESOLVE</span>
    </span>
    <span class="<?= $subSizes[$size] ?> font-bold tracking-[0.16em] uppercase mt-0.5 <?= $isDark ? 'text-neutral-400' : 'text-[#697386]' ?>">
      Tecnologia &amp; Assistência Técnica
    </span>
  </div>
</div>
