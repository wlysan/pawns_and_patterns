# Plugin de Autenticação de Usuários - Simplificado
## PetStyle Boutique - Loja de Roupas e Acessórios para Pets na Irlanda

Este plugin fornece as funcionalidades básicas de autenticação de usuários para a loja online PetStyle Boutique, incluindo registro, login e perfil de usuário.

### Funcionalidades

1. **Registro de Usuários**
   - Nome e sobrenome
   - Endereço de e-mail (com validação)
   - Senha segura (com validação de força)
   - Aceitação da política de privacidade
   - Verificação de e-mail (template incluído)

2. **Login de Usuários**
   - Autenticação por e-mail e senha
   - Funcionalidade "Lembrar-me" com cookies seguros
   - Redirecionamento para páginas protegidas
   - Proteção contra tentativas inválidas

3. **Perfil de Usuário**
   - Visualização de informações básicas
   - Atualização de número de telefone
   - Histórico de pedidos recentes
   - Perfis de pets

4. **Segurança**
   - Senhas armazenadas com hash
   - Proteção contra injeção SQL
   - Sanitização de entrada de dados
   - Validação avançada
   - Status de usuário (ativo, suspenso, etc.)
   - Exclusão lógica (safe delete)

### Requisitos Técnicos

- PHP 7.4 ou superior
- MySQL/MariaDB
- PDO para conexão ao banco de dados
- Sessões PHP

### Instalação

1. Copie os arquivos para a pasta `plugins/user_auth/` da sua aplicação
2. Execute o script de instalação do banco de dados: `php plugins/user_auth/database/install.php`
3. Verifique se as rotas foram registradas corretamente

### Rotas Disponíveis

- `/user/login` - Página de login
- `/user/register` - Página de registro
- `/user/profile` - Perfil do usuário (acesso protegido)
- `/user/logout` - Encerrar sessão

### API

O plugin oferece acesso via API para integração com aplicativos móveis ou sistemas externos:

- `POST /user/login` - Autenticação via API
- `POST /user/register` - Registro via API
- `GET /user/profile` - Obter dados do perfil (requer autenticação)

Para usar a API, envie a chave de API no cabeçalho `X-API-Key`.

### Adaptações

Este plugin é deliberadamente simplificado para focar apenas na autenticação básica. Funcionalidades de endereço e outros detalhes do usuário serão implementados em plugins separados.

### Personalizações para o Mercado Irlandês

- Textos em inglês britânico (conforme usado na Irlanda)
- Formatação de data DD/MM/YYYY
- Suporte para números de telefone no formato irlandês

### Status de Usuário

O sistema usa um campo `status` do tipo ENUM para controlar o estado dos registros:

- `Ativo` - Usuário ativo e funcional
- `Inativo` - Conta temporariamente inativa
- `Pendente` - Aguardando verificação ou ativação
- `Verificado` - E-mail confirmado
- `Suspenso` - Conta temporariamente bloqueada
- `Banido` - Acesso permanentemente bloqueado
- Entre outros...

---

© 2025 PetStyle Boutique - Todos os direitos reservados