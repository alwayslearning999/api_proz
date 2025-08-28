# API de Gerenciamento de Usuários

Esta API permite gerenciar usuários em um sistema, com funcionalidades para criar, listar e excluir usuários. Ela utiliza o banco de dados MySQL e é implementada em PHP, hospedada em um servidor XAMPP.

## Configuração
- **URL base**: `http://localhost/backend/api_noite.php`
- **Métodos suportados**: POST, GET, DELETE
- **Formato de resposta**: JSON
- **Banco de dados**: MySQL, tabela `api_usuarios`

## Endpoints

### 1. Criar um Usuário (POST)
Cria um novo usuário no sistema.

- **Método**: POST
- **URL**: `http://localhost/backend/api_noite.php`
- **Cabeçalhos**:
  - `Content-Type: application/json`
- **Corpo da Requisição** (JSON):
  ```json
  {
    "nome": "string",
    "email": "string",
    "senha": "string",
    "telefone": "string",
    "endereco": "string",
    "estado": "string",
    "data_nascimento": "YYYY-MM-DD"
  }
  ```
  - Requisitos:
    - `nome`: Nome do usuário (obrigatório).
    - `email`: E-mail válido com `@` (obrigatório).
    - `senha`: Mínimo 8 caracteres, com 1 maiúscula, 1 minúscula, 1 número e 1 caractere especial (`@$!%?&`) (obrigatório).
    - `telefone`: Entre 9 e 13 caracteres (obrigatório).
    - `endereco`: Endereço do usuário (obrigatório).
    - `estado`: Estado (ex.: "SP") (obrigatório).
    - `data_nascimento`: Data no formato `YYYY-MM-DD` (obrigatório).
- **Respostas**:
  - **Sucesso (200 OK)**:
    ```json
    {
      "mensagem": "Cliente cadastrado com sucesso",
      "cliente": {
        "id": "UUID",
        "nome": "string",
        "email": "string",
        "senha": "string (hash)",
        "telefone": "string",
        "endereco": "string",
        "estado": "string",
        "data_nascimento": "YYYY-MM-DD",
        "criado_em": "YYYY-MM-DD HH:MM:SS"
      }
    }
    ```
  - **Erros**:
    - **400 Bad Request**: Campos obrigatórios faltando.
      ```json
      {"erro": "Todos os campos são obrigatórios"}
      ```
    - **400 Bad Request**: E-mail já existe.
      ```json
      {"erro": "Usuário já existe"}
      ```
    - **400 Bad Request**: Telefone inválido (menos de 9 ou mais de 13 caracteres).
      ```json
      {"erro": "Número inserido incorreto"}
      ```
    - **400 Bad Request**: E-mail sem `@`.
      ```json
      {"erro": "Email inserido inválido"}
      ```
    - **400 Bad Request**: Senha não atende aos requisitos.
      ```json
      {"erro": "A senha deve conter mínimo 8 caracteres, pelo menos 1 maiúscula, 1 minúscula, 1 número e 1 caractere especial"}
      ```
- **Exemplo de Requisição**:
  ```http
  POST http://localhost/backend/api_noite.php
  Content-Type: application/json

  {
    "nome": "João Silva",
    "email": "joao.silva@example.com",
    "senha": "Senha_sn1!",
    "telefone": "777777777",
    "endereco": "Rua A, 123",
    "estado": "SP",
    "data_nascimento": "1990-01-01"
  }
  ```
- **Exemplo de Resposta**:
  ```json
  {
    "mensagem": "Cliente cadastrado com sucesso",
    "cliente": {
      "id": "123e4567-e89b-12d3-a456-426614174000",
      "nome": "João Silva",
      "email": "joao.silva@example.com",
      "senha": "$2y$10$...",
      "telefone": "777777777",
      "endereco": "Rua A, 123",
      "estado": "SP",
      "data_nascimento": "1990-01-01",
      "criado_em": "2025-08-27 21:22:00"
    }
  }
  ```

### 2. Listar Todos os Usuários (GET)
Retorna a lista de todos os usuários cadastrados.

- **Método**: GET
- **URL**: `http://localhost/backend/api_noite.php`
- **Cabeçalhos**: Nenhum necessário.
- **Respostas**:
  - **Sucesso (200 OK)**:
    ```json
    {
      "mensagem": "Lista de usuários",
      "usuarios": [
        {
          "id": "UUID",
          "nome": "string",
          "email": "string",
          "telefone": "string",
          "endereco": "string",
          "estado": "string",
          "data_nascimento": "YYYY-MM-DD",
          "criado_em": "YYYY-MM-DD HH:MM:SS"
        },
        ...
      ]
    }
    ```
  - **Erro (404 Not Found)**: Nenhum usuário cadastrado.
    ```json
    {"erro": "Nenhum usuário encontrado"}
    ```
- **Exemplo de Requisição**:
  ```http
  GET http://localhost/backend/api_noite.php
  ```
- **Exemplo de Resposta**:
  ```json
  {
    "mensagem": "Lista de usuários",
    "usuarios": [
      {
        "id": "123e4567-e89b-12d3-a456-426614174000",
        "nome": "João Silva",
        "email": "joao.silva@example.com",
        "telefone": "777777777",
        "endereco": "Rua A, 123",
        "estado": "SP",
        "data_nascimento": "1990-01-01",
        "criado_em": "2025-08-27 21:22:00"
      }
    ]
  }
  ```

### 3. Obter Usuário por ID (GET)
Retorna os dados de um usuário específico com base no ID.

- **Método**: GET
- **URL**: `http://localhost/backend/api_noite.php?id={UUID}`
- **Parâmetros**:
  - `id`: UUID do usuário (obrigatório, no formato `xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx`).
- **Cabeçalhos**: Nenhum necessário.
- **Respostas**:
  - **Sucesso (200 OK)**:
    ```json
    {
      "mensagem": "Usuário encontrado",
      "usuario": {
        "id": "UUID",
        "nome": "string",
        "email": "string",
        "telefone": "string",
        "endereco": "string",
        "estado": "string",
        "data_nascimento": "YYYY-MM-DD",
        "criado_em": "YYYY-MM-DD HH:MM:SS"
      }
    }
    ```
  - **Erro (404 Not Found)**: Usuário não encontrado.
    ```json
    {"erro": "Usuário não encontrado"}
    ```
- **Exemplo de Requisição**:
  ```http
  GET http://localhost/backend/api_noite.php?id=123e4567-e89b-12d3-a456-426614174000
  ```
- **Exemplo de Resposta**:
  ```json
  {
    "mensagem": "Usuário encontrado",
    "usuario": {
      "id": "123e4567-e89b-12d3-a456-426614174000",
      "nome": "João Silva",
      "email": "joao.silva@example.com",
      "telefone": "777777777",
      "endereco": "Rua A, 123",
      "estado": "SP",
      "data_nascimento": "1990-01-01",
      "criado_em": "2025-08-27 21:22:00"
    }
  }
  ```

### 4. Excluir Usuário por ID (DELETE)
Exclui um usuário com base no ID.

- **Método**: DELETE
- **URL**: `http://localhost/backend/api_noite.php?id={UUID}`
- **Parâmetros**:
  - `id`: UUID do usuário (obrigatório, no formato `xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx`).
- **Cabeçalhos**: Nenhum necessário.
- **Respostas**:
  - **Sucesso (200 OK)**:
    ```json
    {"mensagem": "Cliente excluído com sucesso"}
    ```
  - **Erro (400 Bad Request)**: ID não fornecido.
    ```json
    {"erro": "ID é obrigatório"}
    ```
  - **Erro (404 Not Found)**: Usuário não encontrado.
    ```json
    {"erro": "Usuário não encontrado"}
    ```
- **Exemplo de Requisição**:
  ```http
  DELETE http://localhost/backend/api_noite.php?id=123e4567-e89b-12d3-a456-426614174000
  ```
- **Exemplo de Resposta**:
  ```json
  {
    "mensagem": "Cliente excluído com sucesso"
  }
  ```