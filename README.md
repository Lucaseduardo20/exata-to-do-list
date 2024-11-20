### 📋 Projeto de Gerenciamento de Tarefas

Este projeto é um sistema simples e eficiente para gerenciamento de tarefas, desenvolvido com **Laravel 11** e **Vue 3 (Composition API + TypeScript)**. A integração foi feita usando **Inertia.js**, com **Axios** para requisições específicas, seguindo boas práticas como **DTOs (Laravel Data)** e **Services** para isolar as queries e organizar o código.

---

## 🚀 Tecnologias Utilizadas

- **Backend:** Laravel 11
- **Frontend:** Vue 3 (Composition API + TS)
- **Comunicação Backend/Frontend:** Inertia.js
- **Requisições HTTP:** Axios
- **Design do Código:** DTOs e Services para manter tudo limpo e escalável.

---

## 🎯 Funcionalidades

### 📌 Usuário Comum
- **Criar tarefa:** Adicione novas tarefas para organizar suas atividades.
- **Editar tarefa:** Ajuste os detalhes de uma tarefa existente.
- **Excluir tarefa:** Remova tarefas desnecessárias.
- **Concluir tarefa:** Marque tarefas como concluídas.
- **Filtrar lista de tarefas:** Encontre rapidamente o que precisa.
- **Perfil:** Consulte suas informações diretamente na tela de perfil.

### 🔑 Administrador
- **Ver todas as tarefas:** Acompanhe as atividades de todos os usuários.
- **Filtrar tarefas:** Aplique filtros para refinar a visualização das tarefas.
- **Listagem de usuários:** Gerencie todos os usuários cadastrados no sistema.
- **Excluir usuário:** Remova usuários do sistema.
- **Editar usuário:** Atualize informações de qualquer usuário.
- **Promover usuário:** Transforme usuários comuns em administradores.

---

## 🧪 Testes

O projeto está totalmente coberto com **testes unitários e de integração**, garantindo que todas as funcionalidades funcionem como esperado. Cada rota, ação e fluxo foram testados com cuidado.

---

## 💡 Como Rodar o Projeto

1. Clone o repositório:
   ```bash
   git clone <url-do-repositorio>
   cd <nome-do-projeto>
   ```

2. Instale as dependências do Laravel e do Vue:
   ```bash
   composer install
   npm install
   ```

3. Execute as migrações para criar o banco de dados:
   ```bash
   php artisan migrate --seed
   ```

4. Suba o servidor:
   ```bash
   php artisan serve
   npm run dev
   ```

5. Acesse o sistema em **http://localhost:8000**.

---

## 📚 Organização do Código

- **Services:** Cada funcionalidade principal tem sua lógica isolada, garantindo manutenibilidade e clareza no código.
- **DTOs (Laravel Data):** Estruturas de dados consistentes entre frontend e backend.
- **Testes:** Foco total em cobertura, simulando cenários reais para evitar erros em produção.

---

Esse projeto foi feito com atenção aos detalhes e busca aplicar as melhores práticas do ecossistema Laravel/Vue. 😊
