<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import Dialog from 'primevue/dialog';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';

const route = useRoute();
const router = useRouter();
const confirm = useConfirm();
const toast = useToast();

const entry = ref(null);
const loading = ref(true);

// Edit dialog state
const editVisible = ref(false);
const editLoading = ref(false);
const saving = ref(false);
const formDef = ref(null);
const formFields = ref([]);
const editValues = ref({});
const pageTitles = ref({});
const currentPage = ref(1);

const pageNumbers = computed(() => {
    const nums = [...new Set(formFields.value.map(f => f.page_num))].sort((a, b) => a - b);
    return nums.length ? nums : [1];
});

onMounted(() => {
    loadEntry();
});

async function loadEntry() {
    loading.value = true;
    try {
        const { data } = await window.axios.get(`/form-entries/${route.params.id}`);
        entry.value = data.entry;
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Entry not found', life: 3000 });
        router.push({ name: 'entries' });
    } finally {
        loading.value = false;
    }
}

async function openEdit() {
    editVisible.value = true;
    editLoading.value = true;
    currentPage.value = 1;

    try {
        const { data } = await window.axios.get(`/forms/${entry.value.form_id}`);
        formDef.value = data.form;
        formFields.value = data.form.fields ?? [];

        // Parse page titles from form options
        try {
            const opts = typeof data.form.options === 'string' ? JSON.parse(data.form.options) : data.form.options;
            if (opts?.page_titles) pageTitles.value = opts.page_titles;
        } catch { /* ignore */ }

        // Build values map: start with defaults, then override with entry metas
        const vals = {};
        for (const field of formFields.value) {
            vals[field.field_key] = field.default_value || '';
        }
        if (entry.value.metas) {
            for (const meta of entry.value.metas) {
                if (meta.field?.field_key) {
                    vals[meta.field.field_key] = meta.meta_value ?? '';
                }
            }
        }
        editValues.value = vals;
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Failed to load form', life: 3000 });
        editVisible.value = false;
    } finally {
        editLoading.value = false;
    }
}

async function saveEdit() {
    saving.value = true;
    try {
        const { data } = await window.axios.put(`/form-entries/${entry.value.id}`, {
            values: editValues.value,
        });
        entry.value = data.entry;
        editVisible.value = false;
        toast.add({ severity: 'success', summary: 'Entry updated', life: 3000 });
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Failed to save', life: 3000 });
    } finally {
        saving.value = false;
    }
}

function fieldsForPage(pageNum) {
    return formFields.value
        .filter(f => f.page_num === pageNum)
        .sort((a, b) => a.field_order - b.field_order);
}

function pageTitle(pageNum) {
    return pageTitles.value[pageNum] || `Page ${pageNum}`;
}

function isInputType(type) {
    return ['text', 'email', 'phone', 'number', 'date', 'hidden'].includes(type);
}

function mapInputType(type) {
    return type === 'phone' ? 'tel' : type;
}

function isFullWidth(field) {
    return ['textarea', 'file', 'checkbox', 'radio'].includes(field.type);
}

function getOptions(field) {
    if (!field.options) return [];
    try {
        const parsed = JSON.parse(field.options);
        return Array.isArray(parsed) ? parsed : [];
    } catch {
        return field.options.split(',').map(s => s.trim()).filter(Boolean);
    }
}

function confirmDelete() {
    confirm.require({
        message: `Are you sure you want to delete entry #${entry.value?.id}?`,
        header: 'Delete Entry',
        icon: 'pi pi-trash',
        rejectLabel: 'Cancel',
        acceptLabel: 'Delete',
        acceptClass: 'p-button-danger',
        accept: deleteEntry,
    });
}

async function deleteEntry() {
    try {
        await window.axios.delete(`/form-entries/${entry.value.id}`);
        toast.add({ severity: 'success', summary: 'Entry deleted', life: 3000 });
        router.push({ name: 'entries' });
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Failed to delete entry', life: 3000 });
    }
}

function printEntry() {
    window.print();
}

function formatDate(dateStr) {
    if (!dateStr) return '—';
    return new Date(dateStr).toLocaleString();
}
</script>

<template>
    <div>
        <div class="flex items-center gap-2 mb-4">
            <Button icon="pi pi-arrow-left" text rounded @click="router.push({ name: 'entries' })" />
            <h2 class="text-2xl font-semibold">
                Entry #{{ entry?.id ?? route.params.id }}
            </h2>
            <Tag
                v-if="entry"
                :value="entry.is_draft ? 'Draft' : 'Submitted'"
                :severity="entry.is_draft ? 'warn' : 'success'"
                class="ml-2"
            />
        </div>

        <div v-if="loading" class="flex justify-center py-12">
            <i class="pi pi-spin pi-spinner text-3xl text-surface-400" />
        </div>

        <div v-else-if="entry">
            <div class="flex gap-2 mb-4 print:hidden">
                <Button label="Edit" icon="pi pi-pencil" @click="openEdit" />
                <Button label="Print" icon="pi pi-print" outlined @click="printEntry" />
                <Button label="Delete" icon="pi pi-trash" severity="danger" outlined @click="confirmDelete" />
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <!-- Main content: tabs -->
                <div class="lg:col-span-2">
                    <TabView>
                        <TabPanel header="Preview">
                            <div v-if="entry.metas && entry.metas.length" class="flex flex-col gap-3 pt-2">
                                <div
                                    v-for="meta in entry.metas"
                                    :key="meta.id"
                                    class="flex flex-col gap-1 border-b border-surface-200 pb-3 last:border-0"
                                >
                                    <span class="text-xs font-semibold text-surface-500 uppercase tracking-wide">
                                        {{ meta.field?.name ?? `Field #${meta.field_id}` }}
                                    </span>
                                    <span class="text-sm">{{ meta.meta_value || '—' }}</span>
                                </div>
                            </div>
                            <div v-else class="text-surface-400 py-4">
                                No field values recorded for this entry.
                            </div>
                        </TabPanel>
                    </TabView>
                </div>

                <!-- Sidebar: entry details -->
                <div class="card print:hidden">
                    <h3 class="text-base font-semibold mb-3">Entry Details</h3>
                    <div class="flex flex-col gap-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-surface-500">ID</span>
                            <span>{{ entry.id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-surface-500">Key</span>
                            <span class="font-mono text-xs">{{ entry.item_key }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-surface-500">Form</span>
                            <span>{{ entry.form?.name ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-surface-500">User</span>
                            <span>{{ entry.user?.name ?? 'Guest' }}</span>
                        </div>
                        <div v-if="entry.user?.email" class="flex justify-between">
                            <span class="text-surface-500">Email</span>
                            <span>{{ entry.user.email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-surface-500">IP</span>
                            <span class="font-mono text-xs">{{ entry.ip || '—' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-surface-500">Status</span>
                            <Tag
                                :value="entry.is_draft ? 'Draft' : 'Submitted'"
                                :severity="entry.is_draft ? 'warn' : 'success'"
                            />
                        </div>
                        <div class="flex justify-between">
                            <span class="text-surface-500">Submitted</span>
                            <span>{{ formatDate(entry.created_at) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-surface-500">Updated</span>
                            <span>{{ formatDate(entry.updated_at) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Dialog -->
        <Dialog
            v-model:visible="editVisible"
            :header="`Edit Entry #${entry?.id}`"
            modal
            :style="{ width: '860px', maxWidth: '95vw' }"
            :maximizable="true"
        >
            <div v-if="editLoading" class="flex justify-center py-12">
                <i class="pi pi-spin pi-spinner text-3xl text-surface-400" />
            </div>

            <div v-else>
                <!-- Page tabs -->
                <div class="flex gap-2 flex-wrap mb-6 border-b border-surface-200 pb-3">
                    <button
                        v-for="page in pageNumbers"
                        :key="page"
                        @click="currentPage = page"
                        class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors"
                        :class="currentPage === page
                            ? 'bg-primary text-white'
                            : 'bg-surface-100 text-surface-600 hover:bg-surface-200'"
                    >
                        {{ pageTitle(page) }}
                    </button>
                </div>

                <!-- Fields for current page -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div
                        v-for="field in fieldsForPage(currentPage)"
                        :key="field.id"
                        :class="isFullWidth(field) ? 'md:col-span-2' : ''"
                    >
                        <label class="block text-sm font-medium mb-1">
                            {{ field.name }}
                            <span v-if="field.required" class="text-red-500">*</span>
                        </label>
                        <p v-if="field.description" class="text-xs text-surface-400 mb-1">{{ field.description }}</p>

                        <!-- Text / Email / Phone / Number / Date -->
                        <input
                            v-if="isInputType(field.type)"
                            v-model="editValues[field.field_key]"
                            :type="mapInputType(field.type)"
                            class="w-full px-3 py-2 rounded-lg border border-surface-300 focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                        />

                        <!-- Textarea -->
                        <textarea
                            v-else-if="field.type === 'textarea'"
                            v-model="editValues[field.field_key]"
                            rows="3"
                            class="w-full px-3 py-2 rounded-lg border border-surface-300 focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                        />

                        <!-- Select -->
                        <select
                            v-else-if="field.type === 'select'"
                            v-model="editValues[field.field_key]"
                            class="w-full px-3 py-2 rounded-lg border border-surface-300 focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                        >
                            <option value="">Select</option>
                            <option v-for="opt in getOptions(field)" :key="opt" :value="opt">{{ opt }}</option>
                        </select>

                        <!-- Checkbox -->
                        <label v-else-if="field.type === 'checkbox'" class="flex items-center gap-2 cursor-pointer">
                            <input
                                type="checkbox"
                                :checked="editValues[field.field_key] === '1'"
                                @change="editValues[field.field_key] = $event.target.checked ? '1' : '0'"
                                class="w-4 h-4"
                            />
                            <span class="text-sm">{{ field.name }}</span>
                        </label>

                        <!-- Radio -->
                        <div v-else-if="field.type === 'radio'" class="flex flex-col gap-2">
                            <label v-for="opt in getOptions(field)" :key="opt" class="flex items-center gap-2 cursor-pointer">
                                <input
                                    type="radio"
                                    :name="`edit_${field.field_key}`"
                                    :value="opt"
                                    v-model="editValues[field.field_key]"
                                    class="w-4 h-4"
                                />
                                <span class="text-sm">{{ opt }}</span>
                            </label>
                        </div>

                        <!-- Fallback -->
                        <input
                            v-else
                            v-model="editValues[field.field_key]"
                            type="text"
                            class="w-full px-3 py-2 rounded-lg border border-surface-300 focus:outline-none focus:ring-2 focus:ring-primary text-sm"
                        />
                    </div>
                </div>

                <!-- Page navigation -->
                <div class="flex justify-between items-center mt-6 pt-4 border-t border-surface-200">
                    <Button
                        v-if="currentPage > pageNumbers[0]"
                        label="Previous"
                        icon="pi pi-chevron-left"
                        outlined
                        @click="currentPage--"
                    />
                    <div v-else />
                    <Button
                        v-if="currentPage < pageNumbers[pageNumbers.length - 1]"
                        label="Next"
                        icon="pi pi-chevron-right"
                        iconPos="right"
                        outlined
                        @click="currentPage++"
                    />
                    <div v-else />
                </div>
            </div>

            <template #footer>
                <Button label="Cancel" text @click="editVisible = false" />
                <Button label="Save" icon="pi pi-check" :loading="saving" @click="saveEdit" />
            </template>
        </Dialog>
    </div>
</template>

<style scoped>
@media print {
    .print\:hidden {
        display: none !important;
    }
}
</style>
