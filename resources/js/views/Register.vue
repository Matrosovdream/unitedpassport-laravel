<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-900">
        <div class="w-full max-w-md bg-gray-800 rounded-lg shadow-lg p-8">
            <h1 class="text-2xl font-bold text-white text-center mb-6">Create Account</h1>

            <div v-if="errorMessage" class="bg-red-500/10 border border-red-500 text-red-400 rounded p-3 mb-4 text-sm">
                {{ errorMessage }}
            </div>

            <div v-if="Object.keys(errors).length" class="bg-red-500/10 border border-red-500 text-red-400 rounded p-3 mb-4 text-sm">
                <ul class="list-disc list-inside">
                    <li v-for="(msgs, field) in errors" :key="field">{{ msgs[0] }}</li>
                </ul>
            </div>

            <form @submit.prevent="register" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Name</label>
                    <input
                        v-model="form.name"
                        type="text"
                        required
                        class="w-full rounded bg-gray-700 border border-gray-600 text-white px-3 py-2 focus:outline-none focus:border-blue-500"
                    />
                </div>

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

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Confirm Password</label>
                    <input
                        v-model="form.password_confirmation"
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
                    {{ loading ? 'Creating account...' : 'Register' }}
                </button>
            </form>

            <p class="mt-4 text-center text-sm text-gray-400">
                Already have an account?
                <router-link to="/login" class="text-blue-400 hover:text-blue-300">Sign in</router-link>
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
const errors = ref({});

const form = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

async function register() {
    loading.value = true;
    errorMessage.value = '';
    errors.value = {};

    try {
        await window.axios.get('/sanctum/csrf-cookie');
        await window.axios.post('/api/register', form);
        clearUserCache();
        router.push({ name: 'dashboard' });
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors || {};
        } else {
            errorMessage.value = error.response?.data?.message || 'Registration failed.';
        }
    } finally {
        loading.value = false;
    }
}
</script>
