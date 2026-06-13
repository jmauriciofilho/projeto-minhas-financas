# 💰 Gestor Financeiro Pessoal

Aplicação web para controle financeiro pessoal, desenvolvida em **Laravel**, com foco na organização de receitas, despesas, categorias e visualização clara da saúde financeira do usuário.

Este projeto tem caráter **educacional e evolutivo**, servindo como base para estudos de Laravel, boas práticas, arquitetura de software e futuras expansões funcionais.

---

## 📌 Visão Geral

O Gestor Financeiro Pessoal permite que usuários acompanhem sua vida financeira de forma simples e eficiente, registrando entradas e saídas, organizando por categorias e analisando resultados em um dashboard central.

---

## 🚀 Tecnologias Utilizadas

- **PHP 8.4**
- **Laravel 12**
- **MySQL**
- **Composer**
- **Node.js 18+**
- **NPM**
- **Docker**

---

## 📦 Requisitos

Antes de rodar o projeto localmente, certifique-se de possuir:

- Docker
- Docker-compose

---

## ⚙️ Instalação e Execução Local (Ambiente de Desenvolvimento)

### 1 Clonar o repositório

```bash
git clone git@github.com:jmauriciofilho/projeto-minhas-financas.git
cd projeto-minhas-financas
```

### 2 Configurar variáveis de ambiente

Crie o arquivo .env a partir do exemplo:

```bash
cp .env.example .env
```

Edite o arquivo .env com as configurações do banco de dados:

```text
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=financas
DB_USERNAME=laravel
DB_PASSWORD=secret
```

### 3 Instalar projeto

```bash
docker compose -f docker-compose.yml -f docker-compose.dev.yml up -d
```

### 4 Acessar container

```bash
docker compose -f docker-compose.yml -f docker-compose.dev.yml exec app bash
```

### 5 Gerar a chave da aplicação

```bash
php artisan key:generate
```

### 6 Executar as migrations

```bash
php artisan migrate
```

<!-- Opcional (caso existam dados iniciais):

```bash
php artisan db:seed
``` -->

### 7 Compilar os assets do frontend

```bash
npm install && npm run build
```

Se necessário remover containers execute:

```bash
docker compose -f docker-compose.yml -f docker-compose.dev.yml down
```

A aplicação estará disponível em:

http://localhost:8000

O adminer para acessar o banco está disponível em:

http://localhost:8080

## 🧠 Funcionalidades

### ✅ Funcionalidades Implementadas

- [x] Cadastro de usuários
- [x] Autenticação e controle de sessão
- [x] Listagem de contas
- [x] Adicionar contas
- [x] Editar contas
- [x] Excluir contas
- [x] Adicionar receitas
- [x] Listar receitas
- [x] Receber receitas
- [x] Excluir receitas
- [x] Adicionar despesas
- [x] Listar despesas
- [x] Pagar despesa
- [x] Excluir despesas
- [x] Adicionar cartoes
- [x] Listar cartoes
- [x] Editar cartoes
- [x] Excluir cartoes
- [x] Adicionar faturas
- [x] Listar faturas
- [x] Editar faturas
- [x] Excluir faturas
- [x] Adicionar compra cartao
- [x] Listar compras cartao
- [x] Excluir compra cartao
- [x] Impedir add de compra em faturas pagas
- [x] Pagar faturas
- [x] classificação de despesas e compras no cartao
- [x] Editar valor de receitas, despesas e contas de cartão equanto nao for paga ou recebida.
- [x] Importe de dados financeiros por json

### 🚧 Funcionalidades Planejadas (To-Do)

- [] Dashboard com visao mensal
- [] Melhorar importes de dados em json

### 🚧 Funcionalidades extras (To-Do)

- [] Adicionar investimentos
- [] Listar investimentos
- [] Editar investimentos
- [] excluir investimentos
- [] realizar aplicacao
- [] realizar resgate
- [] registrar rendimento
- [] gerenciamento de assinaturas

## 📄 Licença

Este projeto está licenciado sob a MIT License.
Você é livre para usar, modificar e distribuir.

## 👤 Autor

Maurício Pereira
Desenvolvedor Backend / Software Architect
