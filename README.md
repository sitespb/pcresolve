# PC Resolve — Assistência Técnica em Informática

Site institucional e painel administrativo da PC Resolve (João Pessoa - PB), em **PHP 8.3 + MySQL**.
Migrado da versão React/Vite, com o mesmo layout e os mesmos recursos.

## Recursos

**Site público:** Início, catálogo de serviços (filtro por categoria e busca), detalhe do serviço, Sobre,
Cidades Atendidas (Leva e Traz), Contato, Termos de Uso e Política de Privacidade (LGPD).
Também traz formulários de orçamento com protocolo (`OS-AAAA-NNNN`), botão flutuante do WhatsApp,
banner de cookies, `sitemap.xml` e `robots.txt`.

**Painel (`/painel`, com login):** Dashboard, Relatórios & Estatísticas (exportação CSV e impressão),
Ordens de Serviço & Leads (status, orçamento, anotações, aviso por WhatsApp e cadastro manual),
Catálogo de Serviços, Cidades & taxas de Leva e Traz, moderação de Depoimentos, FAQ,
Perfil do Usuário (dados, notificações e senha) e Configurações (empresa, SEO, WhatsApp e LGPD).

## Estrutura

```
app/            Código PHP (controllers, models, roteador, helpers)
bin/install.php Instalador: tabelas, dados iniciais e usuário admin
database/       schema.sql e dados iniciais (seed-data.php)
deploy/         Regras de URL do Nginx para o aaPanel
public/         Raiz pública (index.php, CSS/JS compilados, imagens)
resources/css/  Fonte do Tailwind CSS v4
storage/        Logs e sessões (precisa de permissão de escrita)
views/          Templates PHP (layouts, páginas públicas e do painel)
```

Interações no navegador usam **Alpine.js** (arquivo local em `public/assets/js`), sem CDN.

## Ambiente local (Laragon)

1. Crie o banco `pcresolve` e copie `.env.example` para `.env` com `APP_ENV=local`, `APP_DEBUG=true` e os dados do MySQL.
2. Rode `php bin/install.php`; ele mostra o e-mail e a senha do administrador.
3. Acesse `http://pcresolve.test`. O `.htaccess` da raiz encaminha tudo para `public/`.

### CSS (Tailwind)

Só é necessário ao **alterar classes** nas views:

```bash
npm install
npm run build   # gera public/assets/css/app.css (versionado no Git)
npm run dev     # recompila a cada alteração
```

## Produção

Veja o passo a passo em **[DEPLOY.md](DEPLOY.md)** (Ubuntu + aaPanel + PHP 8.3).

## Segurança

- Senhas com `password_hash` e bloqueio após 5 tentativas de login em 15 minutos
- Token CSRF em todos os formulários; sessões `HttpOnly`/`SameSite=Lax`, e `Secure` em HTTPS
- Consultas preparadas (PDO) e saída escapada em todas as views
- Formulários públicos com honeypot anti-spam e limite de 5 envios por IP a cada 10 minutos
- Painel marcado como `noindex`; `.env`, `app/` e `views/` ficam fora da raiz pública
