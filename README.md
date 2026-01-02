
# Desafio Full Stack - Google Oauth
> Solução completa para o desafio técnico Full Stack: API em Laravel + SPA em Vue.js. O sistema oferece login social (Google), complementação de dados sensíveis (CPF, data de nascimento) e exibição performática de grandes volumes de dados (150.000+ usuários).

---

## 🚀 Visão Geral

Este repositório contém:

- Backend: API RESTful construída com **Laravel 12** (PHP 8.4+), MySQL
- Frontend: SPA em **Vue 3** (Composition API + TypeScript) construída com Vite.
- Autenticação via **Login Social (Google OAuth)**.
- Preparado para trabalhar com grandes volumes de dados (seed com 150.000 usuários).
- Containerizado com **Docker** e **Laravel Sail** para facilitar o desenvolvimento local.

---

## 🧩 Principais Funcionalidades

- Login social com Google (OAuth) e persistência do token de acesso.
- Fluxo em duas etapas para completar cadastro: primeiro login social, depois complemento com CPF e data de nascimento.
- Paginação e buscas performáticas por nome / CPF (índices compostos no banco).
- UI responsiva com debounce em buscas, skeletons (loading states) e toasts de notificação.
- Triggers e filas assíncronas com Redis para processamentos demorados.
- Seed de 150.000 usuários para testes de performance.

---

## 🏗 Arquitetura e Decisões Técnicas

- Padrão Service-Repository:
  - Service Layer: regras de negócio (ex.: processamento do callback do Google).
  - Repository Layer: abstração do acesso ao banco, facilitando testes e mudanças futuras no storage/ORM.
- Performance:
  - Índices compostos nas colunas de busca (`name`, `cpf`) para evitar full table scans.
  - Paginação consistente na API para reduzir uso de memória.
- Autenticação:
  - Integração com a biblioteca oficial `googleapis/google-api-php-client`.
  - Token de acesso persistido para permitir continuidade do cadastro em etapas.
- Frontend:
  - Vue Router com Guards para rotas protegidas.
  - Pinia para gerenciamento de estado.
  - Debounce nas buscas e componentes de feedback visual.

---

## 🛠️ Como Rodar o Projeto

Este projeto utiliza **Docker** e **Laravel Sail**. Você **não precisa** ter PHP ou Composer instalados na sua máquina local, apenas o Docker.

### Pré-requisitos
- **Docker** e **Docker Compose** instalados e rodando.
- **Node.js** (versão 18+ recomendada) e **NPM/Yarn** (para o Frontend).
- Credenciais do Google Cloud Console (Client ID e Secret).

---

### Passo 1: Configuração do Backend (API)

1. **Clone o repositório e entre na pasta da API:**
   ```bash
   git clone <URL_DO_SEU_REPOSITORIO>
   cd api
   ```
   Configure as variáveis de ambiente: Faça uma cópia do arquivo de exemplo.

   ```bash
   cp .env.example .env
   ```

   Instale as dependências (Bootstrap do Sail): Como a pasta vendor ainda não existe, usaremos um container Docker temporário para rodar o Composer e instalar o Laravel Sail e as outras dependências.

   Rode este comando no terminal (pode demorar alguns minutos dependendo da sua internet):

   ```bash
   docker run --rm \
       -u "$(id -u):$(id -g)" \
       -v "$(pwd):/var/www/html" \
       -w /var/www/html \
       laravelsail/php84-composer:latest \
       composer install --ignore-platform-reqs
   ```

   Suba o ambiente (Sail): Agora que as dependências foram instaladas, o executável do Sail está disponível.

   ```bash
   ./vendor/bin/sail up -d
   ```

   Gere a chave da aplicação e configure o Google:

   ```bash
   ./vendor/bin/sail artisan key:generate
   ```

   Agora, abra o arquivo .env no seu editor e preencha as credenciais do Google:

   Code snippet
   ```env
   GOOGLE_CLIENT_ID=seu_client_id
   GOOGLE_CLIENT_SECRET=seu_client_secret
   GOOGLE_REDIRECT_URI=http://localhost/api/auth/google/callback
   APP_FRONTEND_URL=http://localhost:5173
   ```

   Banco de Dados e Seeds (150k Usuários): Rode as migrações e o Seeder para popular o banco. Atenção: A geração de 150.000 registros pode levar alguns minutos.

   ```bash
   ./vendor/bin/sail artisan migrate --seed --class=UserSeeder
   ```

### Passo 2: Configuração do Frontend (Vue.js)

Em um novo terminal, acesse a pasta do frontend:

```bash
cd frontend
```

Crie o arquivo de ambiente: Crie um arquivo chamado `.env.local` na raiz da pasta frontend e adicione:

Code snippet
```env
VITE_API_BASE_URL=http://localhost/api
```

Instale as dependências e rode o projeto:

```bash
npm install
npm run dev
```

### Passo 3: Acessar o Projeto

Frontend (Aplicação): Acesse http://localhost:5173

Backend (API): Acesse http://localhost

Documentação da API: Acesse http://localhost/docs/api

---

## 🧪 Testando a Performance (150k usuários)

- O seeder (`UserSeeder`) gera 150.000 registros com avatares dinâmicos.
- A busca por Nome ou CPF é indexada — consultas devem ser rápidas (milissegundos) mesmo com grande volume.
- Para testes de carga adicionais, use ferramentas como `siege`, `wrk` ou `k6` apontando para os endpoints da API.

---

## 📚 Endpoints & Documentação da API

Se a documentação (Swagger / OpenAPI / Scramble) estiver habilitada, acesse:
- http://localhost/docs/api

(Se não estiver instalada, verifique em `api/docs` ou ative a rota de documentação conforme instruções do projeto.)

---

## ⚙️ Variáveis de Ambiente Principais

Exemplo resumido do `.env` do backend (não commitá-lo com credenciais reais):
```env
APP_NAME=TrayChallenge
APP_ENV=local
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=tray
DB_USERNAME=root
DB_PASSWORD=secret

REDIS_HOST=redis
REDIS_PORT=6379

GOOGLE_CLIENT_ID=seu_client_id_aqui
GOOGLE_CLIENT_SECRET=seu_client_secret_aqui
GOOGLE_REDIRECT_URI=http://localhost/api/auth/google/callback
APP_FRONTEND_URL=http://localhost:5173
```

Variáveis frontend (.env.local)
```env
VITE_API_BASE_URL=http://localhost/api
VITE_GOOGLE_CLIENT_ID=seu_client_id_aqui   # se usado no cliente
```

---

## ⛑️ Dicas e Troubleshooting

- Erro 500 / problemas de OAuth:
  - Verifique se o `GOOGLE_REDIRECT_URI` cadastrado no Google Console corresponde exatamente ao que está no `.env`.
- Problemas com containers:
  - Rode `./vendor/bin/sail down` e `./vendor/bin/sail up -d` novamente.
- Seed lento:
  - Se o seeder for muito pesado localmente, considere reduzir a quantidade para desenvolvimento (ex.: 10k) ou usar máquinas com mais recursos.

---

## Contribuição

Contribuições são bem-vindas! Abra uma issue descrevendo a melhoria ou um PR com uma descrição clara das mudanças e testes associados.

Boas práticas:
- Siga as convenções PSR (backend) e linting/formatting (frontend).
- Escreva testes para novas funcionalidades.
- Mantenha o código desacoplado (Service/Repository já adotado como padrão).

---

## Contato

Desenvolvido por: Vitor Henrique P. Araujo
LinkedIn: https://www.linkedin.com/in/vitor-araujo-5a4910227/
E-mail: vitor.araujo63@etec.sp.gov.br