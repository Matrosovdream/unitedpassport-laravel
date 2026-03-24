<template>
    <div class="card mb-6">
        <div class="font-semibold text-xl mb-4">Data Migration</div>
        <p class="text-muted-color mb-4">Import all data from the old WordPress site. Jobs run in the background via queue.</p>

        <div class="flex flex-col md:flex-row gap-4 mb-2">
            <div class="flex-1">
                <label class="block text-sm font-medium mb-1">Source URL</label>
                <InputText v-model="sourceUrl" class="w-full" placeholder="https://example.com" />
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium mb-1">Password</label>
                <InputText v-model="sourcePassword" class="w-full" placeholder="pass123" />
            </div>
        </div>
    </div>

    <div class="card">
        <DataTable :value="tables" :loading="loadingTables" stripedRows sortField="order" :sortOrder="1">
            <Column field="order" header="#" style="width: 50px" sortable />
            <Column field="label" header="Table" />
            <Column field="local_table" header="Local Table" />
            <Column header="Action" style="width: 180px">
                <template #body="{ data }">
                    <Button
                        v-if="!isRunning(data.key)"
                        label="Import All"
                        icon="pi pi-download"
                        :disabled="starting === data.key"
                        @click="startImport(data.key)"
                        size="small"
                    />
                    <Tag v-else severity="info" icon="pi pi-spin pi-spinner" value="Running..." />
                </template>
            </Column>
            <Column header="Progress" style="min-width: 350px">
                <template #body="{ data }">
                    <div v-if="jobFor(data.key)" class="text-sm">
                        <div class="flex items-center gap-2 mb-2">
                            <Tag :severity="statusSeverity(jobFor(data.key).status)" :value="jobFor(data.key).status" />
                            <span v-if="jobFor(data.key).total_pages" class="text-muted-color">
                                Page {{ jobFor(data.key).current_page }} / {{ jobFor(data.key).total_pages }}
                            </span>
                        </div>

                        <ProgressBar
                            v-if="jobFor(data.key).total_pages && jobFor(data.key).status === 'running'"
                            :value="Math.round((jobFor(data.key).current_page / jobFor(data.key).total_pages) * 100)"
                            style="height: 6px"
                            class="mb-2"
                        />

                        <div class="flex gap-3">
                            <span class="text-green-500 font-medium">{{ jobFor(data.key).imported }} created</span>
                            <span class="text-blue-500 font-medium">{{ jobFor(data.key).updated }} updated</span>
                            <span class="text-muted-color">{{ jobFor(data.key).skipped }} skipped</span>
                        </div>

                        <div v-if="jobFor(data.key).total_rows" class="text-muted-color mt-1">
                            {{ jobFor(data.key).total_rows }} total rows
                        </div>

                        <div v-if="jobFor(data.key).errors?.length" class="mt-2">
                            <div
                                v-for="(err, i) in jobFor(data.key).errors.slice(-5)"
                                :key="i"
                                class="text-red-400 text-xs"
                            >
                                {{ err }}
                            </div>
                            <span v-if="jobFor(data.key).errors.length > 5" class="text-muted-color text-xs">
                                ... and {{ jobFor(data.key).errors.length - 5 }} more
                            </span>
                        </div>
                    </div>
                </template>
            </Column>
        </DataTable>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, computed } from 'vue';

const tables = ref([]);
const loadingTables = ref(true);
const starting = ref(null);
const sourceUrl = ref('');
const sourcePassword = ref('');
const jobs = ref([]);
let pollTimer = null;

onMounted(async () => {
    try {
        const { data } = await window.axios.get('/api/migration/tables');
        tables.value = data.tables;
        sourceUrl.value = data.config?.source_url || '';
        sourcePassword.value = data.config?.source_password || '';
    } catch (e) {
        console.error('Failed to load tables', e);
    } finally {
        loadingTables.value = false;
    }

    await fetchStatus();
    startPolling();
});

onBeforeUnmount(() => {
    stopPolling();
});

function jobFor(tableKey) {
    return jobs.value.find(j => j.table_key === tableKey) || null;
}

function isRunning(tableKey) {
    const job = jobFor(tableKey);
    return job && (job.status === 'pending' || job.status === 'running');
}

const hasActiveJobs = computed(() => {
    return jobs.value.some(j => j.status === 'pending' || j.status === 'running');
});

function statusSeverity(status) {
    return {
        pending: 'warn',
        running: 'info',
        completed: 'success',
        failed: 'danger',
    }[status] || 'secondary';
}

async function fetchStatus() {
    try {
        const { data } = await window.axios.get('/api/migration/status');
        jobs.value = data.jobs;
    } catch (e) {
        console.error('Failed to fetch status', e);
    }
}

function startPolling() {
    pollTimer = setInterval(async () => {
        await fetchStatus();
        // Slow down polling if nothing active
        if (!hasActiveJobs.value && pollTimer) {
            stopPolling();
            pollTimer = setInterval(fetchStatus, 10000);
        }
    }, 2000);
}

function stopPolling() {
    if (pollTimer) {
        clearInterval(pollTimer);
        pollTimer = null;
    }
}

async function startImport(tableKey) {
    starting.value = tableKey;

    try {
        await window.axios.post('/api/migration/import', {
            table: tableKey,
            source_url: sourceUrl.value,
            source_password: sourcePassword.value,
        });
        await fetchStatus();
        // Ensure fast polling while active
        stopPolling();
        startPolling();
    } catch (e) {
        alert(e.response?.data?.message || 'Failed to start import');
    } finally {
        starting.value = null;
    }
}
</script>
