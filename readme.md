# 🐝 Bee Shop

Sistema de gerenciamento de vendedores e vendas.

## 🚀 Requisitos

- Docker + Docker Compose
- Node.js (v18+ recomendado)
- Git

---

## 🔥 Como rodar o projeto

### 1. Clone o repositório

```bash
git clone https://github.com/marcos-queiroz/bee-shop.git
```

Acesse o diretório do projeto

```bash
cd bee-shop
```

---

## 🧩 Backend (API Laravel)

```bash
cd api

# Copie o arquivo de ambiente
cp .env.dev .env

# Instale as dependências
composer install

# Suba os containers (Laravel Sail)
./vendor/bin/sail up -d

# Rode as migrations e seeds
./vendor/bin/sail artisan migrate --seed
```

> A API estará disponível em: http://localhost

---

## 💻 Frontend (Vue 3)

```bash
cd ../app

# Instale as dependências
npm install

# Inicie o servidor de desenvolvimento
npm run dev
```

> O app normalmente estará disponível em: http://localhost:5173 ou http://localhost:5174, verifique a saída no terminal.

---

## 🛠️ Observações

- Verifique se a API (`api`) está rodando antes de abrir o frontend (`app`).
- O frontend consome os endpoints da API local (http://localhost).

---

## 📦 Estrutura

```
bee-shop/
├── api/      # Backend Laravel (Laravel Sail)
└── app/      # Frontend Vue 3 + ShadCN
```

---

## 🧪 Testes

Em breve...

---

## 👨‍💻 Autor

Marcos Queiroz – [GitHub](https://github.com/marcos-queiroz)
