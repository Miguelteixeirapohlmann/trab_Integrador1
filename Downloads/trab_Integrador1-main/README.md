# 🏠 Plataforma Imobiliária - Real Estate Platform

Sistema completo de gestão imobiliária desenvolvido em PHP com MySQL, permitindo compra, venda e aluguel de imóveis.

## 📋 Índice

- [Características](#características)
- [Tecnologias](#tecnologias)
- [Requisitos](#requisitos)
- [Instalação](#instalação)
- [Configuração](#configuração)
- [Estrutura do Projeto](#estrutura-do-projeto)
- [Uso](#uso)
- [Funcionalidades](#funcionalidades)

## ✨ Características

- **Sistema de Autenticação**: Login, registro e recuperação de senha
- **Gestão de Usuários**: Clientes, corretores e administradores
- **Catálogo de Imóveis**: Visualização detalhada com galeria de fotos
- **Sistema de Compra e Aluguel**: Processo completo de transações
- **Agendamento de Visitas**: Sistema para agendar visitas aos imóveis
- **Painel Administrativo**: Gestão completa do sistema
- **Painel do Corretor**: Gerenciamento de imóveis e clientes
- **Sistema de Pagamentos**: Controle de pagamentos e comissões
- **Mensagens de Contato**: Formulário de contato integrado

## 🛠 Tecnologias

- **Backend**: PHP 7.4+
- **Banco de Dados**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, Bootstrap 5.3, JavaScript
- **Containerização**: Docker & Docker Compose
- **Padrões**: PDO, MVC (parcial)

## 📦 Requisitos

- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Apache/Nginx
- Docker e Docker Compose (opcional, para ambiente containerizado)

## 🚀 Instalação

### Opção 1: Usando Docker (Recomendado)

1. Clone o repositório:
```bash
git clone <url-do-repositorio>
cd trab_Integrador1-main
```

2. Inicie os containers:
```bash
docker-compose up -d
```

3. Execute o script SQL para criar o banco de dados:
```bash
# Acesse o phpMyAdmin em http://localhost:8081
# Ou execute via linha de comando:
docker exec -i mysql-db mysql -uroot -proot < database.sql
```

4. Acesse a aplicação:
```
http://localhost:8080
```

### Opção 2: Instalação Manual

1. Configure o servidor web (Apache/Nginx) apontando para a pasta do projeto

2. Configure o banco de dados em `config/database.php`:
```php
define('DB_HOST', 'localhost:3307');
define('DB_NAME', 'trab_integrador');
define('DB_USER', 'root');
define('DB_PASS', '');
```

3. Importe o banco de dados:
```bash
mysql -u root -p < database.sql
```

4. Configure permissões:
```bash
chmod -R 755 sessions/
chmod -R 755 uploads/
```

## ⚙️ Configuração

### Credenciais Padrão

**Administrador:**
- Email: `admin@realestate.com`
- Senha: `password` (padrão do hash no banco)

**Corretores:**
- Email: `joao.silva@realestate.com` / `maria.santos@realestate.com` / `pedro.costa@realestate.com`
- Senha: `password`

### Variáveis de Ambiente (Docker)

Edite o arquivo `docker-compose.yml` ou crie um `.env`:

```env
APACHE_PORT=8080
PHPMYADMIN_PORT=8081
MYSQL_HOST_PORT=3307
MYSQL_ROOT_PASSWORD=root
MYSQL_DATABASE=trab_integrador
MYSQL_USER=user
MYSQL_PASSWORD=password
```

## 📁 Estrutura do Projeto

```
trab_Integrador1-main/
├── assets/              # Imagens e favicon
├── Casas/              # Páginas individuais de casas
├── config/             # Configurações (database.php)
├── controllers/        # Controladores MVC
├── css/                # Estilos CSS
├── data/               # Dados JSON (agendamentos, mensagens)
├── includes/           # Arquivos PHP compartilhados
│   ├── auth.php       # Sistema de autenticação
│   ├── database.php   # Conexão com banco
│   ├── functions.php  # Funções auxiliares
│   ├── init.php       # Inicialização do sistema
│   └── navbar.php     # Navbar compartilhada
├── imgs/               # Imagens dos imóveis
├── js/                 # Scripts JavaScript
├── models/             # Modelos MVC
├── properties/         # Páginas de propriedades
├── sessions/           # Sessões PHP
├── views/              # Views MVC
├── admin.php           # Painel administrativo
├── agendar_visita.php  # Agendamento de visitas
├── alugar.php          # Página de aluguel
├── Compra.php          # Página de compra
├── casas_disponiveis.php # Listagem de casas
├── database.sql        # Script SQL do banco
├── docker-compose.yml  # Configuração Docker
├── index.php           # Página inicial
└── README.md           # Este arquivo
```

## 💻 Uso

### Como Usuário

1. Acesse a página inicial
2. Navegue pelas casas disponíveis
3. Faça login ou cadastre-se
4. Agende visitas ou solicite compra/aluguel

### Como Corretor

1. Faça login com credenciais de corretor
2. Acesse "Meus Imóveis" no menu
3. Gerencie seus imóveis (adicionar, editar, excluir)
4. Visualize agendamentos

### Como Administrador

1. Faça login com credenciais de admin
2. Acesse o "Painel Admin"
3. Gerencie usuários, imóveis e corretores
4. Visualize relatórios e estatísticas

## 🎯 Funcionalidades Principais

### 1. Sistema de Autenticação
- Login/Logout
- Registro de novos usuários
- Recuperação de senha
- Diferentes níveis de acesso (user, broker, admin)

### 2. Gestão de Imóveis
- Cadastro de imóveis com múltiplas fotos
- Edição e exclusão de imóveis
- Filtros e buscas
- Status de disponibilidade

### 3. Transações
- Processo de compra com financiamento
- Sistema de aluguel com contratos
- Controle de pagamentos
- Cálculo de comissões

### 4. Agendamentos
- Agendamento de visitas
- Confirmação e cancelamento
- Histórico de agendamentos

### 5. Contato
- Formulário de contato
- Mensagens salvas no banco
- Sistema de notificações

## 🔧 Desenvolvimento

### Iniciar Ambiente de Desenvolvimento

No Windows (PowerShell):
```powershell
.\start-dev.ps1
```

Ou manualmente:
```bash
docker-compose up -d
```

### Parar Ambiente

```bash
docker-compose down
```

### Ver Logs

```bash
docker-compose logs -f
```

## 📝 Notas Importantes

- As senhas padrão devem ser alteradas em produção
- Configure adequadamente as permissões de arquivos
- O sistema usa sessões PHP - certifique-se de que a pasta `sessions/` tem permissões de escrita
- Para uploads de imagens, configure a pasta `uploads/` com permissões adequadas

## 🐛 Solução de Problemas

### Erro de Conexão com Banco de Dados
- Verifique as credenciais em `config/database.php`
- Certifique-se de que o MySQL está rodando
- Verifique se a porta está correta (3307 no Docker, 3306 local)

### Erro de Sessão
- Verifique permissões da pasta `sessions/`
- Certifique-se de que o PHP tem permissão de escrita

### Imagens não aparecem
- Verifique os caminhos das imagens
- Certifique-se de que a pasta `imgs/` existe e tem as imagens

## 📄 Licença

Este projeto foi desenvolvido como trabalho integrador acadêmico.

## 👥 Autores

- Company Miguel

---

**Desenvolvido com ❤️ para facilitar a gestão imobiliária**




