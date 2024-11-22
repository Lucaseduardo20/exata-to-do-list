<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const name = ref<string>('');
const email = ref<string>('');
const password = ref<string>('');
const confirmPassword = ref<string>('');
const errors = ref<Record<string, string[]>>({});
const successMessage = ref<string>('');

const handleRegister = async () => {
    if(password.value !== confirmPassword.value) {
        errors.value = {'confirm': ['As senhas não conferem.']} as Record<string, string[]>
        return;
    }
    errors.value = {};

    await router.post('/user/register', {
        name: name.value,
        email: email.value,
        password: password.value,
        password_confirmation: confirmPassword.value,
    }, {
        onError: (err: Record<string, string[]>) => {
            errors.value = err;
        },
        onSuccess: () => {
            successMessage.value = 'Usuário criado com sucesso!';
        },
    });
};

const goToLogin = () => {
    router.get('/');
};
</script>

<template>
    <main class="w-full h-[100vh] flex items-center justify-center bg-[#2f2c2c]">
        <section class="bg-white p-8 rounded-md shadow-md w-full max-w-md">
            <h1 class="text-2xl font-bold text-center mb-4 text-[#2f2c2c]">Crie sua conta</h1>
            <p class="text-center text-[#2f2c2c]/80 mb-6">Registre-se para começar!</p>

            <div v-if="successMessage" class="bg-[#f9a01b]/20 text-[#2f2c2c] p-3 rounded mb-4">
                {{ successMessage }}
            </div>

            <form @submit.prevent="handleRegister" class="space-y-4">
                <ul v-if="Object.keys(errors).length" class="bg-[#f9a01b]/20 text-[#2f2c2c] p-3 rounded">
                    <li v-for="(fieldErrors, field) in errors" :key="field">
                        <span v-for="error in fieldErrors" :key="error">{{ error }}</span>
                    </li>
                </ul>

                <div>
                    <label for="name" class="block text-[#2f2c2c] mb-1">Nome</label>
                    <input
                        id="name"
                        type="text"
                        v-model="name"
                        placeholder="Digite seu nome completo"
                        class="w-full px-4 py-2 border border-[#2f2c2c]/30 rounded focus:outline-none focus:ring-2 focus:ring-[#f9a01b]"
                        required
                    />
                </div>

                <div>
                    <label for="email" class="block text-[#2f2c2c] mb-1">E-mail</label>
                    <input
                        id="email"
                        type="email"
                        v-model="email"
                        placeholder="Digite seu e-mail"
                        class="w-full px-4 py-2 border border-[#2f2c2c]/30 rounded focus:outline-none focus:ring-2 focus:ring-[#f9a01b]"
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
                        class="w-full px-4 py-2 border border-[#2f2c2c]/30 rounded focus:outline-none focus:ring-2 focus:ring-[#f9a01b]"
                        required
                    />
                </div>

                <div>
                    <label for="confirmPassword" class="block text-[#2f2c2c] mb-1">Confirme sua Senha</label>
                    <input
                        id="confirmPassword"
                        type="password"
                        v-model="confirmPassword"
                        placeholder="Confirme sua senha"
                        class="w-full px-4 py-2 border border-[#2f2c2c]/30 rounded focus:outline-none focus:ring-2 focus:ring-[#f9a01b]"
                        required
                    />
                </div>

                <button
                    type="submit"
                    class="w-full bg-[#f9a01b] text-white py-2 px-4 rounded hover:bg-[#f9a01b]/80 transition"
                >
                    Registrar
                </button>
            </form>

            <div class="mt-4 text-center">
                <p class="text-[#2f2c2c]/80">
                    Já possui uma conta?
                    <button
                        @click="goToLogin"
                        class="text-[#f9a01b] hover:underline"
                    >
                        Faça login
                    </button>
                </p>
            </div>
        </section>
    </main>
</template>

<style scoped>
</style>

