<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-900">
        <div class="w-full max-w-md bg-gray-800 rounded-lg shadow-lg p-8">
            <h1 class="text-2xl font-bold text-white text-center mb-6">United Passport</h1>

            <div v-if="errorMessage" class="bg-red-500/10 border border-red-500 text-red-400 rounded p-3 mb-4 text-sm">
                {{ errorMessage }}
            </div>

            <form @submit.prevent="login" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        required
                        class="w-full rounded bg-gray-700 border border-gray-600 text-white px-3 py-2 focus:outline-none focus:border-blue-500"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Password</label>
                    <input
                        v-model="form.password"
                        type="password"
                        required
                        class="w-full rounded bg-gray-700 border border-gray-600 text-white px-3 py-2 focus:outline-none focus:border-blue-500"
                    />
                </div>

                <button
                    type="submit"
                    :disabled="loading"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition disabled:opacity-50"
                >
                    {{ loading ? 'Signing in...' : 'Sign in' }}
                </button>
            </form>

            <p class="mt-4 text-center text-sm text-gray-400">
                Don't have an account?
                <router-link to="/register" class="text-blue-400 hover:text-blue-300">Register</router-link>
            </p>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { clearUserCache } from '../router';

const router = useRouter();
const loading = ref(false);
const errorMessage = ref('');

const form = reactive({
    email: '',
    password: '',
});

async function login() {
    loading.value = true;
    errorMessage.value = '';

    try {
        await window.axios.get('/sanctum/csrf-cookie');
        await window.axios.post('/api/login', form);
        clearUserCache();
        router.push({ name: 'dashboard' });
    } catch (error) {
        errorMessage.value = error.response?.data?.message || 'Login failed.';
    } finally {
        loading.value = false;
    }
}
</script>
