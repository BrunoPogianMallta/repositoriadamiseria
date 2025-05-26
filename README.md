# 🧪 Teste Técnico – Desenvolvedor PHP Júnior


## 🎯 Objetivo

Avaliar os conhecimentos técnicos fundamentais de PHP (incluindo PHP puro, sem o uso de frameworks), organização de código, lógica de programação, uso de Docker e habilidades básicas com banco de dados. O conhecimento em frameworks como Laravel ou outros será considerado um diferencial.

---

## 📝 Descrição do Teste

Crie uma aplicação PHP simples para **gestão de postagens** com as seguintes funcionalidades:

- ✅ Um usuario deve poder criar uma conta (cadastro).
- ✅ Um usuario deve poder fazer login.
- ✅ Um usuario deve poder criar, editar e excluir postagens.
- ✅ Um usuario deve poder visualizar todas as postagens.
- ✅ Um usuario deve poder visualizar uma postagem específica.
- ✅ Um usuario deve excluir ou ter acesso a postagem de outro usuario.

---

## ⚙️ Requisitos Técnicos

### Linguagem:
- PHP (versão 8.0 ou superior)

### Banco de dados:
- MySQL

### Ferramentas:
- Docker + Docker Compose
- Composer
- Git

## 🧪 O que será avaliado

| Critério                            | Avaliação |
|-------------------------------------|-----------|
| Estrutura do projeto                | ✅        |
| Clareza e organização do código     | ✅    |
| Funcionamento completo da Aplicação | ✅     |
| Uso correto do Docker               | ✅        |
| Boas práticas de versionamento Git  | ✅  |
| README explicativo                  | ✅        |

---

## 📌 Instruções

1. Faça um **fork** ou clone do repositório (ou crie um repositório público no GitHub/GitLab).✅  
2. Monte o ambiente local com Docker + Docker Compose.✅  
3. Implemente o CRUD de tarefas com os campos:✅
- Usuario (nome, email, senha)✅
- Postagem (titulo, conteudo, usuario_id)
4. As Postagem devem ser exibidas em uma página simples (HTML ou JSON via API REST).
5. Envie o link do repositório com o código final.

---

## ✅ Extras (diferenciais, não obrigatórios)

- Interface web com HTML/CSS simples
- Testes automatizados (PHPUnit)
- Utilização de migrations
- Documentação da API (Postman ou Swagger)

---

## 📚 Exemplo de comandos úteis

### Subir os containers:
```bash
docker-compose up -d
```

### Acessar o container PHP:
```bash
docker exec -it nome_do_container_php bash
```

### Rodar migrations (se houver):
```bash
php artisan migrate
```
---

## ⏱ Prazo de entrega

**48 horas** após o envio desta proposta.

---

## 📬 Entrega

Envie o link do seu repositório para: `caique@guimepa.com.br`.

Boa sorte! 💪
