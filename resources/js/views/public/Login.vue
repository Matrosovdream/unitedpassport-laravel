<template>
    <section class="min-h-[calc(100vh-64px)] flex items-center justify-center bg-gradient-to-br from-white to-section-gray py-12">
        <div class="w-full max-w-md bg-white rounded-card shadow-card p-8">
            <h1 class="text-2xl font-bold text-navy text-center">Welcome back</h1>
            <p class="mt-2 text-sm text-navy-light text-center">Sign in to your account</p>

            <div v-if="errorMessage" class="mt-4 bg-red-50 border border-red-200 text-red-600 rounded-lg p-3 text-sm">
                {{ errorMessage }}
            </div>

            <form @submit.prevent="login" class="mt-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        required
                        class="w-full rounded-lg border border-border-gray text-navy px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-teal/50 focus:border-brand-teal"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Password</label>
                    <input
                        v-model="form.password"
                        type="password"
                        required
                        class="w-full rounded-lg border border-border-gray text-navy px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-teal/50 focus:border-brand-teal"
                    />
                </div>

                <button
                    type="submit"
                    :disabled="loading"
                    class="w-full py-3 rounded-full bg-gradient-to-r from-brand-green to-brand-teal text-white font-semibold hover:opacity-90 transition disabled:opacity-50"
                >
                    {{ loading ? 'Signing in...' : 'Sign in' }}
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-navy-light">
                Don't have an account?
                <router-link to="/register" class="text-brand-teal font-medium hover:underline">Register</router-link>
            </p>
        </div>
    </section>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { clearUserCache } from '../../router';

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
        await window.axios.get('/sanctum/csrf-cookie', { baseURL: '/' });
        await window.axios.post('/login', form);
        clearUserCache();
        router.push({ name: 'dashboard' });
    } catch (error) {
        errorMessage.value = error.response?.data?.message || 'Login failed.';
    } finally {
        loading.value = false;
    }
}
</script>
