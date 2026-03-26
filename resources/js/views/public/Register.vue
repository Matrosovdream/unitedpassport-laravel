<template>
    <section class="min-h-[calc(100vh-64px)] flex items-center justify-center bg-gradient-to-br from-white to-section-gray py-12">
        <div class="w-full max-w-md bg-white rounded-card shadow-card p-8">
            <h1 class="text-2xl font-bold text-navy text-center">Create your account</h1>
            <p class="mt-2 text-sm text-navy-light text-center">Start your journey with UnitedPassport</p>

            <div v-if="errorMessage" class="mt-4 bg-red-50 border border-red-200 text-red-600 rounded-lg p-3 text-sm">
                {{ errorMessage }}
            </div>

            <div v-if="Object.keys(errors).length" class="mt-4 bg-red-50 border border-red-200 text-red-600 rounded-lg p-3 text-sm">
                <ul class="list-disc list-inside">
                    <li v-for="(msgs, field) in errors" :key="field">{{ msgs[0] }}</li>
                </ul>
            </div>

            <form @submit.prevent="register" class="mt-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Name</label>
                    <input
                        v-model="form.name"
                        type="text"
                        required
                        class="w-full rounded-lg border border-border-gray text-navy px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-brand-teal/50 focus:border-brand-teal"
                    />
                </div>

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

                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Confirm Password</label>
                    <input
                        v-model="form.password_confirmation"
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
                    {{ loading ? 'Creating account...' : 'Create account' }}
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-navy-light">
                Already have an account?
                <router-link to="/login" class="text-brand-teal font-medium hover:underline">Sign in</router-link>
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
        await window.axios.get('/sanctum/csrf-cookie', { baseURL: '/' });
        await window.axios.post('/register', form);
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
