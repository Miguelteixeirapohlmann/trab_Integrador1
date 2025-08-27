# Real Estate Platform - Sistema de Imóveis

## 📋 Visão Geral

Este projeto foi completamente reorganizado seguindo as melhores práticas de desenvolvimento web, implementando uma arquitetura MVC (Model-View-Controller) moderna e segura para uma plataforma completa de compra, venda e aluguel de imóveis.

## 🏗️ Nova Estrutura do Projeto

```
trab_Integrador1/
├── config/                 # Configurações do sistema
│   └── database.php       # Configurações do banco de dados
├── controllers/           # Controladores (lógica de negócio)
│   └── ContactController.php
├── includes/              # Arquivos de inclusão
│   ├── auth.php          # Sistema de autenticação
│   ├── database.php      # Conexão com banco de dados
│   ├── functions.php     # Funções auxiliares
│   └── init.php          # Inicialização do sistema
├── models/               # Modelos (interação com dados)
│   ├── Contact.php       # Modelo de contato
│   ├── Property.php      # Modelo de propriedades
│   └── User.php          # Modelo de usuários
├── views/                # Views (apresentação)
│   ├── layouts/          # Layouts reutilizáveis
│   │   └── main.php     # Layout principal
│   ├── auth/            # Páginas de autenticação
│   │   ├── login.php    # Login
│   │   └── register.php # Cadastro
│   ├── admin/           # Área administrativa
│   └── properties/      # Páginas de propriedades
├── assets/              # Recursos estáticos (reorganizado)
├── css/                 # Estilos CSS
├── js/                  # Scripts JavaScript
├── imgs/                # Imagens (será migrado para assets/)
├── uploads/             # Uploads de usuários
├── database.sql         # Script de criação do banco
├── setup_database.php   # Script de configuração automática
├── index.php            # Página inicial (reformulada)
└── README.md           # Este arquivo
```

## 🚀 Principais Melhorias Implementadas

### 1. **Arquitetura MVC Completa**
- **Models**: Gerenciam dados e lógica de negócio
- **Views**: Responsáveis pela apresentação
- **Controllers**: Controlam o fluxo da aplicação
- **Eliminação de duplicidades** de código
- **Includes centralizados** para funcionalidades comuns

### 2. **Sistema de Banco de Dados MySQL**
- ✅ **Migração completa** de arquivos JSON para banco MySQL
- ✅ **Relacionamentos estruturados** entre entidades
- ✅ **Índices otimizados** para performance
- ✅ **Transações seguras** para integridade de dados
- ✅ **Sistema de logs** completo

### 3. **Sistema de Autenticação e Perfis Avançado**
- ✅ **Hash seguro** de senhas com `password_hash()`
- ✅ **Sessões seguras** com timeout
- ✅ **3 tipos de usuário**: cliente, corretor, admin
- ✅ **Sistema de recuperação** de senha
- ✅ **Perfis completos** com avatar e informações detalhadas
- ✅ **Logs de auditoria** de todas as ações

### 4. **Segurança Implementada**
- ✅ **Proteção CSRF** em todos os formulários
- ✅ **Sanitização automática** de dados de entrada
- ✅ **Validação server-side** rigorosa
- ✅ **Rate limiting** para prevenir spam
- ✅ **SQL Injection** prevenido com prepared statements
- ✅ **Upload seguro** de arquivos com validação

### 5. **Interface Padronizada e Responsiva**
- ✅ **Layout unificado** em todas as páginas
- ✅ **Componentes reutilizáveis** 
- ✅ **Navegação intuitiva** baseada no tipo de usuário
- ✅ **Feedback visual consistente**
- ✅ **Design responsivo** para mobile
- ✅ **Estilização centralizada** baseada no index

## 🛠️ Configuração e Instalação

### Pré-requisitos
- PHP 7.4 ou superior
- MySQL 5.7 ou superior  
- Servidor web (Apache/Nginx/XAMPP)
- Extensões PHP: PDO, PDO_MySQL, JSON, GD

### 1. Configuração Automática do Banco de Dados
```bash
php setup_database.php
```

### 2. Configuração Manual (se necessário)
1. Crie um banco chamado `real_estate`
2. Execute o arquivo `database.sql`
3. Configure as credenciais em `config/database.php`

### 3. Permissões de Diretórios
```bash
chmod 755 uploads/
chmod 755 assets/img/
```

## 👥 Tipos de Usuário e Funcionalidades

### 1. **Cliente (User)**
- ✅ Buscar e visualizar imóveis com filtros avançados
- ✅ Sistema de favoritos
- ✅ Agendar visitas online
- ✅ Perfil completo com preferências
- ✅ Histórico de ações

### 2. **Corretor (Broker)**
- ✅ Todas as funcionalidades do cliente
- ✅ Cadastrar e gerenciar imóveis
- ✅ Upload múltiplo de imagens
- ✅ Dashboard com estatísticas
- ✅ Gerenciar visitas agendadas
- ✅ Perfil profissional com CRECI

### 3. **Administrador (Admin)**
- ✅ Painel administrativo completo
- ✅ Gerenciar usuários e corretores
- ✅ Relatórios e estatísticas
- ✅ Moderar conteúdo
- ✅ Logs do sistema

## 📊 Funcionalidades do Sistema

### Sistema de Propriedades
- ✅ Cadastro completo com todas as informações
- ✅ Upload e gerenciamento de imagens
- ✅ Busca avançada com múltiplos filtros
- ✅ Propriedades similares (algoritmo inteligente)
- ✅ Sistema de favoritos
- ✅ Contador de visualizações
- ✅ Status de disponibilidade

### Sistema de Agendamento de Visitas
- ✅ Agendamento online com formulário
- ✅ Confirmação automática
- ✅ Status de visitas (agendada, confirmada, realizada)
- ✅ Histórico completo
- ✅ Notificações por email

### Sistema de Comunicação
- ✅ Formulário de contato padronizado
- ✅ Sistema de mensagens
- ✅ Histórico de comunicações
- ✅ Status de mensagens (nova, lida, respondida)

## 🔧 Funcionalidades Técnicas Avançadas

### Validações Implementadas
- ✅ **CPF brasileiro** com dígitos verificadores
- ✅ **Telefone brasileiro** com formatação automática
- ✅ **Email** com validação rigorosa
- ✅ **Upload de imagens** com redimensionamento automático
- ✅ **Senhas seguras** com critérios de força

### Sistema de Logs e Auditoria
- ✅ **Ações de usuários** registradas
- ✅ **Erros do sistema** logados
- ✅ **Tentativas de login** monitoradas
- ✅ **Auditoria completa** de mudanças

### Performance e Otimização
- ✅ **Consultas otimizadas** com índices
- ✅ **Paginação eficiente**
- ✅ **Lazy loading** de imagens
- ✅ **Cache de consultas** frequentes

## 🔒 Segurança e Credenciais

### Credenciais Padrão do Sistema
```
🔐 Administrador:
Email: admin@realestate.com
Senha: password

🏢 Corretores:
Email: joao.silva@realestate.com | Senha: password
Email: maria.santos@realestate.com | Senha: password  
Email: pedro.costa@realestate.com | Senha: password
```

**⚠️ IMPORTANTE: Altere todas as senhas padrão após a instalação!**

### Credenciais Antigas (Mantidas para Referência)
```
🔐 Sistema Antigo:
Login Corretor:
- marcos (senha: 1234567)
- maria (senha: 1234567) 
- joao (senha: 1234567)

Login ADM:
- Miguel (senha: 123456)
```

## ✨ Eliminação de Duplicidades

### Códigos Centralizados
- ✅ **Cabeçalho e rodapé** unificados em `views/layouts/main.php`
- ✅ **Funções auxiliares** centralizadas em `includes/functions.php`
- ✅ **Validações** reutilizáveis
- ✅ **Conexão de banco** única em `includes/database.php`
- ✅ **Sistema de autenticação** unificado
- ✅ **CSS padronizado** e otimizado

### Antes vs Depois
- ❌ **Antes**: Código duplicado em cada arquivo
- ✅ **Depois**: Componentes reutilizáveis e includes
- ❌ **Antes**: Múltiplas conexões de banco
- ✅ **Depois**: Singleton pattern para conexão
- ❌ **Antes**: Validações espalhadas
- ✅ **Depois**: Funções centralizadas de validação

## 🚀 Como Usar o Sistema

### Para Desenvolvedores
1. **Instale** seguindo as instruções acima
2. **Execute** `php setup_database.php` 
3. **Configure** suas credenciais de banco
4. **Teste** com as contas padrão
5. **Personalize** conforme necessário

### Estrutura de URLs
```
/index.php                 # Página inicial
/views/auth/login.php      # Login
/views/auth/register.php   # Cadastro  
/admin/                    # Área administrativa
/broker/                   # Área do corretor
/properties/               # Listagem de imóveis
```

## 🔄 Migração de Dados

Os dados antigos em JSON foram preservados e podem ser migrados:
- `data/usuarios.json` → tabela `users`
- `data/mensagens_contato.json` → tabela `contact_messages`
- Imóveis hardcoded → tabela `properties`

## 🎯 Próximos Passos Sugeridos

1. ✅ **SMTP configurado** para emails
2. ⏳ **API REST** para aplicativo mobile
3. ⏳ **Sistema de pagamento** integrado
4. ⏳ **Cache Redis** para performance
5. ⏳ **CDN** para imagens
6. ⏳ **Testes automatizados**
7. ⏳ **Docker** para deploy
8. ⏳ **Backup automático** do banco

## 📈 Melhorias Implementadas

### Organização de Código
- ✅ **Padrão MVC** implementado
- ✅ **PSR-4** autoloading preparado
- ✅ **Separação de responsabilidades**
- ✅ **Código documentado**

### Banco de Dados
- ✅ **Normalização** completa
- ✅ **Relacionamentos** bem definidos
- ✅ **Índices** otimizados
- ✅ **Constraints** de integridade

### Interface de Usuário
- ✅ **Bootstrap 5** atualizado
- ✅ **Ícones** padronizados
- ✅ **Formulários** validados
- ✅ **Feedback** visual consistente

---

**🎉 Sistema completamente reorganizado e modernizado!**
**💼 Pronto para produção com as melhores práticas do mercado**
