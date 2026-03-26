<template>
    <section class="bg-section-gray min-h-screen">
        <!-- Loading -->
        <div v-if="loading" class="max-w-container mx-auto px-6 md:px-12 xl:px-[120px] py-20 text-center">
            <div class="w-12 h-12 mx-auto border-4 border-brand-teal/30 border-t-brand-teal rounded-full animate-spin"></div>
            <p class="mt-4 text-navy-light">Loading form...</p>
        </div>

        <!-- Error -->
        <div v-else-if="loadError" class="max-w-container mx-auto px-6 md:px-12 xl:px-[120px] py-20 text-center">
            <h2 class="text-2xl font-bold text-navy">Form not found</h2>
            <p class="mt-2 text-navy-light">{{ loadError }}</p>
            <button @click="$router.push('/')" class="mt-6 inline-flex items-center px-8 py-3 rounded-full text-white font-semibold bg-gradient-to-r from-brand-green to-brand-teal">
                Back to Home
            </button>
        </div>

        <!-- Form loaded -->
        <div v-else class="max-w-container mx-auto px-6 md:px-12 xl:px-[120px] py-12">
            <!-- Progress -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-3">
                    <h1 class="text-2xl md:text-3xl font-bold text-navy">{{ formDef.name }}</h1>
                    <span class="text-sm text-navy-light font-medium">Step {{ currentStep }} of {{ totalSteps }} &mdash; {{ Math.round((currentStep / totalSteps) * 100) }}% Complete</span>
                </div>
                <div class="w-full bg-white rounded-full h-2 shadow-sm">
                    <div
                        class="h-2 rounded-full bg-gradient-to-r from-brand-green to-brand-teal transition-all duration-500"
                        :style="{ width: ((currentStep / totalSteps) * 100) + '%' }"
                    ></div>
                </div>
                <div class="flex justify-between mt-3">
                    <button
                        v-for="page in pageNumbers"
                        :key="page"
                        @click="jumpToStep(page)"
                        class="text-xs font-medium transition-colors hidden md:block"
                        :class="page === currentStep ? 'text-brand-teal' : page < currentStep ? 'text-navy' : 'text-navy-light/50'"
                    >
                        Page {{ page }}
                    </button>
                    <!-- Review step label -->
                    <button
                        @click="jumpToStep(totalSteps)"
                        class="text-xs font-medium transition-colors hidden md:block"
                        :class="totalSteps === currentStep ? 'text-brand-teal' : totalSteps < currentStep ? 'text-navy' : 'text-navy-light/50'"
                    >
                        Review
                    </button>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-card shadow-card p-8 md:p-12">
                <transition name="fade" mode="out-in">
                    <!-- Dynamic field pages -->
                    <div v-if="currentStep <= pageNumbers.length" :key="'page-' + currentStep">
                        <h2 class="text-xl font-bold text-navy mb-6">{{ pageTitle(currentStep) }}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div
                                v-for="field in fieldsForPage(currentStep)"
                                :key="field.id"
                                :class="isFullWidth(field) ? 'md:col-span-2' : ''"
                            >
                                <label class="block text-sm font-semibold text-navy mb-2">
                                    {{ field.name }}
                                    <span v-if="field.required" class="text-red-500">*</span>
                                </label>
                                <p v-if="field.description" class="text-xs text-navy-light mb-2">{{ field.description }}</p>

                                <!-- Text / Email / Phone / Number / Date / Hidden -->
                                <input
                                    v-if="isInputType(field.type)"
                                    v-model="values[field.field_key]"
                                    :type="mapInputType(field.type)"
                                    :placeholder="getPlaceholder(field)"
                                    class="w-full px-4 py-3 rounded-xl border border-border-gray focus:outline-none focus:ring-2 focus:ring-brand-teal"
                                    :class="{ 'border-red-400': fieldErrors[field.field_key] }"
                                />

                                <!-- Textarea -->
                                <textarea
                                    v-else-if="field.type === 'textarea'"
                                    v-model="values[field.field_key]"
                                    rows="3"
                                    :placeholder="getPlaceholder(field)"
                                    class="w-full px-4 py-3 rounded-xl border border-border-gray focus:outline-none focus:ring-2 focus:ring-brand-teal"
                                    :class="{ 'border-red-400': fieldErrors[field.field_key] }"
                                ></textarea>

                                <!-- Select -->
                                <select
                                    v-else-if="field.type === 'select'"
                                    v-model="values[field.field_key]"
                                    class="w-full px-4 py-3 rounded-xl border border-border-gray focus:outline-none focus:ring-2 focus:ring-brand-teal"
                                    :class="{ 'border-red-400': fieldErrors[field.field_key] }"
                                >
                                    <option value="" disabled>Select</option>
                                    <option v-for="opt in getOptions(field)" :key="opt" :value="opt">{{ opt }}</option>
                                </select>

                                <!-- Checkbox -->
                                <label v-else-if="field.type === 'checkbox'" class="flex items-center gap-3 cursor-pointer">
                                    <input
                                        type="checkbox"
                                        :checked="values[field.field_key] === '1'"
                                        @change="values[field.field_key] = $event.target.checked ? '1' : '0'"
                                        class="w-4 h-4 rounded border-border-gray text-brand-teal focus:ring-brand-teal"
                                    />
                                    <span class="text-sm text-navy-light">{{ field.name }}</span>
                                </label>

                                <!-- Radio -->
                                <div v-else-if="field.type === 'radio'" class="space-y-2">
                                    <label v-for="opt in getOptions(field)" :key="opt" class="flex items-center gap-3 cursor-pointer">
                                        <input
                                            type="radio"
                                            :name="field.field_key"
                                            :value="opt"
                                            v-model="values[field.field_key]"
                                            class="w-4 h-4 border-border-gray text-brand-teal focus:ring-brand-teal"
                                        />
                                        <span class="text-sm text-navy-light">{{ opt }}</span>
                                    </label>
                                </div>

                                <!-- File (placeholder) -->
                                <div v-else-if="field.type === 'file'">
                                    <div
                                        class="border-2 border-dashed border-border-gray rounded-xl p-6 text-center cursor-pointer hover:border-brand-teal transition-colors"
                                        @click="toggleFile(field.field_key)"
                                    >
                                        <div v-if="values[field.field_key] !== '1'" class="text-navy-light">
                                            <svg class="mx-auto w-10 h-10 mb-2 text-navy-light/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="font-medium text-sm">Click to upload</p>
                                        </div>
                                        <div v-else class="text-brand-teal">
                                            <svg class="mx-auto w-10 h-10 mb-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                            <p class="font-medium text-sm">File uploaded</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Fallback: text input -->
                                <input
                                    v-else
                                    v-model="values[field.field_key]"
                                    type="text"
                                    class="w-full px-4 py-3 rounded-xl border border-border-gray focus:outline-none focus:ring-2 focus:ring-brand-teal"
                                />

                                <small v-if="fieldErrors[field.field_key]" class="text-red-500 text-xs mt-1 block">{{ fieldErrors[field.field_key] }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Review step (last step) -->
                    <div v-else-if="currentStep === totalSteps" key="review">
                        <h2 class="text-xl font-bold text-navy mb-6">Review & Submit</h2>

                        <div class="space-y-6">
                            <div v-for="page in pageNumbers" :key="page" class="bg-section-gray rounded-xl p-6">
                                <div class="flex justify-between items-center mb-3">
                                    <h3 class="font-semibold text-navy">{{ pageTitle(page) }}</h3>
                                    <button @click="currentStep = page" class="text-sm text-brand-teal font-medium hover:underline">Edit</button>
                                </div>
                                <div class="space-y-1">
                                    <div v-for="field in fieldsForPage(page)" :key="field.id" class="flex text-sm">
                                        <span class="text-navy-light w-40 flex-shrink-0">{{ field.name }}:</span>
                                        <span class="text-navy font-medium">{{ displayValue(field) || '—' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 p-4 bg-brand-teal/5 border border-brand-teal/20 rounded-xl">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" v-model="agreed" class="mt-1 w-4 h-4 rounded border-border-gray text-brand-teal focus:ring-brand-teal" />
                                <span class="text-sm text-navy-light">
                                    I certify that all information provided is true and correct.
                                </span>
                            </label>
                        </div>
                    </div>
                </transition>

                <!-- Navigation -->
                <div class="mt-10 flex justify-between items-center">
                    <button
                        v-if="currentStep > 1"
                        @click="currentStep--"
                        class="inline-flex items-center px-6 py-3 rounded-full border border-border-gray font-semibold text-navy hover:border-navy transition"
                    >
                        <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Back
                    </button>
                    <div v-else></div>

                    <button
                        v-if="currentStep < totalSteps"
                        @click="nextStep"
                        class="inline-flex items-center px-8 py-3 rounded-full text-white font-semibold bg-gradient-to-r from-brand-green to-brand-teal hover:opacity-90 transition shadow-lg"
                    >
                        Next
                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <button
                        v-else
                        @click="submitApplication"
                        :disabled="!agreed || submitting"
                        class="inline-flex items-center px-8 py-3 rounded-full text-white font-semibold bg-gradient-to-r from-brand-green to-brand-teal hover:opacity-90 transition shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span v-if="submitting" class="w-5 h-5 mr-2 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                        Submit Application
                    </button>
                </div>
            </div>

            <!-- Success Modal -->
            <div v-if="submitted" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
                <div class="bg-white rounded-card shadow-card p-10 max-w-md mx-4 text-center">
                    <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-gradient-to-r from-brand-green/20 to-brand-teal/20 flex items-center justify-center">
                        <svg class="w-10 h-10 text-brand-teal" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-navy">Application Submitted!</h2>
                    <p class="mt-3 text-navy-light">Your application has been received successfully.</p>
                    <p class="mt-2 text-sm text-navy-light">Reference: <strong>{{ submittedKey }}</strong></p>
                    <button
                        @click="$router.push('/')"
                        class="mt-6 inline-flex items-center px-8 py-3 rounded-full text-white font-semibold bg-gradient-to-r from-brand-green to-brand-teal hover:opacity-90 transition"
                    >
                        Back to Home
                    </button>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();

const loading = ref(true);
const loadError = ref(null);
const currentStep = ref(1);
const submitted = ref(false);
const submitting = ref(false);
const submittedKey = ref('');
const agreed = ref(false);

const formDef = ref({});
const fields = ref([]);
const values = reactive({});
const fieldErrors = reactive({});
const pageTitles = ref({});

const formKey = computed(() => route.params.formKey || route.query.form || 'passport-application');

const pageNumbers = computed(() => {
    const nums = [...new Set(fields.value.map(f => f.page_num))].sort((a, b) => a - b);
    return nums.length ? nums : [1];
});

const totalSteps = computed(() => pageNumbers.value.length + 1); // pages + review

onMounted(async () => {
    await loadForm();
});

async function loadForm() {
    loading.value = true;
    loadError.value = null;
    try {
        const { data } = await window.axios.get(`/public/forms/${formKey.value}`);
        formDef.value = data.form;
        fields.value = data.fields;

        // Parse page titles from form options
        try {
            const opts = typeof data.form.options === 'string' ? JSON.parse(data.form.options) : data.form.options;
            if (opts?.page_titles) pageTitles.value = opts.page_titles;
        } catch { /* ignore */ }

        // Initialize values with defaults
        for (const field of data.fields) {
            values[field.field_key] = field.default_value || '';
        }
    } catch (e) {
        loadError.value = e.response?.data?.message || 'Could not load the form.';
    } finally {
        loading.value = false;
    }
}

function fieldsForPage(pageNum) {
    return fields.value
        .filter(f => f.page_num === pageNum)
        .sort((a, b) => a.field_order - b.field_order);
}

function pageTitle(pageNum) {
    if (pageTitles.value[pageNum]) return pageTitles.value[pageNum];
    return 'Page ' + pageNum;
}

function isInputType(type) {
    return ['text', 'email', 'phone', 'number', 'date', 'hidden'].includes(type);
}

function mapInputType(type) {
    if (type === 'phone') return 'tel';
    return type;
}

function isFullWidth(field) {
    return ['textarea', 'file', 'checkbox', 'radio'].includes(field.type);
}

function getPlaceholder(field) {
    return field.default_value || '';
}

function getOptions(field) {
    if (!field.options) return [];
    try {
        const parsed = JSON.parse(field.options);
        return Array.isArray(parsed) ? parsed : [];
    } catch {
        // Comma-separated fallback
        return field.options.split(',').map(s => s.trim()).filter(Boolean);
    }
}

function toggleFile(fieldKey) {
    values[fieldKey] = values[fieldKey] === '1' ? '0' : '1';
}

function displayValue(field) {
    const val = values[field.field_key];
    if (!val || val === '0') return '';
    if (field.type === 'checkbox') return val === '1' ? 'Yes' : 'No';
    if (field.type === 'file') return val === '1' ? 'Uploaded' : '';
    return val;
}

function validateCurrentPage() {
    let valid = true;
    // Clear errors
    Object.keys(fieldErrors).forEach(k => delete fieldErrors[k]);

    const pageFields = fieldsForPage(currentStep.value);
    for (const field of pageFields) {
        if (field.required && (!values[field.field_key] || values[field.field_key] === '0')) {
            fieldErrors[field.field_key] = `${field.name} is required`;
            valid = false;
        }
    }
    return valid;
}

function jumpToStep(step) {
    if (step <= currentStep.value) {
        currentStep.value = step;
    }
}

function nextStep() {
    if (!validateCurrentPage()) return;
    if (currentStep.value < totalSteps.value) {
        currentStep.value++;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

async function submitApplication() {
    if (!agreed.value || submitting.value) return;
    submitting.value = true;

    try {
        const { data } = await window.axios.post(`/public/forms/${formKey.value}/submit`, {
            values: { ...values },
        });
        submittedKey.value = data.item_key;
        submitted.value = true;
    } catch (e) {
        alert(e.response?.data?.message || 'Submission failed. Please try again.');
    } finally {
        submitting.value = false;
    }
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
