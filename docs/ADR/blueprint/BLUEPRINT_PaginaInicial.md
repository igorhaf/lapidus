# BLUEPRINT_PaginaInicial

## Visão
Landing page institucional com seções Hero, Serviços, Sobre, Portfólio, Depoimentos, Contato.

## Entidades
* **pagina_principal_secoes** (id, titulo, subtitulo, conteudo,…)
* **servicos** (id, nome, descricao,…)
* **portfolio_projetos** (id, titulo, descricao,…)
* **depoimentos** (id, nome_cliente, depoimento,…)
* **contatos** (id, nome, email, mensagem,…)

## ERD
`pagina_principal_secoes` 1—∞ `servicos` (tipo_secao='servicos')  
Outras tabelas independentes.

## User Stories
* **US001** – Como _visitante_ quero ver seções informativas para confiar na marca.  
* **US002** – Como _admin_ quero CRUD completo de serviços para manter oferta atualizada.  

## Roadmap
| Versão | Destaques |
|--------|-----------|
| v0.1   | MVP estático, formulário de contato |
| v0.2   | CRUD dinâmico + upload imagens |
| v0.3   | SEO + Performance + Cache |
| v0.4   | UX melhorias + animações |
| v0.5   | Integração CRM + deploy |

## Próximos passos
Gerar migrations (fase 1) seguindo a ERD acima.
