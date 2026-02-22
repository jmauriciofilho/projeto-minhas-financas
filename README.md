# 💰 Gestor Financeiro Pessoal

Aplicação web para controle financeiro pessoal, desenvolvida em **Laravel**, com foco na organização de receitas, despesas, categorias e visualização clara da saúde financeira do usuário.

Este projeto tem caráter **educacional e evolutivo**, servindo como base para estudos de Laravel, boas práticas, arquitetura de software e futuras expansões funcionais.

---

## 📌 Visão Geral

O Gestor Financeiro Pessoal permite que usuários acompanhem sua vida financeira de forma simples e eficiente, registrando entradas e saídas, organizando por categorias e analisando resultados em um dashboard central.

---

## 🚀 Tecnologias Utilizadas

- **PHP 8.2+**
- **Laravel 11**
- **MySQL / MariaDB / SQLite**
- **Composer**
- **Node.js 18+**
- **NPM**
- **Vite**
- **Blade**
- **Tailwind CSS**

---

## 📦 Requisitos

Antes de rodar o projeto localmente, certifique-se de possuir:

- PHP 8.2 ou superior
- Composer
- Node.js e NPM
- MySQL, MariaDB (se for usar algum desses bancos)
- Git

---

## ⚙️ Instalação e Execução Local (Ambiente de Desenvolvimento)

### 1️⃣ Clonar o repositório

```bash
git clone https://github.com/seu-usuario/gestor-financeiro.git
cd gestor-financeiro
```

### 2️⃣ Instalar dependências do backend (PHP)

```bash
composer install
```

### 3️⃣ Instalar dependências do frontend

```bash
npm install
```

### 4️⃣ Configurar variáveis de ambiente

Crie o arquivo .env a partir do exemplo:

```bash
cp .env.example .env
```

Edite o arquivo .env com as configurações do banco de dados:

```text
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestor_financeiro
DB_USERNAME=root
DB_PASSWORD=senha
```

### 5️⃣ Gerar a chave da aplicação

```bash
php artisan key:generate
```

### 6️⃣ Executar as migrations

```bash
php artisan migrate
```

Opcional (caso existam dados iniciais):

```bash
php artisan db:seed
```

### 7️⃣ Compilar os assets do frontend

```bash
npm install && npm run build
```

### 8️⃣ Iniciar o servidor local

```bash
composer run dev
```

A aplicação estará disponível em:

http://localhost:8000

## 🧠 Funcionalidades

### ✅ Funcionalidades Implementadas

- [x] Cadastro de usuários
- [x] Autenticação e controle de sessão
- [x] Listagem de contas
- [x] Adicionar contas
- [x] Editar contas
- [x] Excluir contas

### 🚧 Funcionalidades Planejadas (To-Do)

- [] Adicionar receitas
- [] Listar receitas
- [] Editar receitas
- [] Receber receitas
- [] Excluir receitas
- [] Adicionar despesas
- [] Listar despesas
- [] Editar despesas
- [] Pagar despesa
- [] Excluir despesas
- [] Adicionar cartoes
- [] Listar cartoes
- [] Editar cartoes
- [] Excluir cartoes
- [] Adicionar faturas
- [] Listar faturas
- [] Editar faturas
- [] Fechar e pagar faturas
- [] Excluir faturas
- [] Adicionar compra cartao
- [] Listar compras cartao
- [] Editar compra cartao
- [] Excluir compra cartao

<!-- ## 📄 Licença

Este projeto está licenciado sob a MIT License.
Você é livre para usar, modificar e distribuir. -->

## 👤 Autor

Maurício Pereira
Desenvolvedor Backend / Software Architect
