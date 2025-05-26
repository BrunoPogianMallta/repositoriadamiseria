# API Blog - PHP com JWT

Para ter acesso ao front pode-se usar o live server após o container estiver up
acessando o index.html

## Endpoints

### POST /register
Cria um novo usuário.

**JSON Body:**
```json
{
  "name": "João",
  "email": "joao@example.com",
  "password": "senha123"
}
```

---

### POST /login
Autentica o usuário e retorna um token JWT.

**JSON Body:**
```json
{
  "email": "joao@example.com",
  "password": "senha123"
}
```

**Resposta:**
```json
{
  "token": "JWT_AQUI",
  "user": {
    "id": 1,
    "name": "João",
    "email": "joao@example.com"
  }
}
```

---

### POST /posts
Cria um post. Requer token JWT no header.

**Header:**
```
Authorization: Bearer SEU_TOKEN
```

**JSON Body:**
```json
{
  "title": "Título do Post",
  "content": "Conteúdo do post"
}
```

---

### GET /posts
Lista todos os posts.

---

### GET /posts/{id}
Retorna os dados de um post pelo ID.

---

### PUT /posts/{id}
Atualiza um post. Requer token JWT do autor.

**JSON Body:**
```json
{
  "title": "Novo título",
  "content": "Novo conteúdo"
}
```

---

### DELETE /posts/{id}
Deleta um post. Requer token JWT do autor.
