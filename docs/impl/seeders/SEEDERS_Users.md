# SEEDERS - Usuários

## Resumo

Seeder para criação de usuários do sistema, seguindo a arquitetura de domínio do projeto.

## Arquivos Envolvidos

- `database/seeders/UserSeeder.php` - Seeder específico para usuários
- `database/seeders/DatabaseSeeder.php` - Seeder principal (registra UserSeeder)

## Implementação

### UserSeeder

O `UserSeeder` foi criado seguindo os princípios da arquitetura de domínio:

- **Responsabilidade única**: Apenas criação de usuários
- **Idempotência**: Usa `insertOrIgnore` para evitar duplicação
- **Dados realistas**: Usuário administrador com credenciais padrão
- **Feedback**: Exibe mensagem de confirmação

### Credenciais do Admin

- **Email**: `admin@example.com`
- **Senha**: `admin`
- **Nome**: `Administrador`
- **Status**: Email verificado

## Como Executar

### Seeder Específico
```bash
./sail artisan db:seed --class=UserSeeder
```

### Todos os Seeders
```bash
./sail artisan db:seed
```

### Verificar Criação
```bash
./sail exec mysql mysql -u sail -ppassword laravel -e "SELECT name, email FROM users WHERE email = 'admin@example.com';"
```

## Próximos Passos

1. **Criar entidade User no domínio** (se necessário)
2. **Implementar ValueObjects** para Email, Password, etc.
3. **Criar UseCases** para gerenciamento de usuários
4. **Implementar Policies** para autorização
5. **Criar seeders para outros módulos** (Home, Contacts, etc.)

## Status

✅ **Implementado**: Seeder de usuários básico
🔄 **Pendente**: Integração com entidades de domínio
🔄 **Pendente**: Seeders para outros módulos 