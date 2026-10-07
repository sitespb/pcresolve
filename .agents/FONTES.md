---
name: fontes-painel-admin
description: Padrão de tipografia, tamanhos, cards, bordas, ícones, botões, tabelas e modais do painel administrativo do PC Resolve. Use ao criar ou ajustar telas admin ou ao replicar o visual em outro projeto.
---

# Padrões do Painel Administrativo (fontes, tamanhos e formatações)

Documento extraído de `views/layouts/admin.php` e das telas em `views/admin/` (login, dashboard, analytics, requests, services, cities, testimonials, faq, settings, profile).

Stack: PHP + Alpine.js + Tailwind CSS v4 (tema em `resources/css/app.css`, compilado para `public/assets/css/app.css`).
Todos os valores abaixo são a escala padrão do Tailwind v4, em `px` (1rem = 16px).

---

## 1. Fontes e tokens globais

| Token | Família | Uso |
|---|---|---|
| `--font-sans` | Inter (300, 400, 500, 600, 700) | Texto corrido, labels, tabelas, botões |
| `--font-heading` (`font-heading`) | Manrope (500, 600, 700, 800) | Títulos `h1`–`h6`, números de métricas, títulos de modal |
| `--font-mono` (`font-mono`, `font-mono-numbers`) | JetBrains Mono (400, 500, 600) | Protocolos, valores em R$, números tabulares |

Carregamento (`views/partials/head.php`):
```html
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Manrope:wght@500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
```

Regras do tema (`resources/css/app.css`):
- `html` usa `font-family: var(--font-sans)`.
- `h1…h6` e `.font-heading` usam Manrope.
- `.font-mono-numbers` usa JetBrains Mono com `font-variant-numeric: tabular-nums`.

Cores da marca (tokens `--color-brand-*`):

| Token | Hex | Uso |
|---|---|---|
| brand-red | `#D71920` | Ação principal, item ativo, destaques, foco de input |
| brand-darkred | `#A90F17` | Hover do botão principal |
| brand-dark | `#202124` | Texto principal |
| brand-gray | `#697386` | Texto secundário, labels de cabeçalho de tabela |
| brand-bg | `#F5F6F8` | Fundo de cabeçalho de tabela, cards secundários, trilhos de filtro |
| brand-border | `#E4E7EC` | Todas as bordas |
| brand-whatsapp | `#25D366` | Ícone/indicador do WhatsApp |

Fundo da área de conteúdo: `bg-[#F5F6F8]`. Cards e modais: `bg-white`.

Cores de status (badges e cards de métrica): emerald (sucesso/ativo), amber (pendente/atenção), blue (em execução), red (erro/excluir), neutral (pausado/inativo).

---

## 2. Escala tipográfica

| Papel | Classes | Tamanho |
|---|---|---|
| Título da página (topo, `h1`) | `font-heading font-extrabold text-base sm:text-lg tracking-tight truncate` | 16px / 18px (sm+) |
| Subtítulo da página | `text-[11px] text-[#697386] hidden sm:block truncate` | 11px |
| Título de seção (card grande) | `font-heading font-bold text-base text-[#202124]` | 16px |
| Título de card / modal | `font-heading font-bold text-sm text-[#202124]` | 14px |
| Título de bloco pequeno (h4) | `font-heading font-bold text-xs uppercase tracking-wider` | 12px |
| Título de card de FAQ / item | `font-heading font-bold text-sm` | 14px |
| Título de login | `font-heading font-extrabold text-xl` | 20px |
| Subtítulo de seção / descrição | `text-xs text-[#697386]` | 12px |
| Label de campo | `block text-xs font-semibold text-[#202124] mb-1` | 12px |
| Texto de tabela | `text-xs` (`w-full text-left text-xs`) | 12px |
| Cabeçalho de tabela | `text-[#697386] uppercase tracking-wider text-[10px]` | 10px |
| Legenda de grupo / label de seção do menu | `text-[10px] font-bold uppercase tracking-wider text-[#697386]` | 10px |
| Texto auxiliar / hint / data | `text-[10px] text-[#697386]` ou `text-[11px] text-[#697386]` | 10px / 11px |
| Métrica (valor grande) | `font-heading font-extrabold text-2xl text-[#202124] font-mono-numbers` | 24px |
| Valor monetário / protocolo | `font-mono font-bold` (+ `text-[#D71920]` para protocolo) | herda (12px na tabela) |
| Texto de badge | `text-[10px] font-semibold` | 10px |
| Botão principal / secundário | `text-xs font-semibold` | 12px |
| Item do menu lateral | `text-xs font-semibold` | 12px |
| Nome do usuário (rodapé do menu) | `text-xs font-bold` | 12px |
| Cargo / e-mail do usuário | `text-[10px]` ou `text-xs` | 10px / 12px |
| Destaque de nome em modal | `font-bold text-sm` | 14px |
| Protocolo no modal | `font-mono font-bold text-lg text-[#D71920]` | 18px |
| Corpo em texto longo (relato, FAQ) | `text-xs leading-relaxed` | 12px |

Peso: títulos `font-bold`/`font-extrabold`, labels e botões `font-semibold`, valores `font-bold`, texto comum sem peso extra.

---

## 3. Layout geral (`views/layouts/admin.php`)

| Elemento | Classes principais |
|---|---|
| Container raiz | `min-h-screen bg-[#F5F6F8] flex flex-col md:flex-row` |
| Barra superior mobile | `md:hidden bg-white border-b border-[#E4E7EC] px-4 py-3` |
| Sidebar | `w-64 bg-white border-r border-[#E4E7EC]`, `fixed md:sticky inset-y-0`, `z-40` |
| Logo na sidebar | `h-10`, bloco com `p-5 border-b border-[#E4E7EC]` |
| Selo "Painel de Gestão Técnica" | bolinha `w-2 h-2` verde, texto 10px uppercase |
| Bloco de navegação | `p-3 space-y-1` |
| Item de menu | `px-3 py-2.5 rounded-xl text-xs font-semibold`, espaço entre ícone e texto `gap-2.5` |
| Item ativo | `bg-[#D71920] text-white shadow-xs` |
| Item inativo | `text-[#202124] hover:bg-[#F5F6F8] hover:text-[#D71920]` |
| Ícone do menu | `w-4 h-4` (cor `text-[#697386]`, ativo `text-white`) |
| Badge de contagem no menu | `px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-[#D71920] text-white` |
| Botão "Ver Site Público" | `border border-dashed border-[#E4E7EC] rounded-xl px-3 py-2 text-xs font-semibold` |
| Rodapé do usuário | `p-4 border-t`, avatar `w-9 h-9 rounded-full`, ícones de ação `w-4 h-4` |
| Cabeçalho da área de conteúdo | `h-16 bg-white border-b border-[#E4E7EC] px-6 sticky top-0 z-20` |
| Área de conteúdo | `p-4 sm:p-6 lg:p-8 space-y-6` |
| Espaço entre blocos da página | `space-y-6` (grid de cards usa `gap-4` ou `gap-6`) |

Larguras de tela (breakpoints Tailwind): sidebar some abaixo de `md` (768px); grids passam de 1 coluna para 2 em `sm` (640px), 3–4 em `lg` (1024px).

---

## 4. Cards e bordas

Regras gerais:
- Raio: `rounded-2xl` (16px) para cards e modais; `rounded-xl` (12px) para blocos internos, filtros e inputs de destaque; `rounded-lg` (8px) para botões e inputs; `rounded-md` (6px) para botões pequenos de ícone; `rounded-full` para badges e avatares.
- Borda: `border border-[#E4E7EC]` (1px) em todo card, input, select e botão secundário.
- Sombra: `shadow-2xs` (cards de listagem e métricas), `shadow-xs` (botão principal e item ativo), `shadow-2xl` (modais).

| Tipo de card | Classes |
|---|---|
| Métrica (KPI) | `bg-white p-5 rounded-2xl border border-[#E4E7EC] shadow-2xs space-y-2` |
| Seção grande (tabela/gráfico) | `bg-white rounded-2xl border border-[#E4E7EC] p-6 shadow-2xs space-y-4` |
| Tabela com cabeçalho | `bg-white rounded-2xl border border-[#E4E7EC] shadow-2xs overflow-hidden` (dentro, `overflow-x-auto`) |
| Barra de busca/filtro | `bg-white rounded-2xl border border-[#E4E7EC] p-4 shadow-2xs` |
| Card de item (FAQ) | `bg-white rounded-2xl border border-[#E4E7EC] p-5 shadow-2xs` |
| Card de avaliação | `bg-white rounded-2xl border border-[#E4E7EC] p-5 shadow-2xs flex flex-col justify-between` |
| Card de destaque (acesso rápido) | `bg-[#F5F6F8] rounded-2xl border border-[#E4E7EC] p-5` |
| Sub-bloco dentro de card | `p-3.5 rounded-xl bg-[#F5F6F8] border border-[#E4E7EC]` |
| Caixa de dica | `p-4 rounded-xl bg-[#F5F6F8] border border-[#E4E7EC] space-y-2` |
| Alerta de erro | `p-4 rounded-xl bg-red-50 border border-red-200 text-red-900` |

Métrica (KPI) com ícone:
- Cabeçalho `flex items-center justify-between text-xs text-[#697386]`.
- Ícone em caixa `w-8 h-8 rounded-lg` com fundo claro (`bg-amber-50 text-amber-600`, `bg-blue-50 text-blue-600`, `bg-emerald-50 text-emerald-600`, `bg-[#F5F6F8] text-[#D71920]`).
- Valor `text-2xl`, complemento `text-[11px] font-medium` na cor do tema.
- Linha de apoio `text-[11px] text-[#697386]`.

Separadores: `border-b border-[#E4E7EC]` no cabeçalho de seção (`pb-3`), `border-t border-[#E4E7EC]` no rodapé (`pt-3`). Divisor de linhas de tabela: `divide-y divide-[#E4E7EC]`.

---

## 5. Ícones

Ícones são SVG Lucide via helper PHP `icon('Nome', 'classes')`.

| Contexto | Tamanho |
|---|---|
| Ícone de menu lateral | `w-4 h-4` |
| Ícone do botão do cabeçalho / ação de tabela | `w-3.5 h-3.5` (14px) |
| Ícone de botão de ícone (fechar, sino, menu mobile) | `w-5 h-5` (20px) ou `w-4 h-4` |
| Ícone dentro de caixa KPI | `w-4 h-4` em caixa `w-8 h-8 rounded-lg` |
| Ícone de badge pequeno (status) | `w-3 h-3` |
| Ícone de estrela (avaliação) | `w-3.5 h-3.5 fill-amber-500` |
| Ícone de login/senha | `w-4 h-4` |
| Ícone de foto (avatar) | `w-5 h-5` |

Cores de ícone: secundário `text-[#697386]`, de destaque `text-[#D71920]`, sobre botão primário `text-white`.

Ícones de ação em tabela ficam em botão quadrado: `p-1.5 rounded-md` (ou `p-2 rounded-lg` em cards).

---

## 6. Botões

| Variante | Classes |
|---|---|
| Principal (cabeçalho, salvar) | `inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-[#D71920] hover:bg-[#A90F17] text-white text-xs font-semibold shadow-xs transition-colors cursor-pointer` |
| Principal de formulário (modal, salvar) | `px-5 py-2 rounded-lg bg-[#D71920] text-white text-xs font-semibold shadow-xs` (em login/perfil: `px-5 py-2.5`) |
| Secundário com borda | `inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-[#E4E7EC] bg-white hover:bg-neutral-50 text-xs font-semibold text-[#202124]` |
| Cancelar (modal) | `px-4 py-2 rounded-lg border border-[#E4E7EC] text-xs font-semibold text-[#697386] hover:bg-neutral-50` |
| Ação de ícone (editar) | `p-1.5 rounded-lg border border-[#E4E7EC] hover:bg-neutral-100 text-[#202124]` |
| Ação de ícone (excluir) | `p-1.5 rounded-lg border border-[#E4E7EC] hover:bg-red-50 text-red-600` |
| Aprovar (sucesso) | `px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold` |
| WhatsApp (ícone) | `p-1.5 rounded-md bg-emerald-50 text-emerald-700 hover:bg-emerald-100` |
| Ver/abrir (ícone) | `p-1.5 rounded-md bg-neutral-100 text-[#202124] hover:bg-neutral-200` |
| Botão de link de texto | `text-xs font-semibold text-[#D71920] hover:underline` |
| Botão escuro (senha) | `bg-neutral-900 hover:bg-neutral-800 text-white text-xs font-semibold` |
| Botão de login (largura total) | `w-full py-3 px-6 rounded-lg bg-[#D71920] text-white font-semibold text-xs tracking-wide shadow-xs` |
| Botão desabilitado | `disabled:opacity-50 disabled:cursor-not-allowed` |

Todo botão tem `cursor-pointer` e `transition-colors`.

Botões pequenos dentro de tabela: `px-2.5 py-1 rounded-md border text-[11px] font-semibold`.

---

## 7. Badges e status (pílulas)

| Tipo | Classes |
|---|---|
| Status de O.S. (padrão) | `inline-block px-2.5 py-0.5 rounded-full text-[10px] font-semibold border` + cor vinda de `lead_status_badge()` |
| Status ativo/inativo (botão-pílula) | `inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold border` |
| Ativo | `bg-emerald-50 text-emerald-800 border-emerald-200` |
| Pausado/Inativo | `bg-neutral-100 text-neutral-600 border-neutral-300` |
| Aguardando aprovação | `bg-amber-50 text-amber-800 border-amber-200` |
| Disponível (entrega) | `text-[11px] text-emerald-700 font-semibold bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200` |
| Categoria (FAQ) | `text-[10px] font-bold px-2 py-0.5 rounded-full bg-[#F5F6F8] text-[#D71920] border border-[#E4E7EC]` |
| Perfil/cargo | `px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#F5F6F8] text-[#D71920] border` |
| Contador de leads (gráfico) | `text-[10px] font-bold bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200` |

---

## 8. Tabelas

Estrutura padrão:
```html
<div class="bg-white rounded-2xl border border-[#E4E7EC] shadow-2xs overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-left text-xs">
      <thead>
        <tr class="bg-[#F5F6F8] border-b border-[#E4E7EC] text-[#697386] uppercase tracking-wider text-[10px]">
          <th class="py-3 px-4 font-bold">…</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-[#E4E7EC]">
        <tr class="hover:bg-[#F5F6F8]/60 transition-colors">
          <td class="py-3 px-4">…</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
```

Variantes:
- Tabela dentro de card (sem fundo cinza no cabeçalho): cabeçalho `border-b border-[#E4E7EC] text-[#697386] uppercase tracking-wider text-[10px]` e células `py-2.5 px-3`.
- Células de cabeçalho com `font-bold`; ação alinhada com `text-right`.
- Espaço de célula: `py-3 px-4` (listagem principal), `py-3.5 px-4` (com miniatura), `py-3 px-3` (tabelas em card).
- Célula com duas linhas: título `font-bold text-[#202124]` + subtítulo `text-[11px] text-[#697386]` (ou `text-[10px]`).
- Linha vazia: `<tr><td colspan="N" class="py-12 text-center text-[#697386]">Mensagem</td></tr>`.
- Miniatura de serviço: `w-10 h-10 rounded-lg object-cover border border-[#E4E7EC]`.
- Paginação: `flex items-center justify-between gap-3 px-4 py-3 border-t border-[#E4E7EC] text-xs text-[#697386]`, botões `px-3 py-1.5 rounded-lg border`.

Barras de progresso dentro de tabela: `w-16 h-1.5 bg-[#F5F6F8] rounded-full border`, preenchimento `bg-[#D71920] rounded-full`.

---

## 9. Formulários e inputs

| Elemento | Classes |
|---|---|
| Input de texto / número / select | `w-full px-3 py-2 rounded-lg border border-[#E4E7EC] bg-white text-xs text-[#202124] focus:outline-none focus:border-[#D71920]` |
| Input de login | `w-full px-3.5 py-2.5 rounded-lg border border-[#E4E7EC] bg-white text-xs` |
| Input com ícone à esquerda (busca) | `w-full pl-9 pr-3.5 py-2 text-xs rounded-xl border border-[#E4E7EC] focus:border-[#D71920]`, ícone `w-4 h-4 absolute left-3.5` |
| Textarea | igual ao input, `rows="2"` a `"3"` |
| Campo numérico com monetário | `font-mono` no input |
| Label | `block text-xs font-semibold text-[#202124] mb-1` |
| Dica abaixo do campo | `text-[10px] text-[#697386] mt-1 block` |
| Checkbox / radio | `accent-[#D71920]`, `mt-0.5` quando há texto em duas linhas |
| Opção de checkbox em cartão | `flex items-start gap-2.5 p-3 rounded-xl border border-[#E4E7EC] bg-white hover:border-[#D71920]/40` |
| Slider | `w-full accent-[#D71920]` |

Grids de formulário: `grid grid-cols-1 sm:grid-cols-2 gap-4` (ou `sm:grid-cols-3`, `sm:grid-cols-4`), com `gap-3` em modais estreitos. Formulários usam `text-xs` no container (`space-y-3` a `space-y-4`).

---

## 10. Modais

Estrutura:
```html
<div class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto" @click.self="…">
  <div class="bg-white rounded-2xl border border-[#E4E7EC] max-w-lg w-full p-6 shadow-2xl space-y-4 my-auto" role="dialog" aria-modal="true">
    <div class="flex items-center justify-between pb-3 border-b border-[#E4E7EC]">
      <h3 class="font-heading font-bold text-sm text-[#202124]">Título</h3>
      <button class="text-[#697386] hover:text-[#202124]" aria-label="Fechar"><!-- X w-5 h-5 --></button>
    </div>
    …
    <div class="pt-3 border-t border-[#E4E7EC] flex justify-end gap-2">…botões…</div>
  </div>
</div>
```

Larguras: `max-w-sm` (ajuste de taxa), `max-w-md` (depoimento), `max-w-lg` (serviço, FAQ), `max-w-xl` (nova O.S.), `max-w-2xl` (gerenciar O.S.).
Título: `text-sm` em modais simples, `text-base` em modais principais.
Fechar com `Escape` via `@keydown.escape.window`.

---

## 11. Abas e segmentação

- Abas de página (Configurações): container `flex gap-2 border-b border-[#E4E7EC] px-6 pt-3 bg-[#F5F6F8]`; aba `px-4 py-3 text-xs font-semibold border-b-2`; ativa `border-[#D71920] text-[#D71920] bg-white rounded-t-xl shadow-2xs`; inativa `border-transparent text-[#697386]`.
- Filtro segmentado (Solicitações, Analytics, Dashboard): trilho `flex items-center gap-1 p-1 bg-[#F5F6F8] rounded-xl border border-[#E4E7EC] overflow-x-auto`; item `px-3 py-1.5 text-xs font-semibold rounded-lg`; ativo `bg-white text-[#D71920] shadow-2xs`.
- Segmentado menor (dashboard): item `px-2.5 py-1 text-[11px] font-semibold rounded-md`.

---

## 12. Barras de progresso e gráficos simples

- Barra de progresso fina: `w-full h-1.5 bg-[#F5F6F8] rounded-full overflow-hidden`, preenchimento `h-full bg-[#D71920] rounded-full`.
- Barra de funil: `h-2.5 border border-[#E4E7EC]/60`, preenchimento `bg-gradient-to-r from-[#D71920] to-[#A90F17]`.
- Barras verticais do gráfico de acessos: coluna `w-full h-36 bg-[#F5F6F8] rounded-xl p-2 border border-[#E4E7EC]`, barra `bg-[#D71920] rounded-lg`, tooltip `bg-[#202124] text-white text-[10px] py-1 px-2 rounded font-mono`.
- Legendas: quadrados `w-3 h-3 rounded-sm`.

---

## 13. Espaçamentos recorrentes

| Uso | Valor |
|---|---|
| Espaço entre blocos da página | `space-y-6` |
| Espaço interno de card | `p-5` (KPI, item de lista), `p-6` (seção, modal) |
| Espaço interno de sub-bloco | `p-3`, `p-3.5`, `p-4` |
| Gap entre colunas de grid | `gap-4` (cards), `gap-6` (layout principal) |
| Gap entre ícone e texto | `gap-1.5` (botões pequenos), `gap-2` / `gap-2.5` (menu) |
| Padding de célula de tabela | `py-3 px-4` ou `py-3 px-3` |
| Altura do cabeçalho | `h-16` |

Layouts de grade usados: KPI `grid-cols-1 sm:grid-cols-2 lg:grid-cols-4`; principal com colunas assimétricas `lg:grid-cols-12` (`lg:col-span-8` + `lg:col-span-4`, ou 6+6); cards de avaliação `md:grid-cols-2 lg:grid-cols-3`.

---

## 14. Estados e mensagens

- Vazio: `py-12 text-center text-[#697386]` em linha de tabela, ou `p-10 text-center text-xs text-[#697386]` em card.
- Notificações: `toast(mensagem, tipo)` (tipos `info`, `success`, `warning`, `error`).
- Confirmação de exclusão: `onsubmit="return confirm('…')"`.
- Elementos que o Alpine controla entram com `x-cloak` (CSS: `display: none !important` até inicializar).

---

## 15. Checklist para replicar em outro projeto

1. Instale Tailwind v4 e copie os tokens de `resources/css/app.css` (`--color-brand-*`, `--font-heading`, `--font-sans`, `--font-mono`) e as regras `h1…h6`, `.font-heading`, `.font-mono-numbers` e `[x-cloak]`.
2. Carregue Inter, Manrope e JetBrains Mono (link do Google Fonts acima).
3. Use o fundo `#F5F6F8` na área de conteúdo e `#E4E7EC` em todas as bordas.
4. Replique a escala: títulos `text-base`/`text-sm`, texto `text-xs`, cabeçalho de tabela e legendas `text-[10px]`, subtítulos `text-[11px]`, métrica `text-2xl`.
5. Cards com `rounded-2xl border border-[#E4E7EC] shadow-2xs`; botões `rounded-lg text-xs font-semibold`; inputs `rounded-lg px-3 py-2 text-xs` com foco `#D71920`.
6. Modais: overlay `bg-black/50 backdrop-blur-xs`, painel `rounded-2xl p-6 shadow-2xl`, cabeçalho com `border-b`.
7. Recompile o CSS (`npm run build`) após criar classes novas, porque o Tailwind gera apenas o que encontra nos arquivos listados em `@source`.

---

## 16. Inconsistências observadas (escolha uma antes de replicar)

O painel não é 100% uniforme. Ao replicar, adote os valores dominantes abaixo:

| Item | Variações encontradas | Recomendado |
|---|---|---|
| Título de modal | `text-sm` e `text-base` | `text-sm` em modais simples; `text-base` no principal |
| Padding de botão principal | `py-1.5`, `py-2`, `py-2.5` | `py-2` em formulários; `py-1.5` no cabeçalho |
| Padding de célula | `py-2.5 px-3`, `py-3 px-3`, `py-3 px-4`, `py-3.5 px-4` | `py-3 px-4` (listagem principal) |
| Raio de inputs | `rounded-lg` e `rounded-xl` | `rounded-lg` (busca pode usar `rounded-xl`) |
| Sombra de card | `shadow-2xs` e sem sombra | `shadow-2xs` |
| Largura de modal | `max-w-sm` a `max-w-2xl` | conforme o conteúdo |
| Cor do botão de login secundário | `bg-neutral-900` (perfil) e `bg-[#D71920]` | `bg-[#D71920]` como padrão |
