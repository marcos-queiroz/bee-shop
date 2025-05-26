# 🐝 Bee Shop

Sistema de gerenciamento de vendedores e vendas desenvolvido com o Laravel 12, VueJS 3 + Vite e a biblioteca de componentes [shadcn/vue](https://www.shadcn-vue.com/) a mesma utilizada no novo Starter Kit do Laravel.


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

- Certifique-se de que a API (`api`) esteja rodando antes de abrir o frontend (`app`).
- O frontend consome os endpoints da API local (http://localhost).

---

## 🕒 Filas e Comandos Agendados

### Processamento de Filas

O cálculo das comissões é processado em filas. Para processar as tarefas em background, execute o worker com o comando:

```bash
./vendor/bin/sail artisan queue:work
```

> **Observação:** Certifique-se de que o comando acima esteja rodando em um terminal separado ou como um serviço. Isso garantirá que os cálculos (e outros processos que utilizam filas) sejam executados corretamente.

---

### Testando Comandos Agendados

Embora o sistema utilize agendamento para executar os comandos de relatório (`report:admin` e `report:sellers`), você pode testá-los manualmente utilizando:

```bash
./vendor/bin/sail artisan report:admin
./vendor/bin/sail artisan report:sellers
```

Esses comandos geram, respectivamente, o relatório de vendas do administrador e o relatório de comissões para os vendedores.

---

## 📦 Estrutura

```
bee-shop/
├── api/      # Backend Laravel (Laravel Sail)
└── app/      # Frontend Vue 3 + ShadCN
```

---

## 🧪 Testes

Para executar os testes, basta rodar o comando:

```bash
./vendor/bin/sail artisan test
```

---

## 👨‍💻 Autor

Marcos Queiroz – [GitHub](https://github.com/marcos-queiroz)
