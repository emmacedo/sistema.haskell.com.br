# Documentação - CRUD Administrativo com AdminLTE

Este documento lista todos os arquivos criados para o painel administrativo com AdminLTE, incluindo controladores CRUD completos para Distribuidores, Vendedores e Mensagens de Contato.

## Arquivos Criados

### 1. FormRequests (Validação)

#### `/app/Http/Requests/StoreDistributorRequest.php`
- **Descrição**: Validação para criação de distribuidores
- **Regras**: Valida todos os campos obrigatórios (razão social, nome fantasia, CNPJ, e-mail, telefone, endereço, status)
- **Regras especiais**: CNPJ e e-mail devem ser únicos, cities é array obrigatório com pelo menos 1 cidade
- **Mensagens**: Todas em português com labels traduzidos

#### `/app/Http/Requests/UpdateDistributorRequest.php`
- **Descrição**: Validação para atualização de distribuidores
- **Diferenças**: Usa Rule::unique()->ignore() para permitir CNPJ e e-mail do próprio registro
- **Mesmas regras**: do StoreDistributorRequest

#### `/app/Http/Requests/StoreSellerRequest.php`
- **Descrição**: Validação para criação de vendedores
- **Regras**: Valida distributor_id, nome, e-mail, telefone (obrigatórios), whatsapp e cargo (opcionais)
- **Mensagens**: Todas em português

#### `/app/Http/Requests/UpdateSellerRequest.php`
- **Descrição**: Validação para atualização de vendedores
- **Mesmas regras**: do StoreSellerRequest

---

### 2. Controllers

#### `/app/Http/Controllers/Admin/DistributorController.php`
- **Descrição**: Controller completo para gerenciamento de distribuidores
- **Métodos**:
  - `index()`: Lista com paginação (15/página), filtros de status e busca
  - `create()`: Formulário de criação com cidades carregadas
  - `store()`: Salva distribuidor e sincroniza cidades (transaction)
  - `show()`: Exibe detalhes completos com relacionamentos
  - `edit()`: Formulário de edição com dados pré-preenchidos
  - `update()`: Atualiza distribuidor e sincroniza cidades
  - `destroy()`: Soft delete do distribuidor
  - `approve()`: Aprova distribuidor (status=approved) e envia e-mail de boas-vindas
  - `reject()`: Rejeita distribuidor (status=rejected) com motivo e envia e-mail
- **Serviços**: Usa EmailService para envio de e-mails
- **Relacionamentos**: Carrega cities, sellers, contactMessages

#### `/app/Http/Controllers/Admin/SellerController.php`
- **Descrição**: Controller CRUD para vendedores
- **Métodos**:
  - `index()`: Lista com filtro por distribuidor e busca
  - `create()`: Formulário com select de distribuidores
  - `store()`: Cria vendedor
  - `show()`: Detalhes do vendedor (preparado mas não usado)
  - `edit()`: Formulário de edição
  - `update()`: Atualiza vendedor
  - `destroy()`: Exclui vendedor
- **Relacionamentos**: Carrega distributor em todas as listagens

#### `/app/Http/Controllers/Admin/ContactMessageController.php`
- **Descrição**: Controller para gerenciamento de mensagens de contato
- **Métodos**:
  - `index()`: Lista com filtro de lidas/não lidas e busca, ordenadas por não lidas primeiro
  - `show()`: Exibe mensagem e marca automaticamente como lida
  - `markAsRead()`: Marca mensagem como lida via POST
  - `destroy()`: Exclui mensagem
- **Relacionamentos**: Carrega distributor, city em todas as operações

---

### 3. Views - Distribuidores

#### `/resources/views/admin/distributors/index.blade.php`
- **Descrição**: Listagem de distribuidores com tabela, filtros e ações
- **Recursos**:
  - Tabela com badges de status (pendente/aprovado/rejeitado)
  - Filtros: busca por texto e status
  - Ações inline: visualizar, editar, aprovar, rejeitar, excluir
  - Botões com SweetAlert2 para confirmações
  - Aprovação com confirmação simples
  - Rejeição com modal de textarea para motivo
  - Paginação do Laravel
  - Alertas de sucesso/erro
- **Colunas**: ID, Razão Social, Nome Fantasia, CNPJ, E-mail, Status, Qtd Cidades, Qtd Vendedores, Ações

#### `/resources/views/admin/distributors/create.blade.php`
- **Descrição**: Formulário de criação de distribuidor
- **Componentes AdminLTE**:
  - x-adminlte-input: campos de texto
  - x-adminlte-textarea: endereço
  - x-adminlte-select2: cidades (múltiplo)
  - x-adminlte-select: status
- **Recursos**:
  - Select2 com tema Bootstrap4 para cidades
  - Máscaras jQuery para CNPJ, telefone, whatsapp
  - Icons do FontAwesome em todos os campos
  - Campo de motivo de rejeição (condicional)
  - Validação com exibição de erros
  - Botões: Salvar e Voltar

#### `/resources/views/admin/distributors/edit.blade.php`
- **Descrição**: Formulário de edição de distribuidor
- **Similar ao create.blade.php** mas:
  - Usa método PUT
  - Pré-preenche todos os campos com dados do distribuidor
  - Cidades já selecionadas vindas do relacionamento
  - Validação de unique ignora o próprio registro

#### `/resources/views/admin/distributors/show.blade.php`
- **Descrição**: Visualização detalhada do distribuidor
- **Layout**: 2 colunas (8/4)
- **Cards**:
  - **Informações Gerais**: todos os dados do distribuidor
  - **Cidades Atendidas**: tabela com cidade, estado e código IBGE
  - **Vendedores**: tabela com nome, e-mail, telefone, cargo
  - **Mensagens de Contato**: últimas 5 mensagens com status lida/não lida
  - **Status** (sidebar): badge grande, botões de ações
  - **Estatísticas** (sidebar): totais de cidades, vendedores, mensagens
- **Ações**:
  - Aprovar (se pendente)
  - Rejeitar com modal SweetAlert2 (se pendente)
  - Editar
  - Excluir com confirmação
  - Voltar

---

### 4. Views - Vendedores

#### `/resources/views/admin/sellers/index.blade.php`
- **Descrição**: Listagem de vendedores com filtros
- **Recursos**:
  - Tabela simples com dados do vendedor
  - Filtros: busca por texto e por distribuidor
  - Ações: editar e excluir
  - SweetAlert2 para confirmação de exclusão
  - Paginação
- **Colunas**: ID, Nome, E-mail, Telefone, WhatsApp, Cargo, Distribuidor, Ações

#### `/resources/views/admin/sellers/create.blade.php`
- **Descrição**: Formulário de criação de vendedor
- **Componentes**:
  - x-adminlte-select2: distribuidor
  - x-adminlte-input: nome, e-mail, telefone, whatsapp, cargo
- **Recursos**:
  - Select2 para distribuidor
  - Máscaras para telefone e whatsapp
  - Icons do FontAwesome
  - Campos opcionais: whatsapp e cargo

#### `/resources/views/admin/sellers/edit.blade.php`
- **Descrição**: Formulário de edição de vendedor
- **Similar ao create.blade.php**:
  - Método PUT
  - Campos pré-preenchidos
  - Mesmos componentes e recursos

---

### 5. Views - Mensagens de Contato

#### `/resources/views/admin/contact-messages/index.blade.php`
- **Descrição**: Listagem de mensagens com destaque para não lidas
- **Recursos**:
  - Tabela com linha destacada (table-warning) para mensagens não lidas
  - Badges: lida (verde) / não lida (amarelo)
  - Filtros: busca por texto e status (todas/lidas/não lidas)
  - Ações: visualizar, marcar como lida, excluir
  - Botão inline para marcar como lida (só aparece se não lida)
  - SweetAlert2 para confirmação de exclusão
  - Ordenação: não lidas primeiro, depois por data
- **Colunas**: ID, Status, Remetente, E-mail, Telefone, Distribuidor, Cidade, Data, Ações

#### `/resources/views/admin/contact-messages/show.blade.php`
- **Descrição**: Visualização completa da mensagem
- **Layout**: 2 colunas (8/4)
- **Cards principais**:
  - **Mensagem**: remetente, e-mail, telefone, data, mensagem completa em card
  - **Informações do Distribuidor**: todos os dados e cidades atendidas (máx 10, depois "e mais X...")
  - **Cidade de Interesse**: cidade, estado, código IBGE
- **Sidebar**:
  - **Ações**: marcar como lida (se não lida), ver distribuidor, voltar, excluir
  - **Informações Adicionais**: ID, data de recebimento, data de leitura
  - **Contato Rápido**: links clicáveis para e-mail, telefone, WhatsApp
- **Recursos**:
  - Marca automaticamente como lida ao abrir
  - Badge de status
  - Links funcionais (mailto:, tel:, WhatsApp)
  - SweetAlert2 para confirmações

---

### 6. Rotas

#### `/routes/admin-routes-example.php`
- **Descrição**: Arquivo exemplo com todas as rotas necessárias
- **Instruções**: Copiar conteúdo para dentro de um grupo com middleware auth no web.php
- **Rotas criadas**:
  - `distributors.*`: resource completo (index, create, store, show, edit, update, destroy)
  - `distributors.approve`: PUT para aprovar
  - `distributors.reject`: PUT para rejeitar
  - `sellers.*`: resource completo
  - `contact-messages.*`: resource parcial (index, show, destroy)
  - `contact-messages.mark-as-read`: POST para marcar como lida
- **Prefixo sugerido**: admin
- **Name prefix**: admin.

---

## Recursos Implementados

### AdminLTE
- ✅ Usa `@extends('adminlte::page')` em todas as views
- ✅ Componentes x-adminlte-* (card, input, select, select2, textarea)
- ✅ Ícones FontAwesome em todos os campos
- ✅ Layout responsivo Bootstrap 4
- ✅ Tema consistente com cores do AdminLTE

### JavaScript / Bibliotecas
- ✅ **SweetAlert2**: confirmações elegantes para exclusões, aprovações e rejeições
- ✅ **Select2**: selects com busca para cidades e distribuidores
- ✅ **jQuery Mask**: máscaras para CNPJ, telefone e WhatsApp
- ✅ **DataTables**: preparado nas tabelas (id nas tables)

### Funcionalidades
- ✅ **Paginação**: todas as listagens usam paginate(15)
- ✅ **Filtros**: busca por texto e filtros específicos
- ✅ **Validação**: FormRequests com mensagens em português
- ✅ **Transações**: store/update de distribuidor usa DB::transaction
- ✅ **Soft Delete**: distribuidor usa SoftDeletes
- ✅ **E-mails**: integração com EmailService (aprovação e rejeição)
- ✅ **Badges**: status visual em todas as listagens
- ✅ **Alertas**: sucesso/erro com SweetAlert2
- ✅ **Relacionamentos**: eager loading em todas as consultas

### Português
- ✅ Todos os labels, placeholders e mensagens em português
- ✅ Validação com attributes traduzidos
- ✅ Datas formatadas em d/m/Y H:i

---

## Como Usar

### 1. Adicionar as rotas
Abra o arquivo `routes/web.php` e adicione:

```php
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Cole o conteúdo de routes/admin-routes-example.php aqui
});
```

### 2. Configurar AdminLTE (se ainda não configurado)
```bash
composer require jeroennoten/laravel-adminlte
php artisan adminlte:install
```

### 3. Acessar as rotas
- Distribuidores: `/admin/distributors`
- Vendedores: `/admin/sellers`
- Mensagens: `/admin/contact-messages`

### 4. Menu AdminLTE (opcional)
Adicione no arquivo `config/adminlte.php`:

```php
'menu' => [
    [
        'text' => 'Distribuidores',
        'url'  => 'admin/distributors',
        'icon' => 'fas fa-building',
    ],
    [
        'text' => 'Vendedores',
        'url'  => 'admin/sellers',
        'icon' => 'fas fa-users',
    ],
    [
        'text' => 'Mensagens',
        'url'  => 'admin/contact-messages',
        'icon' => 'fas fa-envelope',
        'badge' => ContactMessage::unread()->count(), // opcional
        'badge_color' => 'warning',
    ],
],
```

---

## Resumo de Arquivos

**Total de arquivos criados: 16**

- **4 FormRequests** (validação)
- **3 Controllers** (lógica de negócio)
- **8 Views Blade** (interface)
- **1 Arquivo de rotas exemplo**

---

## Notas Técnicas

1. **Relacionamentos**: Todos os controllers usam eager loading para evitar N+1
2. **Segurança**: CSRF em todos os formulários, validação server-side
3. **UX**: Confirmações antes de ações destrutivas, feedback visual imediato
4. **Performance**: Paginação em todas as listagens, queries otimizadas
5. **Manutenibilidade**: Código organizado, seguindo padrões Laravel e PSR

---

## Próximos Passos (Opcional)

- [ ] Adicionar DataTables com AJAX para listagens grandes
- [ ] Implementar exportação para Excel/PDF
- [ ] Adicionar gráficos no dashboard
- [ ] Criar logs de atividades (auditoria)
- [ ] Adicionar permissões/ACL com Laravel Permission
- [ ] Criar API REST para mobile
