<script setup>
import { ref, computed } from "vue";
import {router} from "@inertiajs/vue3";

// Mock inicial de usuários
const users = ref([
    { id: 1, name: "João Silva", email: "joao@empresa.com", status: "Ativo" },
    { id: 2, name: "Maria Souza", email: "maria@empresa.com", status: "Inativo" },
    { id: 3, name: "Carlos Almeida", email: "carlos@empresa.com", status: "Ativo" },
]);

// Mock inicial de tarefas (apenas para visualização)
const tasks = ref([
    { id: 1, userId: 1, title: "Estudar Vue.js", status: "Pendente" },
    { id: 2, userId: 2, title: "Criar backend", status: "Concluída" },
    { id: 3, userId: 1, title: "Ajustar front-end", status: "Pendente" },
]);

// Variáveis de controle
const showModal = ref(false);
const showTaskModal = ref(false);
const currentUser = ref(null);
const filter = ref({ name: "", email: "", status: "" });
const sortKey = ref("name"); // Chave de ordenação padrão
const sortOrder = ref("asc"); // Ordem padrão (crescente)

// Funções de controle
const openModal = () => {
    currentUser.value = { id: null, name: "", email: "", status: "Ativo" };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const saveUser = () => {
    if (currentUser.value.name.trim() && currentUser.value.email.trim()) {
        if (currentUser.value.id) {
            // Aqui você faria uma requisição ao backend para atualizar o usuário
            router.put(`/users/${currentUser.value.id}`, currentUser.value, {
                onSuccess: () => {
                    const index = users.value.findIndex((user) => user.id === currentUser.value.id);
                    users.value[index] = { ...currentUser.value };
                    closeModal();
                },
            });
        } else {
            // Aqui você faria uma requisição ao backend para criar um novo usuário
            router.post("/users", currentUser.value, {
                onSuccess: () => {
                    users.value.push({ ...currentUser.value, id: users.value.length + 1 });
                    closeModal();
                },
            });
        }
    }
};

const editUser = (user) => {
    currentUser.value = { ...user };
    showModal.value = true;
};

const toggleStatus = (user) => {
    const newStatus = user.status === "Ativo" ? "Inativo" : "Ativo";
    router.put(`/users/${user.id}/status`, { ...user, status: newStatus }, {
        onSuccess: () => {
            user.status = newStatus;
        },
    });
};

const viewTasks = (user) => {
    // Filtra as tarefas associadas ao usuário
    const userTasks = tasks.value.filter((task) => task.userId === user.id);
    alert(`Tarefas de ${user.name}: \n${userTasks.map((task) => task.title).join("\n")}`);
};

// Filtragem e Ordenação
const filteredUsers = computed(() => {
    return users.value
        .filter((user) => {
            return (
                (filter.value.name ? user.name.toLowerCase().includes(filter.value.name.toLowerCase()) : true) &&
                (filter.value.email ? user.email.toLowerCase().includes(filter.value.email.toLowerCase()) : true) &&
                (filter.value.status ? user.status.toLowerCase().includes(filter.value.status.toLowerCase()) : true)
            );
        })
        .sort((a, b) => {
            const aValue = a[sortKey.value].toLowerCase();
            const bValue = b[sortKey.value].toLowerCase();
            if (aValue < bValue) return sortOrder.value === "asc" ? -1 : 1;
            if (aValue > bValue) return sortOrder.value === "asc" ? 1 : -1;
            return 0;
        });
});
</script>

<template>
    <section class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-[#2f2c2c]">Gerenciador de Usuários</h1>
            <button
                @click="openModal"
                class="bg-[#f9a01b] text-white px-6 py-3 rounded-lg hover:bg-[#e68a00] transition-colors"
            >
                Novo Usuário
            </button>
        </div>

        <div class="mb-4 flex gap-4">
            <input
                v-model="filter.name"
                type="text"
                placeholder="Filtrar por nome"
                class="w-full p-3 border border-[#697076] rounded-lg"
            />
            <input
                v-model="filter.email"
                type="text"
                placeholder="Filtrar por e-mail"
                class="w-full p-3 border border-[#697076] rounded-lg"
            />
            <input
                v-model="filter.status"
                type="text"
                placeholder="Filtrar por status"
                class="w-full p-3 border border-[#697076] rounded-lg"
            />
        </div>

        <table class="w-full text-left border-collapse border border-[#697076]">
            <thead class="bg-[#2f2c2c] text-white">
            <tr>
                <th @click="sortKey = 'name'; sortOrder = sortOrder === 'asc' ? 'desc' : 'asc'" class="p-4 cursor-pointer border border-[#697076]">
                    Nome
                    <span v-if="sortKey === 'name'" class="ml-2">{{ sortOrder === 'asc' ? '↑' : '↓' }}</span>
                </th>
                <th @click="sortKey = 'email'; sortOrder = sortOrder === 'asc' ? 'desc' : 'asc'" class="p-4 cursor-pointer border border-[#697076]">
                    E-mail
                    <span v-if="sortKey === 'email'" class="ml-2">{{ sortOrder === 'asc' ? '↑' : '↓' }}</span>
                </th>
                <th @click="sortKey = 'status'; sortOrder = sortOrder === 'asc' ? 'desc' : 'asc'" class="p-4 cursor-pointer border border-[#697076]">
                    Status
                    <span v-if="sortKey === 'status'" class="ml-2">{{ sortOrder === 'asc' ? '↑' : '↓' }}</span>
                </th>
                <th class="p-4 border border-[#697076] text-center">Ações</th>
            </tr>
            </thead>
            <tbody>
            <tr
                v-for="user in filteredUsers"
                :key="user.id"
                class="odd:bg-[#f9a01b] even:bg-[#fff] hover:bg-[#ffd89e] transition-colors"
            >
                <td class="p-4 border border-[#697076]">{{ user.name }}</td>
                <td class="p-4 border border-[#697076]">{{ user.email }}</td>
                <td class="p-4 border border-[#697076]">{{ user.status }}</td>
                <td class="p-4 border border-[#697076] text-center">
                    <button
                        @click="editUser(user)"
                        class="bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-800 transition-colors mr-2"
                    >
                        Editar
                    </button>
                    <button
                        @click="toggleStatus(user)"
                        class="bg-green-600 text-white px-3 py-2 rounded hover:bg-green-800 transition-colors mr-2"
                    >
                        {{ user.status === "Ativo" ? "Inativar" : "Ativar" }}
                    </button>
                    <button
                        @click="viewTasks(user)"
                        class="bg-yellow-600 text-white px-3 py-2 rounded hover:bg-yellow-800 transition-colors"
                    >
                        Tarefas
                    </button>
                </td>
            </tr>
            </tbody>
        </table>

        <div
            v-if="showModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                <h2 class="text-xl font-bold mb-4">{{ currentUser.id ? "Editar" : "Novo" }} Usuário</h2>
                <form @submit.prevent="saveUser">
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-[#2f2c2c]">Nome</label>
                        <input
                            v-model="currentUser.name"
                            id="name"
                            type="text"
                            class="w-full p-3 border border-[#697076] rounded-lg"
                            required
                        />
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-[#2f2c2c]">E-mail</label>
                        <input
                            v-model="currentUser.email"
                            id="email"
                            type="email"
                            class="w-full p-3 border border-[#697076] rounded-lg"
                            required
                        />
                    </div>
                    <div class="flex justify-between gap-4">
                        <button
                            @click="closeModal"
                            type="button"
                            class="bg-[#f9a01b] text-white px-6 py-3 rounded-lg hover:bg-[#e68a00] transition-colors"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-800 transition-colors"
                        >
                            Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>
