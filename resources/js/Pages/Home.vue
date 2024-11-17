<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3'
import UsersTab from "@/Layouts/UsersTab.vue";
import TasksTab from "@/Layouts/TasksTab.vue";
import ProfileTab from "@/Layouts/ProfileTab.vue";

const user = usePage().props.auth.user;
const activeTab = ref('profile');

function setActiveTab(tab) {
    activeTab.value = tab;
}
</script>

<template>
    <div class="flex h-screen bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-100">
        <aside
            class="w-64 bg-[#2f2c2c] text-white dark:bg-blue-800 flex flex-col justify-between"
        >
            <nav class="flex-grow">
                <div class="px-6 py-4 text-xl font-bold">
                    <img src="exata-logo.png">
                </div>

                <ul>
                    <li>
                        <button
                            @click="setActiveTab('profile')"
                            class="w-full text-white text-left px-6 py-3 hover:bg-[#f9a01b] focus:bg-[#f9a01b]"
                            :class="{ 'bg-[#f9a01b]': activeTab === 'profile' }"
                        >
                            Perfil
                        </button>
                    </li>
                    <li>
                        <button
                            @click="setActiveTab('tasks')"
                            class="w-full text-white text-left px-6 py-3 hover:bg-[#f9a01b] focus:bg-[#f9a01b]"
                            :class="{ 'bg-[#f9a01b]': activeTab === 'tasks' }"
                        >
                            Tarefas
                        </button>
                    </li>
                    <li v-if="user.role === 'admin'"><button
                            @click="setActiveTab('users')"
                            class="w-full text-white text-left px-6 py-3 hover:bg-[#f9a01b] focus:bg-[#f9a01b]"
                            :class="{ 'bg-[#f9a01b]': activeTab === 'users' }"
                        >
                            Usuários
                        </button>
                    </li>
                </ul>
            </nav>
            <footer class="px-6 text-[#697076] py-4 text-sm">
                &copy; {{ new Date().getFullYear() }} Exata Tech - To Do List
            </footer>
        </aside>

        <main class="flex-grow bg-[#f9dfb8] p-6 flex justify-center items-center">
            <profile-tab v-if="activeTab === 'profile'" class="animate-fade-in"></profile-tab>
            <tasks-tab v-if="activeTab === 'tasks'" class="animate-fade-in"></tasks-tab>

            <users-tab v-if="activeTab === 'users' && user.role === 'admin'" class="animate-fade-in"></users-tab>
        </main>
    </div>
</template>

<style scoped>
.animate-fade-in {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}
</style>
