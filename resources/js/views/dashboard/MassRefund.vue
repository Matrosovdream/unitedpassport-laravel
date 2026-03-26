<script setup>
import { ref, computed } from 'vue';
import Button from 'primevue/button';
import Select from 'primevue/select';
import Checkbox from 'primevue/checkbox';
import Dialog from 'primevue/dialog';
import Textarea from 'primevue/textarea';
import InputText from 'primevue/inputtext';
import Tag from 'primevue/tag';

// ── Filters ──────────────────────────────────────────────────────────────────
const filters = ref({
    status: null,
    order_status: null,
    time: null,
    amount_type: null,
});

const statusOptions = [
    { label: 'All', value: null },
    { label: 'Processing', value: 'processing' },
    { label: 'On Hold', value: 'on_hold' },
    { label: 'Issue', value: 'issue' },
    { label: 'Complete', value: 'complete' },
];

const orderStatusOptions = [
    { label: 'All', value: null },
    { label: 'Failed', value: 'failed' },
    { label: 'Processing', value: 'processing' },
    { label: 'Processing-E', value: 'processing-e' },
    { label: 'Processing-NA', value: 'processing-na' },
    { label: 'Processing-X', value: 'processing-x' },
    { label: 'Verified', value: 'verified' },
    { label: 'Submitted', value: 'submitted' },
    { label: 'Submitted-C', value: 'submitted-c' },
    { label: 'Submitted-M', value: 'submitted-m' },
    { label: 'Mailed', value: 'mailed' },
    { label: 'On Hold', value: 'on_hold' },
    { label: 'On Hold-L', value: 'on_hold-l' },
    { label: 'On Hold-R', value: 'on_hold-r' },
    { label: 'Issue', value: 'issue' },
    { label: 'Refunded', value: 'refunded' },
    { label: 'Complete', value: 'complete' },
    { label: 'Complete-NA', value: 'complete-na' },
    { label: 'Complete-M', value: 'complete-m' },
];

const timeOptions = [
    { label: 'All', value: null },
    { label: 'Between 3:00PM – 3:00PM', value: 'today' },
    { label: 'Before 3:00PM Yesterday', value: 'before_yesterday' },
    { label: 'After 3:00PM Today', value: 'after_today' },
];

const amountTypeOptions = [
    { label: 'All', value: null },
    { label: 'Full', value: 'full' },
    { label: 'Partial', value: 'partial' },
];

const bulkOrderStatus = ref('refunded');
const bulkOrderStatusOptions = [
    { label: 'Refunded', value: 'refunded' },
];

// ── Mock data ─────────────────────────────────────────────────────────────────
const rows = ref([
    {
        id: 1,
        refund_id: 101,
        order_id: 5821,
        order_number: '5821',
        created_at: '2026-03-25 14:32:00',
        order_created_at: '2026-03-20 09:15:00',
        reason: 'Customer requested cancellation',
        status: 'processing',
        status_note: '',
        order_status: 'Processing',
        refund_amount: '189.00',
        order_total: '189.00',
        refunded_so_far: '0.00',
    },
    {
        id: 2,
        refund_id: 102,
        order_id: 5734,
        order_number: '5734',
        created_at: '2026-03-25 11:05:00',
        order_created_at: '2026-03-18 16:40:00',
        reason: 'Reason Test23',
        status: 'processing',
        status_note: '',
        order_status: 'Processing',
        refund_amount: '250.00',
        order_total: '250.00',
        refunded_so_far: '0.00',
    },
    {
        id: 3,
        refund_id: 103,
        order_id: 5699,
        order_number: '5699',
        created_at: '2026-03-24 09:20:00',
        order_created_at: '2026-03-15 11:00:00',
        reason: 'test',
        status: 'complete',
        status_note: 'Processed successfully',
        order_status: 'Refunded',
        refund_amount: '99.00',
        order_total: '99.00',
        refunded_so_far: '99.00',
    },
    {
        id: 4,
        refund_id: 104,
        order_id: 5612,
        order_number: '5612',
        created_at: '2026-03-23 17:45:00',
        order_created_at: '2026-03-10 08:30:00',
        reason: 'Duplicate order',
        status: 'on_hold',
        status_note: 'Waiting for confirmation',
        order_status: 'On Hold',
        refund_amount: '175.00',
        order_total: '350.00',
        refunded_so_far: '0.00',
    },
    {
        id: 5,
        refund_id: 105,
        order_id: 5580,
        order_number: '5580',
        created_at: '2026-03-22 13:10:00',
        order_created_at: '2026-03-08 14:22:00',
        reason: 'Service not rendered',
        status: 'issue',
        status_note: 'Gateway timeout',
        order_status: 'Issue',
        refund_amount: '320.00',
        order_total: '320.00',
        refunded_so_far: '0.00',
    },
    {
        id: 6,
        refund_id: 106,
        order_id: 5501,
        order_number: '5501',
        created_at: '2026-03-21 10:55:00',
        order_created_at: '2026-03-05 10:00:00',
        reason: 'Partial refund agreed',
        status: 'processing',
        status_note: '',
        order_status: 'Processing',
        refund_amount: '50.00',
        order_total: '189.00',
        refunded_so_far: '0.00',
    },
]);

const totalRecords = 1544;
const currentPage = ref(1);
const perPage = 20;
const totalPages = computed(() => Math.ceil(totalRecords / perPage));
const showing = computed(() => {
    const from = (currentPage.value - 1) * perPage + 1;
    const to = Math.min(currentPage.value * perPage, totalRecords);
    return `${from}–${to} of ${totalRecords}`;
});

// ── Selection ─────────────────────────────────────────────────────────────────
const selected = ref([]);
const allChecked = computed({
    get: () => rows.value.length > 0 && selected.value.length === rows.value.length,
    set: (val) => {
        selected.value = val ? rows.value.map(r => r.id) : [];
    },
});
const hasSelection = computed(() => selected.value.length > 0);

// ── Edit modal ────────────────────────────────────────────────────────────────
const showEditModal = ref(false);
const editForm = ref({ reason: '', status: null, amount: '' });
const editStatusOptions = [
    { label: 'Processing', value: 'processing' },
    { label: 'On Hold', value: 'on_hold' },
    { label: 'Issue', value: 'issue' },
    { label: 'Complete', value: 'complete' },
];

function openEdit(row) {
    editForm.value = {
        reason: row.reason,
        status: row.status,
        amount: row.refund_amount,
    };
    showEditModal.value = true;
}

// ── Helpers ───────────────────────────────────────────────────────────────────
function statusSeverity(status) {
    const map = {
        processing: 'info',
        on_hold: 'warn',
        issue: 'danger',
        complete: 'success',
    };
    return map[status] || 'secondary';
}

function statusLabel(status) {
    if (!status) return '—';
    return status.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
}

function resetFilters() {
    filters.value = { status: null, order_status: null, time: null, amount_type: null };
}
</script>

<template>
    <div class="card">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold">Mass Refund</h2>
        </div>

        <!-- Bulk Actions -->
        <div class="flex flex-wrap gap-2 mb-4 items-center">
            <Button
                label="Issue Refund"
                icon="pi pi-undo"
                :disabled="!hasSelection"
                severity="primary"
                size="small"
            />
            <Button
                label="Mark Refund Complete"
                icon="pi pi-check-circle"
                :disabled="!hasSelection"
                severity="success"
                size="small"
            />
            <Button
                label="Send Email"
                icon="pi pi-envelope"
                :disabled="!hasSelection"
                severity="secondary"
                size="small"
            />
            <div class="flex gap-2 items-center">
                <Select
                    v-model="bulkOrderStatus"
                    :options="bulkOrderStatusOptions"
                    optionLabel="label"
                    optionValue="value"
                    style="min-width: 140px"
                    size="small"
                />
                <Button
                    label="Update Order Status"
                    icon="pi pi-refresh"
                    :disabled="!hasSelection"
                    severity="secondary"
                    size="small"
                />
            </div>
        </div>

        <!-- Filters -->
        <div class="flex flex-wrap gap-3 mb-3 items-end">
            <div class="flex flex-col gap-1">
                <label class="text-xs text-surface-500">Status</label>
                <Select
                    v-model="filters.status"
                    :options="statusOptions"
                    optionLabel="label"
                    optionValue="value"
                    placeholder="All"
                    style="min-width: 150px"
                />
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs text-surface-500">Order status</label>
                <Select
                    v-model="filters.order_status"
                    :options="orderStatusOptions"
                    optionLabel="label"
                    optionValue="value"
                    placeholder="All"
                    style="min-width: 180px"
                />
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs text-surface-500">Time window <span class="text-surface-400">(3:00PM, WP Time Now)</span></label>
                <Select
                    v-model="filters.time"
                    :options="timeOptions"
                    optionLabel="label"
                    optionValue="value"
                    placeholder="All"
                    style="min-width: 220px"
                />
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs text-surface-500">Amount type</label>
                <Select
                    v-model="filters.amount_type"
                    :options="amountTypeOptions"
                    optionLabel="label"
                    optionValue="value"
                    placeholder="All"
                    style="min-width: 130px"
                />
            </div>
            <div class="flex gap-2 items-end">
                <Button label="Apply" icon="pi pi-search" size="small" />
                <Button label="Reset" icon="pi pi-times" text size="small" @click="resetFilters" />
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-sm">
                <thead>
                    <tr class="border-b border-surface-200 bg-surface-50">
                        <th style="width:34px" class="p-3 text-left">
                            <Checkbox v-model="allChecked" :binary="true" />
                        </th>
                        <th style="width:120px" class="p-3 text-left font-semibold text-surface-600">Order #</th>
                        <th style="width:170px" class="p-3 text-left font-semibold text-surface-600">Date Created</th>
                        <th style="width:500px" class="p-3 text-left font-semibold text-surface-600">Reason</th>
                        <th style="width:160px" class="p-3 text-left font-semibold text-surface-600">Status</th>
                        <th style="width:160px" class="p-3 text-left font-semibold text-surface-600">Amount</th>
                        <th style="width:280px" class="p-3 text-left font-semibold text-surface-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(row, i) in rows"
                        :key="row.id"
                        :class="['border-b border-surface-100', i % 2 === 1 ? 'bg-surface-50/50' : '']"
                    >
                        <!-- Checkbox -->
                        <td class="p-3">
                            <Checkbox v-model="selected" :value="row.id" />
                        </td>

                        <!-- Order # -->
                        <td class="p-3">
                            <div class="font-semibold">#{{ row.order_number }}</div>
                            <div class="text-surface-400 text-xs mt-0.5">Refund #{{ row.refund_id }}</div>
                        </td>

                        <!-- Date -->
                        <td class="p-3">
                            <div>{{ row.created_at }}</div>
                            <div class="text-surface-400 text-xs mt-0.5">Order created at: {{ row.order_created_at }}</div>
                        </td>

                        <!-- Reason -->
                        <td class="p-3">
                            <span class="text-surface-700">{{ row.reason }}</span>
                        </td>

                        <!-- Status -->
                        <td class="p-3">
                            <Tag :value="statusLabel(row.status)" :severity="statusSeverity(row.status)" />
                            <div v-if="row.status_note" class="text-xs text-surface-400 mt-1">{{ row.status_note }}</div>
                            <div class="text-xs text-surface-400 mt-1">Order status: {{ row.order_status }}</div>
                        </td>

                        <!-- Amount -->
                        <td class="p-3">
                            <div class="font-semibold">${{ row.refund_amount }} / ${{ row.order_total }}</div>
                            <div class="text-xs text-surface-400 mt-0.5">Refunded: ${{ row.refunded_so_far }} of ${{ row.order_total }}</div>
                        </td>

                        <!-- Actions -->
                        <td class="p-3">
                            <div class="flex flex-wrap gap-1">
                                <Button
                                    label="Open order"
                                    icon="pi pi-external-link"
                                    size="small"
                                    text
                                    as="a"
                                    :href="`/orders/entry/${row.order_id}`"
                                    target="_blank"
                                />
                                <Button
                                    label="Edit refund"
                                    icon="pi pi-pencil"
                                    size="small"
                                    text
                                    severity="secondary"
                                    @click="openEdit(row)"
                                />
                                <Button
                                    label="Refund"
                                    icon="pi pi-undo"
                                    size="small"
                                    severity="danger"
                                    text
                                />
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer / Pagination -->
        <div class="flex justify-between items-center mt-4 pt-3 border-t border-surface-200 text-sm text-surface-500">
            <span>Showing {{ showing }}</span>
            <div class="flex items-center gap-3">
                <span>Page {{ currentPage }} / {{ totalPages }}</span>
                <Button
                    icon="pi pi-chevron-left"
                    text
                    rounded
                    size="small"
                    :disabled="currentPage === 1"
                    @click="currentPage--"
                />
                <Button
                    icon="pi pi-chevron-right"
                    text
                    rounded
                    size="small"
                    :disabled="currentPage === totalPages"
                    @click="currentPage++"
                />
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <Dialog v-model:visible="showEditModal" header="Edit Refund" :style="{ width: '480px' }" modal>
        <div class="flex flex-col gap-4 pt-2">
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium">Reason</label>
                <Textarea v-model="editForm.reason" rows="3" />
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium">Status</label>
                <Select
                    v-model="editForm.status"
                    :options="editStatusOptions"
                    optionLabel="label"
                    optionValue="value"
                />
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium">Amount</label>
                <InputText v-model="editForm.amount" />
            </div>
        </div>
        <template #footer>
            <Button label="Cancel" text severity="secondary" @click="showEditModal = false" />
            <Button label="Save" icon="pi pi-check" @click="showEditModal = false" />
        </template>
    </Dialog>
</template>
