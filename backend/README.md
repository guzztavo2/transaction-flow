<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# 🚀 Laravel API com Docker, MySQL, Redis e Supervisor

Este projeto é uma **API desenvolvida em Laravel**, containerizada com **Docker**, utilizando **MySQL** como banco de dados, **Redis** para cache/filas e **Supervisor** para gerenciar processos em segundo plano.  
O ambiente foi configurado para facilitar o desenvolvimento local com **Xdebug** habilitado e suporte a filas com **queue workers**.

---

## 📦 Tecnologias Utilizadas

- **PHP 8.3** (CLI)
- **Laravel** (API REST)
- **MySQL 8.0**
- **Redis**
- **Supervisor**
- **Docker & Docker Compose**
- **Xdebug** (debug local)

---

## ⚙️ Estrutura dos Serviços

- **app** → Contêiner principal da aplicação Laravel  
- **db** → Banco de dados MySQL  
- **redis** → Cache e filas  
- **queue** → Worker responsável por processar jobs da fila  
- **supervisor** → Gerencia os workers e processos em segundo plano  

---

## 🔧 Como Rodar o Projeto

### 1. Clone o repositório
```bash
git clone https://github.com/seu-usuario/seu-repo.git
cd seu-repo
```

### 2. Copie o arquivo `.env.example` para `.env` e configure as variáveis
```bash
cp .env.example .env
```

⚠️ Certifique-se de ajustar as variáveis de banco, Redis e e-mail conforme necessário.

### 3. Suba os containers
```bash
docker compose up -d --build
```

### 4. Instale as dependências do Laravel
```bash
docker exec -it laravel_app php artisan migrate
```

### 5. Ative o SUPERVISOR - Rodar jobs em segundo plano
Para rodar em segundo plano, o ideal é após fazer o migration, é voltar ao host, e executar:
```bash
docker restart laravel_supervisor laravel_queue
```

### 6. Acesse a aplicação
- API disponível em: **http://localhost:8000**

### 7. Testes disponíveis
Você pode rodar os testes da aplicação, que estão no seguinte diretório:
```bash
transaction-flow/backend/tests
```

Para rodar os testes você pode usar o seguinte comando (dentro do container da aplicação principal, aqui: laravel_app):
```bash
php vendor/bin/phpunit tests/Feature/{no do arquivo}
```
---

## 🗄️ Banco de Dados

- **Host:** `localhost`  
- **Porta:** `3306`  
- **Usuário:** `user`  
- **Senha:** `secret`  
- **Banco:** `project_01`  

---

## 📮 Rotas da API

### 🔑 Autenticação
- `POST /auth/register` → Registrar usuário  
- `POST /auth/login` → Login do usuário  
- `POST /auth/reset-password` → Resetar senha  
- `POST /auth/change-password/{token}` → Alterar senha via token  
- `POST /auth/change-password` → Alterar senha autenticado  
- `GET /auth/me` → Dados do usuário autenticado  
- `GET /auth/logout` → Logout  
- `GET /auth/refresh` → Refresh do token  

### 🏦 Contas
- `GET /accounts` → Listar contas  
- `GET /accounts/{id}` → Detalhar conta  
- `POST /accounts` → Criar conta  
- `PUT /accounts/{id}` → Atualizar conta  
- `DELETE /accounts/{id}` → Remover conta  
- `POST /accounts/define-default/{id}` → Definir conta padrão  

### 💸 Transações
- `POST /transactions` → Criar transação  

---

## 🐘 Xdebug
A aplicação já está configurada para debug remoto:  
- **Host:** `host.docker.internal`  
- **Porta:** `9003`  

---

## 🛠️ Supervisor & Queue Workers
O **Supervisor** gerencia processos como workers da fila. O contêiner `laravel_queue` processa jobs em background.  

Para verificar logs:
```bash
docker logs laravel_supervisor -f
```

---

## 📜 Licença
Este projeto está sob a licença **MIT**.  
