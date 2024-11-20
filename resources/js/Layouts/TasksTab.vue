<script setup lang="ts">
import { ref, computed, defineProps, onMounted } from "vue";
import {router, usePage} from "@inertiajs/vue3";
import {Tasks} from "../types/tasks";
import {getTasks, createTaskService, editTaskService} from "../../services/tasks";

const props = defineProps({
    tasks: {
        type: Array<Tasks>
    }
})

const tasks = ref([]);

const showModal = ref(false);
const newTask = ref({ title: "", description: ""});
const isEditing = ref<boolean>(false);
const editingTask = ref({id: null, title: "", description: ""})
const filter = ref({ title: "", description: "", status: "" });
const sortKey = ref("title");
const sortOrder = ref("asc");

const openModal = () => {
    newTask.value = { title: "", description: "" };
    showModal.value = true;
};

onMounted(async () => {
    const response = await getTasks(usePage().props.auth.user.id)
    tasks.value = response.data
})

const closeModal = () => {
    editingTask.value = {id: null, title: "", description: ""};
    newTask.value = { title: "", description: ""};
    isEditing.value = false;
    showModal.value = false;
};

const createTask = async () => {
    if (!newTask.value.title.trim() || !newTask.value.description.trim()) {
        return alert('Preencha os campos corretamente!')
    }
    await createTaskService({title: newTask.value.title, description: newTask.value.description}).then((res) => {
        closeModal();
    })
    tasks.value = (await getTasks(usePage().props.auth.user.id)).data
};

const updateTask = async () => {
    if (!editingTask.value.title.trim() || !editingTask.value.description.trim()) {
        return alert('Preencha os campos corretamente!')
    }
    await editTaskService({id: editingTask.value.id, title: editingTask.value.title, description:editingTask.value.description}).then((res) => {
        closeModal();
    })
    tasks.value = (await getTasks(usePage().props.auth.user.id)).data
}

const submitModal = async () => {
    return isEditing.value ? await updateTask() : await createTask();
}

const edit = (task) => {
    isEditing.value = true;
    editingTask.value = { ...task };
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
    <section class="p-6 max-h-[90%]">
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
            <select
                v-model="filter.status"
                class="w-full p-3 border border-[#697076] rounded-lg"
            >
                <option value="">Filtrar por status</option>
                <option value="done">Em Andamento</option>
                <option value="in-progress">Concluída</option>
                <option value="pending">Pendente</option>
            </select>
        </div>

        <div class="overflow-y-auto max-h-[550px]">

            <table class="w-full text-left border-collapse border border-[#697076] max-w-6xl max-h-[90%] overflow-auto">
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
                    <th class="p-4 border border-[#697076] text-center h-auto">
                        Ações
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="tasks.length === 0" class="bg-[#f9a01b] ">
                    <td colspan="6" align="center">
                        Carregando...
                    </td>
                </tr>
                <tr
                    v-for="task in filteredTasks"
                    :key="task.id"
                    class="odd:bg-[#f9a01b] even:bg-[#fff] hover:bg-[#ffd89e] transition-colors"
                >
                    <td class="p-4 border border-[#697076]">{{ task.title }}</td>
                    <td class="p-4 border border-[#697076]">{{ task.description }}</td>
                    <td class="p-4 border border-[#697076]">{{ task.status }}</td>
                    <td class="p-4 border border-[#697076]">
                        <div class="flex items-center h-full">

                            <button
                                @click="edit(task)"
                                class="bg-blue-600 text-white px-1 py-1 rounded hover:bg-blue-800 transition-colors mr-2"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>

                            </button>
                            <button
                                @click="toggleStatus(task)"
                                class="bg-green-600 text-white px-1 py-1 rounded hover:bg-green-800 transition-colors mr-2"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                            </button>
                            <button
                                @click="deleteTask(task.id)"
                                class="bg-red-600 text-white px-1 py-1 rounded hover:bg-red-800 transition-colors"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m6 4.125 2.25 2.25m0 0 2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                </svg>

                            </button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div
            v-if="showModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
        >
            <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                <h2 class="text-2xl font-bold mb-4">
                    {{ isEditing ? "Editar Tarefa" : "Nova Tarefa" }}
                </h2>
                <form @submit.prevent="submitModal">
                    <div v-if="isEditing">
                        <div class="mb-4">
                            <label class="block text-lg font-medium text-[#2f2c2c]">Título</label>
                            <input
                                v-model="editingTask.title"
                                type="text"
                                class="w-full p-3 border border-[#697076] rounded-lg focus:outline-none"
                            />
                        </div>
                        <div class="mb-4">
                            <label class="block text-lg font-medium text-[#2f2c2c]">Descrição</label>
                            <textarea
                                v-model="editingTask.description"
                                class="w-full p-3 border border-[#697076] rounded-lg focus:outline-none"
                            ></textarea>
                        </div>
                    </div>
                    <div v-else>
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
                            @click="saveTask"
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

