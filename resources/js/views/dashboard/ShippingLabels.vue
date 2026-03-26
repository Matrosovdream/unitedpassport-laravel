<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
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

const route = useRoute();
const toast = useToast();

const q = route.query;
const filters = ref({
    search:    q.search    || '',
    entry_id:  q.entry_id  ? Number(q.entry_id) : null,
    status:    q.status    || null,
    mode:      q.mode      || null,
    is_return: q.is_return !== undefined ? q.is_return : null,
    date_from: q.date_from ? new Date(q.date_from) : null,
    date_to:   q.date_to   ? new Date(q.date_to)   : null,
});

const { currentPage, perPage, sortField, sortOrder, apiParams, syncUrl, onPage, onSort } = useListParams(
    {},
    () => {
        const extra = {};
        if (filters.value.search)                    extra.search    = filters.value.search;
        if (filters.value.entry_id)                  extra.entry_id  = filters.value.entry_id;
        if (filters.value.status)                    extra.status    = filters.value.status;
        if (filters.value.mode)                      extra.mode      = filters.value.mode;
        if (filters.value.is_return !== null)        extra.is_return = filters.value.is_return;
        if (filters.value.date_from)                 extra.date_from = formatDate(filters.value.date_from);
        if (filters.value.date_to)                   extra.date_to   = formatDate(filters.value.date_to);
        return extra;
    }
);

const labels = ref([]);
const loading = ref(true);
const totalRecords = ref(0);

const statusOptions = [
    { label: 'All statuses', value: null },
    { label: 'Pre-transit', value: 'pre_transit' },
    { label: 'In transit', value: 'in_transit' },
    { label: 'Out for delivery', value: 'out_for_delivery' },
    { label: 'Delivered', value: 'delivered' },
    { label: 'Return to sender', value: 'return_to_sender' },
    { label: 'Failure', value: 'failure' },
    { label: 'Unknown', value: 'unknown' },
];

const modeOptions = [
    { label: 'All modes', value: null },
    { label: 'Test', value: 'test' },
    { label: 'Production', value: 'production' },
];

const returnOptions = [
    { label: 'All', value: null },
    { label: 'Return', value: '1' },
    { label: 'Outbound', value: '0' },
];

let searchTimeout = null;

onMounted(() => {
    loadLabels();
});

async function loadLabels() {
    loading.value = true;
    try {
        const params = { ...apiParams() };

        if (filters.value.search)                    params.search    = filters.value.search;
        if (filters.value.entry_id)                  params.entry_id  = filters.value.entry_id;
        if (filters.value.status)                    params.status    = filters.value.status;
        if (filters.value.mode)                      params.mode      = filters.value.mode;
        if (filters.value.is_return !== null)        params.is_return = filters.value.is_return;
        if (filters.value.date_from)                 params.date_from = formatDate(filters.value.date_from);
        if (filters.value.date_to)                   params.date_to   = formatDate(filters.value.date_to);

        const { data } = await window.axios.get('/shipping-labels', { params });
        labels.value = data.labels;
        totalRecords.value = data.pagination.total;
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Failed to load shipping labels', life: 3000 });
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
    loadLabels();
}

function onSearchInput() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(applyFilters, 400);
}

function resetFilters() {
    filters.value = { search: '', entry_id: null, status: null, mode: null, is_return: null, date_from: null, date_to: null };
    applyFilters();
}

function statusSeverity(status) {
    const map = {
        delivered: 'success',
        in_transit: 'info',
        out_for_delivery: 'info',
        pre_transit: 'secondary',
        return_to_sender: 'warn',
        failure: 'danger',
        unknown: 'secondary',
    };
    return map[status] || 'secondary';
}

function statusLabel(status) {
    if (!status) return '—';
    return status.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
}
</script>

<template>
    <div class="card">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold">Shipping Labels</h2>
        </div>

        <!-- Filters -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mb-4">
            <InputText
                v-model="filters.search"
                placeholder="Search by tracking code, EasyPost ID..."
                @input="onSearchInput"
            />

            <Select
                v-model="filters.status"
                :options="statusOptions"
                optionLabel="label"
                optionValue="value"
                placeholder="All statuses"
                @change="applyFilters"
            />

            <Select
                v-model="filters.mode"
                :options="modeOptions"
                optionLabel="label"
                optionValue="value"
                placeholder="All modes"
                @change="applyFilters"
            />

            <InputNumber
                v-model="filters.entry_id"
                placeholder="Entry ID"
                :useGrouping="false"
                @keyup.enter="applyFilters"
                @blur="applyFilters"
            />

            <Select
                v-model="filters.is_return"
                :options="returnOptions"
                optionLabel="label"
                optionValue="value"
                placeholder="All shipments"
                @change="applyFilters"
            />

            <DatePicker
                v-model="filters.date_from"
                placeholder="Date from"
                dateFormat="yy-mm-dd"
                showButtonBar
                @date-select="applyFilters"
                @clear-click="applyFilters"
            />

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
            :value="labels"
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
            @page="onPage($event, loadLabels)"
            @sort="onSort($event, loadLabels)"
        >
            <template #paginatorstart>
                <PaginatorInfo :currentPage="currentPage" :perPage="perPage" :total="totalRecords" />
            </template>

            <Column field="id" header="#" sortable style="width: 70px" />

            <Column field="entry_id" header="Entry ID" sortable style="width: 100px">
                <template #body="{ data }">
                    <span class="text-sm">{{ data.entry_id || '—' }}</span>
                </template>
            </Column>

            <Column field="tracking_code" header="Tracking Code" sortable>
                <template #body="{ data }">
                    <a
                        v-if="data.tracking_url"
                        :href="data.tracking_url"
                        target="_blank"
                        class="text-primary underline text-sm"
                        @click.stop
                    >{{ data.tracking_code || '—' }}</a>
                    <span v-else class="text-sm">{{ data.tracking_code || '—' }}</span>
                </template>
            </Column>

            <Column header="Carrier / Service">
                <template #body="{ data }">
                    <span v-if="data.selected_rate" class="text-sm">
                        {{ data.selected_rate.carrier }} {{ data.selected_rate.service }}
                    </span>
                    <span v-else class="text-surface-400 text-sm">—</span>
                </template>
            </Column>

            <Column header="Rate">
                <template #body="{ data }">
                    <span v-if="data.selected_rate" class="text-sm">
                        ${{ Number(data.selected_rate.rate).toFixed(2) }} {{ data.selected_rate.currency }}
                    </span>
                    <span v-else class="text-surface-400 text-sm">—</span>
                </template>
            </Column>

            <Column field="status" header="Status" sortable style="width: 150px">
                <template #body="{ data }">
                    <Tag
                        v-if="data.status"
                        :value="statusLabel(data.status)"
                        :severity="statusSeverity(data.status)"
                    />
                    <span v-else class="text-surface-400 text-sm">—</span>
                </template>
            </Column>

            <Column field="mode" header="Mode" sortable style="width: 100px">
                <template #body="{ data }">
                    <Tag
                        :value="data.mode"
                        :severity="data.mode === 'production' ? 'success' : 'secondary'"
                    />
                </template>
            </Column>

            <Column field="is_return" header="Type" style="width: 90px">
                <template #body="{ data }">
                    <Tag
                        :value="data.is_return ? 'Return' : 'Outbound'"
                        :severity="data.is_return ? 'warn' : 'info'"
                    />
                </template>
            </Column>

            <Column header="Label" style="width: 90px">
                <template #body="{ data }">
                    <a
                        v-if="data.label && data.label.label_pdf_url"
                        :href="data.label.label_pdf_url"
                        target="_blank"
                        @click.stop
                    >
                        <Button icon="pi pi-file-pdf" text rounded size="small" severity="danger" />
                    </a>
                    <a
                        v-else-if="data.label && data.label.label_url"
                        :href="data.label.label_url"
                        target="_blank"
                        @click.stop
                    >
                        <Button icon="pi pi-download" text rounded size="small" />
                    </a>
                    <span v-else class="text-surface-400 text-sm">—</span>
                </template>
            </Column>

            <Column field="created_at" header="Date" sortable style="width: 160px">
                <template #body="{ data }">
                    <span class="text-sm">{{ new Date(data.created_at).toLocaleString() }}</span>
                </template>
            </Column>
        </DataTable>
    </div>
</template>
