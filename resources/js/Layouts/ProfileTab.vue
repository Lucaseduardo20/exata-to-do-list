<script setup>
import { usePage } from "@inertiajs/vue3";

const user = usePage().props.auth.user;

function handleEditProfile() {
    // @todo
}

function handleLogout() {
    // @todo
}
</script>

<template>
    <section class="profile-container bg-[#2f2c2c] p-6 rounded-lg shadow-md">
        <header class="mb-4">
            <h1 class="text-2xl font-bold text-white">Bem-vindo, {{ user.name }}!</h1>
            <p class="text-white">Aqui estão suas informações de usuário:</p>
        </header>

        <div class="user-info mb-4">
            <p class="text-lg text-white"><strong>E-mail:</strong> {{ user.email }}</p>
            <p class="text-lg text-white"><strong>Função:</strong> {{ user.role }}</p>
        </div>

        <footer class="actions flex gap-4">
            <button
                @click="handleEditProfile"
                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
            >
                Editar Perfil
            </button>
            <button
                @click="handleLogout"
                class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
            >
                Sair
            </button>
        </footer>
    </section>
</template>

<style scoped>
.profile-container {
    max-width: 400px;
    margin: 0 auto;
}

.user-info p {
    margin-bottom: 0.5rem;
}

.actions button {
    transition: background-color 0.2s ease-in-out;
}
</style>
