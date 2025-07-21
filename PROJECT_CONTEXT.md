# PROJECT_CONTEXT

Estou criando um **site institucional** para uma empresa de consultoria digital que comercializa soluções sob medida para uso corporativo (cardápios de restaurantes, sistemas de cursos E‑learning, CRMs imobiliários etc.).  
O portal terá:

- **Área pública** – landing page, blog e páginas de serviço.  
- **Área administrativa** – painel (Laravel Breeze + Inertia.js) para CRUD de conteúdo, leads e métricas.  
- **API REST v1** (`/api/v1`) consumida pelo SPA e por integrações de parceiros.

## Stack

| Camada         | Tecnologia                                    |
|----------------|-----------------------------------------------|
| Backend        | **Laravel 11** (API + SSR via Inertia)        |
| Contêiner      | **Laravel Sail** (Docker)                     |
| Frontend       | **Vue 3** (Vite + Composition API)            |
| Estilo         | **TailwindCSS** (Design System)               |
| Bridge         | **Inertia.js** (SPA/SSR bridge)               |
| Banco de Dados | **MySQL 8**                                   |

## Conventions

| Responsabilidade                     | Caminho padrão                                                           |
|-------------------------------------|--------------------------------------------------------------------------|
| **Entities / Value Objects**        | `app/Domain/<Module>/Entities`                                           |
| **Domain Services / Actions**       | `app/Domain/<Module>/{Services,Actions}`                                 |
| **Application UseCases**            | `app/Application/<Module>/UseCases`                                      |
| **Controllers (single-action)**     | `app/Http/Controllers/Api/V1/<Module>/`                                  |
| **Vue pages / components**          | `resources/js/modules/<module>/`                                         |
| **Design Tokens (JSON)**            | `resources/design/<module>.json`                                         |
| **Tests**                           | `tests/{Unit,Feature,Browser}/<Module>/`                                 |

*(A árvore completa está em `docs/structure.md`, gerada automaticamente.)*

## Directory layout

**app/**  
├─ **Domain/** – Regras de negócio por módulo  
│   └─ **<Modulo>/**  
│       ├─ **Entities/** – Objetos do domínio (POPOs)  
│       ├─ **ValueObjects/** – Objetos imutáveis (Email, Slug, Money…)  
│       ├─ **Enums/** – status, tipos, categorias  
│       ├─ **Events/** – Domain Events (ex.: PedidoAprovado)  
│       ├─ **Interfaces/**  
│       │   ├─ **Repositories/** – contratos de acesso a dados  
│       │   └─ **Services/** – contratos de serviços de domínio  
│       ├─ **Services/** – lógica complexa, sem dependência de framework  
│       ├─ **Actions/** – comandos como `CalcularFreteAction`  
│       └─ **Traits/** – comportamentos reutilizáveis  

**app/Application/**  
└─ **<Modulo>/**  
    ├─ **UseCases/** – coordenação de fluxo entre Services/Repositórios/Policies  
    ├─ **DTOs/** – estruturas de entrada/saída para UseCases  
    └─ **Queries/** – consultas otimizadas / read‑model  

**app/Infra/**  
├─ **Repositories/**  
│   └─ **Eloquent/** – implementações concretas dos contratos de Domain  
├─ **Gateways/** – SDKs / clientes HTTP de serviços externos  
├─ **Services/** – serviços de infraestrutura (ex.: gerador de PDF)  
└─ **Providers/** – bindings Interface → Implementation no Laravel  

**app/Http/**  
├─ **Controllers/Api/V1/<Modulo>/** – Controllers `__invoke`, chamando UseCases  
├─ **Requests/<Modulo>/** – FormRequests para validação (store/update)  
└─ **Middleware/** – filtros, autenticação, tenant, throttle  

**app/Policies/** – definição das políticas de autorização  
**app/Rules/** – validadores customizados (ex.: CpfRule)  
**app/Listeners/** – listeners de eventos de domínio  
**app/ViewComposers/** – compartilhamento de dados para Blade ou Vue  
**app/Providers/** – registro de service providers do aplicativo  

**database/**  
├─ **factories/** – arquivos `<Modulo>Factory.php` para testes  
├─ **migrations/** – arquivos de definição de banco de dados  
└─ **seeders/** – modelos para popular o banco em ambiente de desenvolvimento  

**resources/**  
├─ **js/modules/<modulo>/** – SPA: páginas, componentes, e arquivos de config  
├─ **views/<modulo>/** – Blade views para fallback ou SSR  
├─ **design/<modulo>.json** – tokens de design (cores, espaçamentos etc.)  
└─ **composers/<modulo>.js** – scripts dinâmicos globais (ex.: menu)  

**public/assets/<modulo>/** – logos, ícones e imagens otimizadas  

**tests/**  
├─ **Unit/<Modulo>/** – testes de lógica isolada (Entities, Services)  
├─ **Feature/<Modulo>/** – testes HTTP com banco em memória  
└─ **Browser/<Modulo>/** – testes E2E (Dusk ou Playwright)  

**contracts/**  
├─ **api/<modulo>.openapi.yaml** – contrato REST/OpenAPI por módulo  
├─ **events/** – contratos de eventos assíncronos  
└─ **services/** – interfaces de integração com outros sistemas  

**docs/**  
├─ **structure.md** – snapshot da árvore de diretórios  
├─ **README_<Modulo>.md** – visão e instruções por módulo  
└─ **ADR/** – Architecture Decision Records, se aplicável  
