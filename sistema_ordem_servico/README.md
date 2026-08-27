# 🛠️ Sistema de Ordem de Serviços - JM Informática

Este projeto é um sistema web para gerenciamento de ordens de serviço, controle de comissões de funcionários e geração de relatórios operacionais. Desenvolvido para atender aos requisitos técnicos de teste prático sem utilização de frameworks ou gerenciadores de dependência.

---

## 📋 Sumário
- [Recursos e Funcionalidades](#-recursos-e-funcionalidades)
- [Regras de Negócio](#-regras-de-negócio)
- [Tecnologias Utilizadas](#-tecnologias-utilizadas)
- [Estrutura do Projeto](#-estrutura-do-projeto)
- [Modelo de Banco de Dados](#-modelo-de-banco-de-dados)
- [Instalação e Execução](#-instalação-e-execução)
  - [Pré-requisitos](#pré-requisitos)
  - [Passo a Passo (PHP Built-in Server)](#passo-a-passo-php-built-in-server)
  - [Execução via XAMPP / Apache](#execução-via-xampp--apache)
- [Solução de Problemas](#-solução-de-problemas)

---

## 🚀 Recursos e Funcionalidades

### 🔒 Autenticação & Usuários
* **Login Seguro:** Autenticação por e-mail e senha com criptografia em hash (`password_hash`).
* **Cadastro de Usuários:** Permite registrar novos funcionários no sistema.
* **Sessão do Usuário:** Exibição do nome do usuário logado e data atual no Dashboard.

### 📊 Dashboard & Painel Operacional
* **Lista de Serviços:** Tabela com todos os serviços e botões de ação (Excluir, Finalizar).
* **Destaques Rápidos:**
  * Lista dos últimos serviços cadastrados.
  * Serviços pendentes vinculados ao usuário logado.
  * Valor total dos serviços prestados pelo usuário.
* **Filtros Avançados:**
  * Período (Data Inicial e Data Final).
  * Descrição / Nome do Serviço.
  * Status do Serviço (PENDENTE / FINALIZADO).
  * Nome do Funcionário / Usuário.

### 💼 Gestão de Serviços & Comissões
* **Cadastro de Serviço:** Inserção com status inicial automático como `PENDENTE`.
* **Finalização de Serviço:** Atualiza a data de conclusão, calcula a comissão progressiva e envia notificação por e-mail ao responsável.

---

## 📐 Regras de Negócio

### Status do Serviço
* **PENDENTE:** Quando o campo `finished_at` do banco de dados for `NULL`.
* **FINALIZADO:** Quando o campo `finished_at` contiver a data e hora do encerramento.

### Cálculo de Comissão (ao finalizar)
O valor da comissão gerada para o funcionário depende da faixa de preço do serviço:
* **Valores até R$ 1.000,00:** 5% de comissão.
* **Valores entre R$ 1.000,01 e R$ 10.000,00:** 10% de comissão.
* **Valores acima de R$ 10.000,00:** 20% de comissão.

---

## 🛠️ Tecnologias Utilizadas

Este projeto respeita integralmente as restrições arquiteturais exigidas:
* **Linguagem Backend:** PHP (Nativo / Vanilla - PDO)
* **Banco de Dados:** MySQL / MariaDB
* **Frontend:** HTML5, CSS3 puro e JavaScript Vanilla
* **Proibições Atendidas:** ❌ Sem Frameworks (Backend/Frontend), ❌ Sem Composer, ❌ Sem Bibliotecas de Terceiros.

---

## 📁 Estrutura do Projeto

```text
sistema_ordem_servico/
│
├── config/
│   └── database.php       # Conexão PDO com banco de dados MySQL
│
├── controllers/
│   ├── AuthController.php # Autenticação e Registro de Usuários
│   └── ServiceController.php # Regras de Negócio e CRUD de Serviços
│
├── models/
│   ├── User.php           # Manipulação dos dados da tabela 'user'
│   └── Service.php        # Manipulação dos dados da tabela 'service'
│
├── views/
│   ├── login.php          # Interface de Login
│   ├── register_user.php  # Interface de Registro de Usuário
│   ├── dashboard.php      # Painel de Controle Principal
│   └── add_service.php    # Formulário de Cadastro de Serviços
│
├── assets/
│   ├── css/
│   │   └── style.css      # Estilização CSS Nativa
│   └── js/
│       └── script.js      # Validações em JavaScript Nativo
│
├── README.md              # Documentação do Projeto
└── index.php              # Front Controller e Roteador da aplicação
```

---

## 🗄️ Modelo de Banco de Dados

Rode o script SQL abaixo no seu MySQL para estruturar a base de dados `jm_informatica`:

```sql
CREATE DATABASE IF NOT EXISTS jm_informatica CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE jm_informatica;

-- Tabela de Usuários
CREATE TABLE IF NOT EXISTS user (
    id_user BIGINT(20) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    update_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    ativo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB;

-- Tabela de Serviços
CREATE TABLE IF NOT EXISTS service (
    id_service BIGINT(20) AUTO_INCREMENT PRIMARY KEY,
    description VARCHAR(45) NOT NULL,
    price DECIMAL(11,3) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    update_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    finished_at DATETIME NULL DEFAULT NULL,
    commission_user DECIMAL(11,3) NULL DEFAULT 0.000,
    user_id_user BIGINT(20) NOT NULL,
    FOREIGN KEY (user_id_user) REFERENCES user(id_user) ON DELETE CASCADE
) ENGINE=InnoDB;
```

---

## ⚙️ Instalação e Execução

### Pré-requisitos
* **PHP** (versão 7.2 ou superior)
* **MySQL** ou **MariaDB**

---

### Passo a Passo (PHP Built-in Server)

1. **Clonar / Baixar o Repositório:**
   Extraia os arquivos na sua máquina local.

2. **Configurar o Banco de Dados:**
   * Certifique-se de que o servidor MySQL está em execução.
   * Crie o banco de dados e as tabelas executando o script SQL fornecido acima.

3. **Ajustar Credenciais de Conexão:**
   Abra o arquivo `config/database.php` e configure os dados de conexão conforme seu ambiente:
   ```php
   private $host = '127.0.0.1'; // Ou '127.0.0.1;port=3307' caso use porta personalizada
   private $db_name = 'jm_informatica';
   private $username = 'root';
   private $password = '';
   ```

4. **Executar pelo Terminal:**
   Abra a pasta do projeto no terminal e inicie o servidor interno do PHP:
   ```bash
   cd /caminho/para/sistema_ordem_servico
   php -S localhost:8000 index.php
   ```

5. **Acessar a Aplicação:**
   Abra o navegador e acesse: [http://localhost:8000](http://localhost:8000)

---

### Execução via XAMPP / Apache

1. Mova a pasta `sistema_ordem_servico` para a pasta htdocs.
2. Inicie os módulos **Apache** e **MySQL** no XAMPP Control Panel.
3. Importe o banco de dados no phpMyAdmin.
4. Acesse pelo navegador: `http://localhost/sistema_ordem_servico/index.php`.

---

## ❓ Solução de Problemas

* **Erro `Not Found /` ao rodar `php -S`:**
  Execute o servidor passando o arquivo roteador explicitamente: `php -S localhost:8000 index.php`.
* **Erro `SQLSTATE[HY000] [2006] MySQL server has gone away`:**
  Verifique se o serviço do MySQL está ativo ou se há conflito de portas no Windows. Caso altere a porta no MySQL (ex: `3307`), lembre-se de definir no `config/database.php`: `private $host = '127.0.0.1;port=3307';`.