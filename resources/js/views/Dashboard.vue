<template>
    <div class="min-h-screen bg-gray-900">
        <nav class="bg-gray-800 border-b border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <span class="text-white font-bold text-lg">United Passport</span>

                    <div class="flex items-center gap-4">
                        <span class="text-gray-300 text-sm">{{ user?.name }}</span>
                        <button
                            @click="logout"
                            class="text-sm text-red-400 hover:text-red-300 transition"
                        >
                            Logout
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-white mb-2">Dashboard</h1>
            <p class="text-gray-400">Welcome back, {{ user?.name }}.</p>
        </main>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { clearUserCache } from '../router';

const router = useRouter();
const user = ref(null);

onMounted(async () => {
    try {
        const { data } = await window.axios.get('/api/user');
        user.value = data.user;
    } catch {
        router.push({ name: 'login' });
    }
});

async function logout() {
    await window.axios.post('/api/logout');
    clearUserCache();
    router.push({ name: 'login' });
}
</script>
