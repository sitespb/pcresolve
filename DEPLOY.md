# Deploy no servidor Ubuntu com aaPanel (PHP 8.3 + MySQL)

Guia para publicar o site **PC Resolve** em um servidor Linux Ubuntu gerenciado pelo aaPanel.
O servidor **não precisa de Node.js**: o CSS já vai compilado em `public/assets/css/app.css`.

## Requisitos

- aaPanel com **Nginx** (ou Apache), **PHP 8.3** e **MySQL 5.7+/8.x** (ou MariaDB 10.3+)
- Extensões PHP: `pdo_mysql`, `mbstring`, `intl` (opcional, melhora os slugs), `fileinfo`
- Acesso SSH (Terminal do aaPanel) para rodar o instalador

## 1. Criar o site e o banco

1. **Website › Add site**: informe o domínio, escolha **PHP-83** e marque **Create database (MySQL)**.
   Anote o nome do banco, o usuário e a senha gerados.
2. Abra o site criado › **Site directory**:
   - **Running directory**: selecione `/public` e salve.
   - Mantenha o *Anti-XSS attack (open_basedir)* ativado; o projeto inteiro fica dentro da pasta do site.

> **Atenção (OpenLiteSpeed/aaPanel):** depois de salvar o *Running directory*, confirme que a raiz realmente mudou.
> No arquivo `/www/server/panel/vhost/openlitespeed/detail/<DOMINIO>.conf` a primeira linha deve ser
> `docRoot $VH_ROOT/public`. Se estiver `docRoot $VH_ROOT`, o `.env`, `app/` e `database/` ficam acessíveis pela web.
> Corrija com `sed`, reinicie com `sudo /usr/local/lsws/bin/lswsctrl restart` e teste: `/.env` e `/app/config.php` devem dar 403/404.

## 2. Enviar os arquivos

O aaPanel cria a pasta do site com arquivos próprios (`.user.ini`, `index.html`, `404.html`).
Por isso o `git clone` direto na pasta falha. Use:

```bash
cd /www/wwwroot/SEU_DOMINIO
rm -f index.html 404.html
git init
git remote add origin https://github.com/sitespb/pcresolve.git
git pull origin main
```

> Alternativa sem Git: envie um `.zip` do projeto pelo **Files** do aaPanel e extraia na pasta do site.
> Não é necessário enviar `node_modules/`.

## 3. Configurar o `.env`

```bash
cp .env.example .env
nano .env
```

Preencha:

```ini
APP_ENV=production
APP_DEBUG=false
APP_URL=https://www.seudominio.com.br

DB_HOST=127.0.0.1
DB_DATABASE=nome_do_banco
DB_USERNAME=usuario_do_banco
DB_PASSWORD=senha_do_banco

ALLOW_DEMO_RESET=false
```

## 4. Instalar (tabelas, dados iniciais e usuário admin)

```bash
php83 bin/install.php
```

> Se o comando `php83` não existir, use `/www/server/php/83/bin/php bin/install.php`.

O instalador:
- cria todas as tabelas (`database/schema.sql`);
- insere os dados iniciais (serviços, cidades, FAQ, depoimentos e configurações) **somente em tabelas vazias**, então pode ser executado mais de uma vez sem apagar dados;
- cria o usuário administrador e **mostra a senha gerada na tela**.

Opções:

```bash
php83 bin/install.php --admin-email=voce@empresa.com.br --admin-password='SenhaForte#2026'
php83 bin/install.php --reset-password      # gera nova senha para o admin (esqueceu a senha)
```

## 5. Permissões

```bash
chown -R www:www storage
chmod -R 775 storage
```

`storage/logs` guarda os logs de erro (`app-AAAA-MM-DD.log`) e `storage/sessions` guarda as sessões do painel.

## 6. Regras de URL (URLs amigáveis)

- **OpenLiteSpeed** (servidor da VPS): lê o `public/.htaccess` nativamente; não há nada a configurar.
- **Nginx** (outros servidores): cole [`deploy/nginx-aapanel.conf`](deploy/nginx-aapanel.conf) em *URL rewrite*.

## 7. SSL

Site › **SSL** › **Let's Encrypt** › emita o certificado e ative **Force HTTPS**.
O cookie de sessão passa a ser enviado só por HTTPS automaticamente.

## 8. Conferência final

| Teste | Esperado |
|---|---|
| `https://seudominio/` | Página inicial |
| `https://seudominio/servicos/manutencao-notebooks` | Detalhe do serviço |
| `https://seudominio/painel` | Redireciona para o login |
| `https://seudominio/admin` | 404 (o painel não usa mais o endereço `/admin`) |
| `https://seudominio/.env` | 403/404 (nunca o conteúdo) |
| `https://seudominio/sitemap.xml` | Lista de URLs com o seu domínio |

Depois do primeiro login:
1. **Perfil do Usuário**: troque a senha e confira o e-mail.
2. **Configurações › SEO & Rastreamento**: informe os IDs reais do GA4, GTM e Google Ads, ou apague os de demonstração.
   As tags só carregam em produção e depois que o visitante aceita os cookies.
3. **Configurações › Dados da Empresa**: revise telefone, WhatsApp, endereço e e-mail.

## Atualizações futuras

```bash
cd /www/wwwroot/SEU_DOMINIO
git pull origin main
php83 bin/install.php     # cria tabelas novas, se houver; não apaga dados
```

## Backup

No aaPanel, em **Cron**, agende o backup do **banco de dados** e da pasta do site.
Todos os dados do sistema ficam no MySQL. A pasta `public/assets/img` guarda as imagens.
