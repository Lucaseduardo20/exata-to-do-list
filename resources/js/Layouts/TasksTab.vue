<script setup>
import { ref, computed } from "vue";
import {router} from "@inertiajs/vue3";

const tasks = ref([
    { id: 1, title: "Estudar Vue.js", description: "Aprender Composition API", status: "Pendente" },
    { id: 2, title: "Criar backend", description: "Configurar API Laravel", status: "Concluída" },
    { id: 3, title: "Ajustar front-end", description: "Fazer a responsividade do layout", status: "Pendente" },
]);

const showModal = ref(false);
const newTask = ref({ title: "", description: "", status: "Pendente" });
const filter = ref({ title: "", description: "", status: "" });
const sortKey = ref("title");
const sortOrder = ref("asc");

const openModal = () => {
    newTask.value = { title: "", description: "", status: "Pendente" };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const saveTask = () => {
    if (newTask.value.title.trim() && newTask.value.description.trim()) {
        router.post("/tasks", newTask.value, {
            onSuccess: () => {
                tasks.value.push({ ...newTask.value, id: tasks.value.length + 1 });
                closeModal();
            },
        });
    }
};

const editTask = (task) => {
    newTask.value = { ...task };
    showModal.value = true;
};

const deleteTask = (id) => {
    if (confirm("Tem certeza que deseja remover esta tarefa?")) {
        router.delete(`/tasks/${id}`, {
            onSuccess: () => {
                tasks.value = tasks.value.filter((task) => task.id !== id);
            },
        });
    }
};

const toggleStatus = (task) => {
    const newStatus = task.status === "Pendente" ? "Concluída" : "Pendente";
    router.put(`/tasks/${task.id}`, { ...task, status: newStatus }, {
        onSuccess: () => {
            task.status = newStatus;
        },
    });
};

const filteredTasks = computed(() => {
    return tasks.value
        .filter((task) => {
            return (
                (filter.value.title ? task.title.toLowerCase().includes(filter.value.title.toLowerCase()) : true) &&
                (filter.value.description ? task.description.toLowerCase().includes(filter.value.description.toLowerCase()) : true) &&
                (filter.value.status ? task.status.toLowerCase().includes(filter.value.status.toLowerCase()) : true)
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
            <h1 class="text-3xl font-bold text-[#2f2c2c]">Gerenciador de Tarefas</h1>
            <button
                @click="openModal"
                class="bg-[#f9a01b] text-white px-6 py-3 rounded-lg hover:bg-[#e68a00] transition-colors"
            >
                Nova Tarefa
            </button>
        </div>

        <div class="mb-4 flex gap-4">
            <input
                v-model="filter.title"
                type="text"
                placeholder="Filtrar por título"
                class="w-full p-3 border border-[#697076] rounded-lg"
            />
            <input
                v-model="filter.description"
                type="text"
                placeholder="Filtrar por descrição"
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
                <th @click="sortKey = 'title'; sortOrder = sortOrder === 'asc' ? 'desc' : 'asc'" class="p-4 cursor-pointer border border-[#697076]">
                    Título
                    <span v-if="sortKey === 'title'" class="ml-2">{{ sortOrder === 'asc' ? '↑' : '↓' }}</span>
                </th>
                <th @click="sortKey = 'description'; sortOrder = sortOrder === 'asc' ? 'desc' : 'asc'" class="p-4 cursor-pointer border border-[#697076]">
                    Descrição
                    <span v-if="sortKey === 'description'" class="ml-2">{{ sortOrder === 'asc' ? '↑' : '↓' }}</span>
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
                v-for="task in filteredTasks"
                :key="task.id"
                class="odd:bg-[#f9a01b] even:bg-[#fff] hover:bg-[#ffd89e] transition-colors"
            >
                <td class="p-4 border border-[#697076]">{{ task.title }}</td>
                <td class="p-4 border border-[#697076]">{{ task.description }}</td>
                <td class="p-4 border border-[#697076]">{{ task.status }}</td>
                <td class="p-4 border border-[#697076] text-center">
                    <button
                        @click="editTask(task)"
                        class="bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-800 transition-colors mr-2"
                    >
                        Editar
                    </button>
                    <button
                        @click="toggleStatus(task)"
                        class="bg-green-600 text-white px-3 py-2 rounded hover:bg-green-800 transition-colors mr-2"
                    >
                        {{ task.status === "Pendente" ? "Concluir" : "Reabrir" }}
                    </button>
                    <button
                        @click="deleteTask(task.id)"
                        class="bg-red-600 text-white px-3 py-2 rounded hover:bg-red-800 transition-colors"
                    >
                        Remover
                    </button>
                </td>
            </tr>
            </tbody>
        </table>

        <div
            v-if="showModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
        >
            <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                <h2 class="text-2xl font-bold mb-4">
                    {{ newTask.id ? "Editar Tarefa" : "Nova Tarefa" }}
                </h2>
                <form @submit.prevent="saveTask">
                    <div class="mb-4">
                        <label class="block text-lg font-medium text-[#2f2c2c]">Título</label>
                        <input
                            v-model="newTask.title"
                            type="text"
                            class="w-full p-3 border border-[#697076] rounded-lg focus:outline-none"
                        />
                    </div>
                    <div class="mb-4">
                        <label class="block text-lg font-medium text-[#2f2c2c]">Descrição</label>
                        <textarea
                            v-model="newTask.description"
                            class="w-full p-3 border border-[#697076] rounded-lg focus:outline-none"
                        ></textarea>
                    </div>
                    <div class="flex justify-end gap-4">
                        <button
                            type="button"
                            @click="closeModal"
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

