<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Tag from 'primevue/tag';
import Button from 'primevue/button';
import { useToast } from 'primevue/usetoast';
import { useListParams } from '../../composables/useListParams';

const router = useRouter();
const toast = useToast();
const { currentPage, perPage, sortField, sortOrder, apiParams, onPage, onSort } = useListParams();

const forms = ref([]);
const loading = ref(true);
const totalRecords = ref(0);

onMounted(() => {
    loadForms();
});

async function loadForms() {
    loading.value = true;
    try {
        const { data } = await window.axios.get('/forms', { params: apiParams() });
        forms.value = data.forms;
        totalRecords.value = data.pagination.total;
    } catch (e) {
        console.error('Failed to load forms', e);
    } finally {
        loading.value = false;
    }
}

function openForm(form) {
    router.push({ name: 'form-edit', params: { id: form.id } });
}
</script>

<template>
    <div class="card">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold">Forms</h2>
        </div>

        <DataTable
            :value="forms"
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
            @page="onPage($event, loadForms)"
            @sort="onSort($event, loadForms)"
            @row-click="openForm($event.data)"
            class="cursor-pointer"
        >
            <Column field="id" header="#" sortable style="width: 60px" />
            <Column field="name" header="Name" sortable />
            <Column field="form_key" header="Key" sortable />
            <Column field="fields_count" header="Fields" style="width: 100px">
                <template #body="{ data }">
                    {{ data.fields_count }}
                </template>
            </Column>
            <Column field="status" header="Status" sortable style="width: 120px">
                <template #body="{ data }">
                    <Tag v-if="data.status" :value="data.status" :severity="data.status === 'active' ? 'success' : 'warn'" />
                    <span v-else class="text-surface-400">—</span>
                </template>
            </Column>
            <Column header="" style="width: 80px">
                <template #body="{ data }">
                    <Button icon="pi pi-pencil" text rounded @click.stop="openForm(data)" />
                </template>
            </Column>
        </DataTable>
    </div>
</template>
