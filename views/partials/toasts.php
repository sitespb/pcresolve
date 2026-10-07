<?php /* Notificações flutuantes (equivalente ao <ToastContainer />). */ ?>
<div x-data class="fixed top-5 right-5 z-50 flex flex-col gap-2 max-w-sm w-full pointer-events-none" aria-live="polite">
  <template x-for="toast in $store.toasts.items" :key="toast.id">
    <div
      class="pointer-events-auto p-3.5 rounded-xl border shadow-lg backdrop-blur-md flex items-center justify-between gap-3 text-xs font-medium transition-all"
      :class="{
        'border-emerald-200 bg-emerald-50/95 text-emerald-950': toast.type === 'success',
        'border-blue-200 bg-blue-50/95 text-blue-950': toast.type === 'info',
        'border-amber-200 bg-amber-50/95 text-amber-950': toast.type === 'warning',
        'border-red-200 bg-red-50/95 text-red-950': toast.type === 'error'
      }"
      x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="opacity-0 -translate-y-2"
      x-transition:enter-end="opacity-100 translate-y-0"
    >
      <div class="flex items-center gap-2.5">
        <template x-if="toast.type === 'success'"><?= icon('CheckCircle2', 'w-5 h-5 text-emerald-600 shrink-0') ?></template>
        <template x-if="toast.type === 'info'"><?= icon('Info', 'w-5 h-5 text-blue-600 shrink-0') ?></template>
        <template x-if="toast.type === 'warning'"><?= icon('AlertTriangle', 'w-5 h-5 text-amber-600 shrink-0') ?></template>
        <template x-if="toast.type === 'error'"><?= icon('XCircle', 'w-5 h-5 text-red-600 shrink-0') ?></template>
        <span x-text="toast.text"></span>
      </div>
      <button type="button" @click="$store.toasts.remove(toast.id)" class="text-neutral-400 hover:text-neutral-700 p-1 rounded transition-colors" aria-label="Fechar notificação">
        <?= icon('X', 'w-4 h-4') ?>
      </button>
    </div>
  </template>
</div>
<script>window.__TOASTS__ = <?= json_script(pull_toasts()) ?>;</script>
