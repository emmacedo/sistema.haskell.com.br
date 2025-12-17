# Changelog - Atualização do Formulário de Cadastro Público

**Data:** 21 de novembro de 2025
**Objetivo:** Padronizar os campos coletados entre o cadastro público e o painel administrativo

## Alterações Realizadas

### 1. Substituição do Campo de Endereço

**ANTES:**
- Campo único `address` (textarea) que não estava sendo salvo no banco de dados

**DEPOIS:**
- Campos estruturados (igual ao painel admin):
  - `cep` - CEP (obrigatório)
  - `logradouro` - Rua/Avenida (obrigatório)
  - `numero` - Número (obrigatório)
  - `complemento` - Apartamento/Sala (opcional)
  - `bairro` - Bairro (obrigatório)
  - `cidade` - Cidade (obrigatório)
  - `estado` - UF (obrigatório, 2 caracteres)

**Impacto:** Corrige o problema crítico onde dados de endereço eram perdidos

---

### 2. Adição do Campo Telefone 2

**NOVO CAMPO:**
- `phone2` - Segundo telefone (opcional)
- Mesma máscara e validação do telefone principal

**Localização na view:** Entre `phone` e `whatsapp`

---

### 3. Integração com ViaCEP

**Funcionalidade adicionada:**
- Busca automática de endereço ao preencher o CEP
- Preenche automaticamente: logradouro, bairro, cidade, estado
- Feedback visual durante a busca (campos desabilitados com "...")
- Tratamento de erros (CEP não encontrado, erro de conexão)

**Comportamento:**
- Ativa ao sair do campo CEP (evento `blur`)
- Requer CEP com 8 dígitos
- Foca automaticamente no campo "Número" após busca bem-sucedida

---

### 4. Máscaras Adicionadas/Atualizadas

**Máscaras implementadas:**
- CEP: `00000-000`
- UF: Apenas 2 letras maiúsculas
- Telefone 2: `(00) 0000-0000` ou `(00) 00000-0000`

**Máscaras já existentes:**
- CNPJ: `00.000.000/0000-00`
- Telefone: `(00) 0000-0000` ou `(00) 00000-0000`
- WhatsApp: `(00) 00000-0000`

---

### 5. Validações Atualizadas

**Controller: `DistributorRegistrationController.php`**

**Validações adicionadas:**
```php
'phone2' => 'nullable|string|max:20',
'cep' => 'required|string|max:10',
'logradouro' => 'required|string|max:255',
'numero' => 'required|string|max:20',
'complemento' => 'nullable|string|max:255',
'bairro' => 'required|string|max:100',
'cidade' => 'required|string|max:100',
'estado' => 'required|string|size:2',
```

**Validação removida:**
```php
'address' => 'required|string|max:500', // REMOVIDO
```

**Mensagens de erro personalizadas:**
- Todos os novos campos têm mensagens em português
- Validação de tamanho para UF (2 caracteres)

---

### 6. Criação do Distribuidor Atualizada

**Campos salvos no banco de dados:**
```php
Distributor::create([
    'company_name' => $validated['company_name'],
    'trade_name' => $validated['trade_name'],
    'cnpj' => $validated['cnpj'],
    'email' => $validated['email'],
    'phone' => $validated['phone'],
    'phone2' => $validated['phone2'] ?? null,      // NOVO
    'whatsapp' => $validated['whatsapp'] ?? null,
    'website' => $validated['website'] ?? null,
    'cep' => $validated['cep'],                    // NOVO
    'logradouro' => $validated['logradouro'],      // NOVO
    'numero' => $validated['numero'],              // NOVO
    'complemento' => $validated['complemento'] ?? null, // NOVO
    'bairro' => $validated['bairro'],              // NOVO
    'cidade' => $validated['cidade'],              // NOVO
    'estado' => $validated['estado'],              // NOVO
    'status' => 'pending',
    'verification_code' => $verificationCode,
    'email_verified_at' => null,
]);
```

---

## Arquivos Modificados

1. **resources/views/frontend/registration/create.blade.php**
   - Substituição do campo `address` por campos estruturados
   - Adição do campo `phone2`
   - Implementação da integração ViaCEP
   - Máscaras adicionais (CEP, UF)

2. **app/Http/Controllers/Frontend/DistributorRegistrationController.php**
   - Atualização das regras de validação
   - Atualização da criação do distribuidor
   - Adição de mensagens de erro personalizadas

---

## Comparação Final: Cadastro Público vs Painel Admin

### Campos Idênticos ✅
- Razão Social
- Nome Fantasia
- CNPJ
- Email
- Telefone
- Telefone 2 (NOVO no público)
- WhatsApp
- Website
- CEP (NOVO no público)
- Logradouro (NOVO no público)
- Número (NOVO no público)
- Complemento (NOVO no público)
- Bairro (NOVO no público)
- Cidade (NOVO no público)
- Estado/UF (NOVO no público)
- Cidades Atendidas

### Diferenças Remanescentes
1. **Status:** Painel admin permite selecionar, público sempre cria como "pending"
2. **Verificação de Email:** Apenas no público (gera código e envia email)
3. **Vendedores:** Público permite cadastrar durante o registro, admin adiciona depois
4. **Interface de Cidades:** Público usa autocomplete, admin usa sistema de 3 listas

---

## Testes Realizados

1. ✅ Sintaxe PHP verificada (sem erros)
2. ✅ Views compiladas com sucesso
3. ✅ Rotas validadas
4. ✅ Cache limpo

---

## Recomendações para Testes Manuais

1. **Testar busca de CEP:**
   - Digite um CEP válido e verifique se os campos são preenchidos automaticamente
   - Teste com CEP inválido e verifique a mensagem de erro
   - Teste sem conexão com internet

2. **Testar máscaras:**
   - Digite números nos campos de CEP, CNPJ, telefones
   - Verifique se as máscaras são aplicadas corretamente
   - Digite letras no campo UF e verifique se fica em maiúsculas

3. **Testar validações:**
   - Tente submeter o formulário sem preencher campos obrigatórios
   - Verifique se as mensagens de erro aparecem corretamente

4. **Testar cadastro completo:**
   - Preencha o formulário completamente
   - Verifique se o distribuidor é criado no banco de dados
   - Verifique se todos os campos estruturados foram salvos corretamente

---

## Próximos Passos (Opcional)

1. Criar FormRequest separado para unificar validações
2. Adicionar validação de CNPJ completa (dígitos verificadores)
3. Implementar rate limiting nas rotas de verificação e reenvio
4. Adicionar expiração para códigos de verificação
5. Criar testes automatizados para o fluxo de cadastro

---

## Problema Corrigido

**PROBLEMA CRÍTICO RESOLVIDO:**
O formulário de cadastro público estava tentando salvar o campo `address` que não existe no modelo `Distributor`, resultando em **perda de dados de endereço**.

Agora, o cadastro público coleta e salva os mesmos campos estruturados que o painel administrativo, garantindo **integridade e consistência dos dados**.
