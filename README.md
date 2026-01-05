# Fullstack - Google OAuth

Este projeto é uma solução completa Fullstack (API Laravel + Frontend Vue.js) com autenticação social via Google.

<div align="center">

  [![LinkedIn](https://img.shields.io/badge/LinkedIn-0077B5?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/vitor-araujo-5a4910227)
  [![Demo App](https://img.shields.io/badge/Demo-Demo_Online-4CAF50?style=for-the-badge&logo=google-chrome&logoColor=white)](https://app.vitoraraujo.cloud)
  [![Email](https://img.shields.io/badge/Email-Fale_Comigo-D14836?style=for-the-badge&logo=gmail&logoColor=white)](mailto:vitor.araujo63@etec.sp.gov.br)
  
  [![CI/CD Pipeline](https://github.com/VitorAraujo63/google-oauth-project/actions/workflows/deploy.yml/badge.svg)](https://github.com/VitorAraujo63/google-oauth-project/actions)

</div>

---

## Tecnologias e Arquitetura

* **Backend:** PHP 8.3, Laravel 12
* **Frontend:** Vue.js 3, TypeScript, Pinia, Sass
* **Banco de Dados:** MySQL 8
* **Infraestrutura:** Docker & Laravel Sail
* **Design Patterns:** Service Layer, Repository Pattern, Dependency Injection
* **Filas (Queues):** Processamento assíncrono para envio de e-mails

---

## Pré-requisitos

Para rodar este projeto, você precisa ter instalado em sua máquina:

* **Opção Docker (Recomendada):** Docker, Docker Compose e Node.js 20+.
* **Opção Nativa:** PHP 8.2+, Composer, Node.js 20+, MySQL.

---

## Como Rodar o Projeto


Você pode escolher rodar via **Docker (Laravel Sail)** ou **Nativamente**.

### Opção 1: Rodando com Docker (Laravel Sail)

Esta é a forma mais simples, pois não requer PHP instalado na máquina host.

- ### Antes de iniciar recomendo passar pela seção de [Variaveis de ambiente](https://github.com/VitorAraujo63/google-oauth-project?tab=readme-ov-file#configura%C3%A7%C3%A3o-do-google-oauth-e-e-mail)

1.  **Clone o repositório:**
    ```bash
    git clone https://github.com/VitorAraujo63/google-oauth-project.git
    cd google-oauth-project
    ```

2.  **Configure o Backend:**
    ```bash
    # Copie o arquivo de ambiente
    cp .env.example .env

    # Instale as dependências do PHP (via container temporário)
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html \
        laravelsail/php83-composer:latest \
        composer install --ignore-platform-reqs

    # Ou se tiver o composer em sua maquina siga dessa maneira
    composer install
    ```

3.  **Suba os containers:**
    ```bash
    ./vendor/bin/sail up -d
    ```

4.  **Gere a chave e rode as migrações:**
    ```bash
    ./vendor/bin/sail artisan key:generate
    ./vendor/bin/sail artisan migrate
    ./vendor/bin/sail artisan db:seed --class=UserSeeder
    ```

5.  **Configure o Frontend:**
    ```bash
    cd frontend
    npm install
    npm run dev
    # O frontend estará disponível em http://localhost:5174
    ```

6.  **Inicie a Fila (Para envio de e-mails):**
    Em um novo terminal, na raiz do projeto:
    ```bash
    ./vendor/bin/sail artisan queue:work
    ```

---

### Opção 2: Rodando Nativamente (Diretamente na Máquina)

Caso prefira rodar sem Docker e já tenha o ambiente configurado.

- ### Antes de iniciar recomendo passar pela seção de [Variaveis de ambiente](https://github.com/VitorAraujo63/google-oauth-project?tab=readme-ov-file#configura%C3%A7%C3%A3o-do-google-oauth-e-e-mail)

1.  **Backend (API):**
    ```bash
    # Na raiz do projeto
    cp .env.example .env
    
    # Edite o .env e configure suas credenciais de banco (DB_HOST, DB_PASSWORD, etc)
    
    composer install
    php artisan key:generate
    php artisan migrate
    php artisan db:seed --class=UserSeeder
    
    # Inicie o servidor
    php artisan serve
    # API disponível em http://localhost:8000
    ```

2.  **Frontend (Vue.js):**
    ```bash
    cd frontend
    cp .env.example .env # configure VITE_API_BASE_URL
    npm install
    npm run dev
    # Frontend disponível em http://localhost:5174 (ou o que foi configurado em sua maquina)
    ```

3.  **Filas (Essencial para E-mails):**
    ```bash
    # Em um terminal separado
    php artisan queue:work
    ```

---

## Configuração do Google OAuth e E-mail

Para que o login e o envio de e-mails funcionem, você precisa configurar as credenciais no arquivo `.env`.

### 1. Google OAuth
Crie um projeto no Google Cloud Console e obtenha as credenciais.
```ini
GOOGLE_CLIENT_ID=seu_client_id_aqui
GOOGLE_CLIENT_SECRET=seu_client_secret_aqui
GOOGLE_REDIRECT_URL=http://localhost:8000/api/auth/google/callback 

(ou a url da api que estiver em sua maquina,
se for pelo sail provavelmente será http://localhost:80/api/auth/google/callback)
```

### 2. Envio de E-mail (Gmail/SMTP)
Para testar o envio real, recomenda-se usar uma Senha de App do Gmail ou Mailtrap.

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu_email@gmail.com
MAIL_PASSWORD=sua_senha_de_app
MAIL_ENCRYPTION=tls

QUEUE_CONNECTION=database
```

### 3. Reconhecimento da API (Front-end)
Para que a api seja reconhecida no frontend é preciso configurar o .env dentro do "/frontend"

```
cd /frontend

cp .env.example .env

#.env
VITE_API_BASE_URL=http://localhost:80/api
```
---


## Decisões de Arquitetura

Durante o desenvolvimento, foram adotadas as seguintes práticas para garantir escalabilidade e manutenção:

- Service & Repository Pattern:

    -  Controllers: Mantidos "magros", responsáveis apenas por receber requisições HTTP e retornar respostas JSON.

    - Services: Centralizam as regras de negócio (ex: decisão de disparar e-mail após cadastro).

    - Repositories: Abstraem a camada de dados, permitindo queries complexas (como a busca híbrida de CPF/Nome) sem poluir o controller.

- Processamento Assíncrono (Queues):

    - O envio de e-mails de boas-vindas é feito através de Jobs e Queues (driver database). Isso garante que a API responda imediatamente ao usuário, sem travamentos enquanto o SMTP processa o envio.

- Autenticação:

    - Optou-se pelo Laravel Socialite devido à sua robustez, segurança e integração nativa com o framework, seguindo os padrões modernos da comunidade Laravel, embora a biblioteca nativa do Google tenha sido considerada.

- Frontend Otimizado:

    - Uso de Pinia para gerenciamento de estado global do usuário.

    - TypeScript para garantir tipagem segura e reduzir bugs em tempo de execução.

<br>

- Foi decido que no front-end não deveria ser apresentado o CPF completo, por esse motivo seguiu com a restrição de exibição do mesmo, porém, ainda era possível validar no F12, dando brecha para ser capturado qualquer informação de outros usuarios, contornei isso com UserResource, na qual ele retorna o cpf já mascarado para que não seja divulgado para o front-end e se mantenha os dados privados, conforme apresentado na imagem a seguir:

    <img width="1869" height="928" alt="Design sem nome" src="https://github.com/user-attachments/assets/df9243d4-2153-4379-92b0-d71040eafcc0" />

<br>

- Abaixo é a estrutura inicial pensada e criada no excalidraw para entender o fluxo e seguir com o desenvolvimento a partir disso.

<img width="2081" height="861" alt="Sem título-2025-04-23-2028" src="https://github.com/user-attachments/assets/d45d5eba-1f72-48af-9426-9943f9b4536e" />

Adicionar uma seção de contribuição demonstra profissionalismo e conhecimento do fluxo de trabalho Git (Git Flow), mesmo que seja um projeto de teste.

Aqui está um modelo padrão e elegante para você colar no final do seu README.md:

---

## 🤝 Contribuição

Embora este seja um projeto de avaliação técnica, contribuições e sugestões de melhoria são sempre bem-vindas!

Se você deseja contribuir:

1.  Faça um **Fork** do projeto.
2.  Crie uma nova Branch para sua funcionalidade (`git checkout -b feature/MinhaNovaFeature`).
3.  Faça o Commit das suas alterações (`git commit -m 'feat: Adiciona nova funcionalidade'`).
4.  Faça o Push para a Branch (`git push origin feature/MinhaNovaFeature`).
5.  Abra um **Pull Request**.

---

<div align="center">
  Feito com 💜 por <a href="https://www.linkedin.com/in/vitor-araujo-5a4910227">Vitor Araujo</a>
</div>
