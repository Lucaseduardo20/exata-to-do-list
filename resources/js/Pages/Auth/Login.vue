<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const email = ref<string>('');
const password = ref<string>('');
const errors = ref<string[]>([]);

const handleLogin = async () => {
    errors.value = [];

    await router.post('/login', {
        email: email.value,
        password: password.value,
    }, {
        onError: (err) => {
            if (err.email) errors.value.push(err.email[0]);
            if (err.password) errors.value.push(err.password[0]);
        },
    });
};

const goToRegister = () => {
    router.get('/register');
};
</script>

<template>
    <main class="w-full h-[100vh] flex items-center justify-center bg-gray-100">
        <section class="bg-white p-8 rounded-md shadow-md w-full max-w-md">
            <h1 class="text-2xl font-bold text-center mb-4 text-gray-800">Bem-vindo!</h1>
            <p class="text-center text-gray-600 mb-6">Faça login para acessar sua conta</p>

            <form @submit.prevent="handleLogin" class="space-y-4">
                <ul v-if="errors.length" class="bg-red-100 text-red-700 p-3 rounded">
                    <li v-for="error in errors" :key="error">{{ error }}</li>
                </ul>

                <div>
                    <label for="email" class="block text-gray-700 mb-1">E-mail</label>
                    <input
                        id="email"
                        type="email"
                        v-model="email"
                        placeholder="Digite seu e-mail"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    />
                </div>

                <div>
                    <label for="password" class="block text-gray-700 mb-1">Senha</label>
                    <input
                        id="password"
                        type="password"
                        v-model="password"
                        placeholder="Digite sua senha"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    />
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition"
                >
                    Entrar
                </button>
            </form>

            <div class="mt-4 text-center">
                <p class="text-gray-600">
                    Não tem uma conta?
                    <button
                        @click="goToRegister"
                        class="text-blue-500 hover:underline"
                    >
                        Registre-se
                    </button>
                </p>
            </div>
        </section>
    </main>
</template>

<style scoped>
</style>
