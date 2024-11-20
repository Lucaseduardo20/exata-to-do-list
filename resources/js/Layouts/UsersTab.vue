<script setup>
import { ref, computed, onMounted } from "vue";
import {router} from "@inertiajs/vue3";
import {deleteUserService, editUserService, getUsersService, promoteUserService} from "../../services/admin.ts";

const users = ref([]);

const showModal = ref(false);
const currentUser = ref(null);
const filter = ref({ name: "", email: "", status: "" });
const sortKey = ref("name");
const sortOrder = ref("asc");

const getUsers = async () => {
    await getUsersService().then((res) => {
        users.value = res.data.users
    })
}

onMounted(async () => {
    await getUsers()
})

const closeModal = () => {
    showModal.value = false;
};

const saveUser = async () => {
    await editUserService({
        id: currentUser.value.id,
        name: currentUser.value.name,
        email: currentUser.value.email
    }).then((res) => {
        alert(res.data.message)
    })
    closeModal();
    await getUsers();
};

const editUser = (user) => {
    currentUser.value = { ...user };
    showModal.value = true;
};

const deleteUser = async (user) => {
    if (confirm("Tem certeza que deseja deletar este usuário?")) {
        await deleteUserService(user.id).then((res) => {
            alert(res.data.message);
        })
    }
    await getUsers();

};

const promoteUser = async (user) => {
    if (confirm("Tem certeza que deseja promover este usuário à Administrador?")) {
        await promoteUserService(user.id).then((res) => {
            alert(res.data.message);
        })
    }

    await getUsers();
}

const filteredUsers = computed(() => {
    return users.value
        .filter((user) => {
            return (
                (filter.value.name ? user.name.toLowerCase().includes(filter.value.name.toLowerCase()) : true) &&
                (filter.value.email ? user.email.toLowerCase().includes(filter.value.email.toLowerCase()) : true)
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
                <th  class="p-4 cursor-pointer border border-[#697076]">
                    Role
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
                <td class="p-4 border border-[#697076]">{{ user.f_role }}</td>
                <td class="p-4 border border-[#697076] text-center flex justify-center gap-1">
                    <button
                        @click="editUser(user)"
                        class="bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-800 transition-colors"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>

                    </button>

                    <button
                        @click="deleteUser(user)"
                        class="bg-red-600 text-white px-3 py-2 rounded hover:bg-red-800 transition-colors"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>

                    </button>
                    <button v-if="user.role === 'user'"
                        @click="promoteUser(user)"
                        class="bg-green-600 text-white  px-3 py-2 rounded hover:bg-green-800 transition-colors"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 18.75 7.5-7.5 7.5 7.5" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 7.5-7.5 7.5 7.5" />
                        </svg>

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
