<script setup>
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Tag from 'primevue/tag';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import Select from 'primevue/select';
import DatePicker from 'primevue/datepicker';
import { useToast } from 'primevue/usetoast';
import { useListParams } from '../../composables/useListParams';
import PaginatorInfo from '../../components/PaginatorInfo.vue';

const router = useRouter();
const route = useRoute();
const toast = useToast();

// Read initial filter values from URL query params
const q = route.query;
const filters = ref({
    search:    q.search    || '',
    form_id:   q.form_id   ? Number(q.form_id) : null,
    entry_id:  q.entry_id  ? Number(q.entry_id) : null,
    is_draft:  q.is_draft  !== undefined ? q.is_draft : null,
    date_from: q.date_from ? new Date(q.date_from) : null,
    date_to:   q.date_to   ? new Date(q.date_to)   : null,
});

const { currentPage, perPage, sortField, sortOrder, apiParams, syncUrl, onPage, onSort } = useListParams(
    {},
    () => {
        const extra = {};
        if (filters.value.search)                extra.search    = filters.value.search;
        if (filters.value.form_id)               extra.form_id   = filters.value.form_id;
        if (filters.value.entry_id)              extra.entry_id  = filters.value.entry_id;
        if (filters.value.is_draft !== null)     extra.is_draft  = filters.value.is_draft;
        if (filters.value.date_from)             extra.date_from = formatDate(filters.value.date_from);
        if (filters.value.date_to)               extra.date_to   = formatDate(filters.value.date_to);
        return extra;
    }
);

const entries = ref([]);
const loading = ref(true);
const totalRecords = ref(0);
const forms = ref([]);

const statusOptions = [
    { label: 'All statuses', value: null },
    { label: 'Submitted', value: '0' },
    { label: 'Draft', value: '1' },
];

let searchTimeout = null;

onMounted(async () => {
    await loadForms();
    loadEntries();
});

async function loadForms() {
    try {
        const { data } = await window.axios.get('/forms', { params: { per_page: 200 } });
        forms.value = [{ id: null, name: 'All forms' }, ...data.forms];
    } catch (e) {
        // non-critical
    }
}

async function loadEntries() {
    loading.value = true;
    try {
        const params = { ...apiParams() };

        if (filters.value.search)                params.search   = filters.value.search;
        if (filters.value.form_id)               params.form_id  = filters.value.form_id;
        if (filters.value.entry_id)              params.entry_id = filters.value.entry_id;
        if (filters.value.is_draft !== null)     params.is_draft = filters.value.is_draft;
        if (filters.value.date_from)             params.date_from = formatDate(filters.value.date_from);
        if (filters.value.date_to)               params.date_to   = formatDate(filters.value.date_to);

        const { data } = await window.axios.get('/form-entries', { params });
        entries.value = data.entries;
        totalRecords.value = data.pagination.total;
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Failed to load entries', life: 3000 });
    } finally {
        loading.value = false;
    }
}

function formatDate(d) {
    if (!d) return null;
    const date = d instanceof Date ? d : new Date(d);
    return date.toISOString().split('T')[0];
}

function applyFilters() {
    currentPage.value = 1;
    syncUrl();
    loadEntries();
}

function onSearchInput() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(applyFilters, 400);
}

function resetFilters() {
    filters.value = { search: '', form_id: null, entry_id: null, is_draft: null, date_from: null, date_to: null };
    applyFilters();
}

function openEntry(entry) {
    router.push({ name: 'entry-show', params: { id: entry.id } });
}
</script>

<template>
    <div class="card">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold">Entries</h2>
        </div>

        <!-- Filters -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mb-4">
            <!-- Common search -->
            <InputText
                v-model="filters.search"
                placeholder="Search by form, user, name..."
                @input="onSearchInput"
            />

            <!-- Form -->
            <Select
                v-model="filters.form_id"
                :options="forms"
                optionLabel="name"
                optionValue="id"
                placeholder="All forms"
                @change="applyFilters"
                showClear
            />

            <!-- Status -->
            <Select
                v-model="filters.is_draft"
                :options="statusOptions"
                optionLabel="label"
                optionValue="value"
                placeholder="All statuses"
                @change="applyFilters"
            />

            <!-- Entry ID -->
            <InputNumber
                v-model="filters.entry_id"
                placeholder="Entry ID"
                :useGrouping="false"
                @keyup.enter="applyFilters"
                @blur="applyFilters"
            />

            <!-- Date from -->
            <DatePicker
                v-model="filters.date_from"
                placeholder="Date from"
                dateFormat="yy-mm-dd"
                showButtonBar
                @date-select="applyFilters"
                @clear-click="applyFilters"
            />

            <!-- Date to -->
            <DatePicker
                v-model="filters.date_to"
                placeholder="Date to"
                dateFormat="yy-mm-dd"
                showButtonBar
                @date-select="applyFilters"
                @clear-click="applyFilters"
            />
        </div>

        <div class="flex justify-end mb-3">
            <Button label="Reset filters" icon="pi pi-times" text size="small" @click="resetFilters" />
        </div>

        <DataTable
            :value="entries"
            :loading="loading"
            lazy
            :totalRecords="totalRecords"
            :rows="perPage"
            :first="(currentPage - 1) * perPage"
            :rowsPerPageOptions="[10, 20, 50]"
            :sortField="sortField"
            :sortOrder="sortOrder"
            paginator
            stripedRows
            @page="onPage($event, loadEntries)"
            @sort="onSort($event, loadEntries)"
            @row-click="openEntry($event.data)"
            class="cursor-pointer"
        >
            <template #paginatorstart>
                <PaginatorInfo :currentPage="currentPage" :perPage="perPage" :total="totalRecords" />
            </template>
            <Column field="id" header="#" sortable style="width: 70px" />
            <Column header="Form" sortable sort-field="form_id">
                <template #body="{ data }">
                    <span v-if="data.form">{{ data.form.name }}</span>
                    <span v-else class="text-surface-400">—</span>
                </template>
            </Column>
            <Column header="User">
                <template #body="{ data }">
                    <span v-if="data.user">{{ data.user.name }}</span>
                    <span v-else class="text-surface-400">Guest</span>
                </template>
            </Column>
            <Column field="ip" header="IP" style="width: 140px">
                <template #body="{ data }">
                    <span class="text-surface-500 text-sm">{{ data.ip || '—' }}</span>
                </template>
            </Column>
            <Column field="is_draft" header="Status" style="width: 110px">
                <template #body="{ data }">
                    <Tag
                        :value="data.is_draft ? 'Draft' : 'Submitted'"
                        :severity="data.is_draft ? 'warn' : 'success'"
                    />
                </template>
            </Column>
            <Column field="created_at" header="Date" sortable style="width: 160px">
                <template #body="{ data }">
                    <span class="text-sm">{{ new Date(data.created_at).toLocaleString() }}</span>
                </template>
            </Column>
            <Column header="" style="width: 60px">
                <template #body="{ data }">
                    <Button icon="pi pi-eye" text rounded @click.stop="openEntry(data)" />
                </template>
            </Column>
        </DataTable>
    </div>
</template>
