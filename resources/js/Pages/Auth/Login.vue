<script setup lang="ts">
import { ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
const email = ref('');
const password = ref('');
const errors = ref<string[]>([]);

const { props } = usePage();
const successMessage = props.message;

const handleLogin = async () => {
    errors.value = [];

    await router.post('/login', {
        email: email.value,
        password: password.value,
    }, {
        onError: (err) => {
            const errorHandlers = {
                email: (message: string) => errors.value.push(message),
                password: (message: string) => errors.value.push(message),
                others: (message: string) => errors.value.push(message),
            };

            Object.entries(err).forEach(([key, message]) => {
                if (errorHandlers[key as keyof typeof errorHandlers]) {
                    errorHandlers[key as keyof typeof errorHandlers](message as string);
                } else {
                    errors.value.push('Erro desconhecido.');
                }
            });
        },
    });
};

const goToRegister = () => {
    router.get('/register');
};
</script>

<template>
    <main class="w-full h-[100vh] flex items-center justify-center bg-[#2f2c2c]">
        <section class="bg-white p-8 rounded-md shadow-md w-full max-w-md">
            <h1 class="text-2xl font-bold text-center mb-4 text-[#2f2c2c]">Bem-vindo!</h1>
            <p class="text-center text-[#2f2c2c] mb-6">Faça login para acessar sua conta</p>

            <div v-if="successMessage" class="bg-[#f9a01b] text-white p-3 rounded mb-4">
                {{ successMessage }}
            </div>

            <form @submit.prevent="handleLogin" class="space-y-4">
                <ul v-if="errors.length" class="bg-[#f9a01b] text-white p-3 rounded">
                    <li v-for="error in errors" :key="error">{{ error }}</li>
                </ul>

                <div>
                    <label for="email" class="block text-[#2f2c2c] mb-1">E-mail</label>
                    <input
                        id="email"
                        type="email"
                        v-model="email"
                        placeholder="Digite seu e-mail"
                        class="w-full px-4 py-2 border border-[#2f2c2c] rounded focus:outline-none focus:ring-2 focus:ring-[#f9a01b]"
                        required
                    />
                </div>

                <div>
                    <label for="password" class="block text-[#2f2c2c] mb-1">Senha</label>
                    <input
                        id="password"
                        type="password"
                        v-model="password"
                        placeholder="Digite sua senha"
                        class="w-full px-4 py-2 border border-[#2f2c2c] rounded focus:outline-none focus:ring-2 focus:ring-[#f9a01b]"
                        required
                    />
                </div>

                <button
                    type="submit"
                    class="w-full bg-[#2f2c2c] text-white py-2 px-4 rounded hover:bg-[#f9a01b] transition"
                >
                    Entrar
                </button>
            </form>

            <div class="mt-4 text-center">
                <p class="text-[#2f2c2c]">
                    Não tem uma conta?
                    <button
                        @click="goToRegister"
                        class="text-[#f9a01b] hover:underline"
                    >
                        Registre-se
                    </button>
                </p>
            </div>
        </section>
    </main>
</template>

<style scoped>
/* Custom transitions */
button {
    transition: background-color 0.3s ease, color 0.3s ease;
}
</style>
