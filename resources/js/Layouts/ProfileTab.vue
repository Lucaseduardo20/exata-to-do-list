<script setup>
import {usePage} from "@inertiajs/vue3";

const user = usePage().props.auth.user;

</script>

<template>
    <section>
        <h1 class="text-2xl font-bold mb-4">Perfil</h1>
        <p>Bem-vindo, {{ user.name }}!</p>
    </section>
</template>

<style scoped>

</style>
