<script setup>
import { ref, computed } from 'vue';
import Button from 'primevue/button';
import Select from 'primevue/select';
import MultiSelect from 'primevue/multiselect';
import InputText from 'primevue/inputtext';
import Checkbox from 'primevue/checkbox';
import Dialog from 'primevue/dialog';
import Textarea from 'primevue/textarea';

// ── Filters ───────────────────────────────────────────────────────────────────
const filters = ref({ status: '', entry_id: '', order_id: '' });

const statusOptions = [
    { label: 'All', value: '' },
    { label: 'Processing', value: 'Processing' },
    { label: 'Fix Photo', value: 'Fix Photo' },
    { label: 'On Hold', value: 'On Hold' },
    { label: 'Approved', value: 'Approved' },
    { label: 'Denied', value: 'Denied' },
];

// ── AI Prompt ─────────────────────────────────────────────────────────────────
const promptSelect = ref('passport');
const promptOptions = [
    { label: 'Passport Photo', value: 'passport' },
    { label: 'Visa Photo', value: 'visa' },
    { label: 'Long Test', value: 'long_test' },
];

// ── Mock data (form #7 entries) ───────────────────────────────────────────────
const rows = ref([
    {
        id: 122751, order_id: 58210, date: '2026-03-25 14:32',
        service: 'New Passport', email: 'john.doe@email.com', note: '',
        status: 'Processing',
        original_img: 'https://placehold.co/150x150/e7f1ff/0b4aa2?text=Original',
        final_img: 'https://placehold.co/150x150/e8ffef/116329?text=Final',
    },
    {
        id: 122734, order_id: 57834, date: '2026-03-25 11:05',
        service: 'Passport Renewal', email: 'jane.smith@email.com', note: 'Rush processing',
        status: 'Fix Photo',
        original_img: 'https://placehold.co/150x150/f6f8fa/57606a?text=Original',
        final_img: '',
    },
    {
        id: 122699, order_id: 57501, date: '2026-03-24 09:20',
        service: 'Name Change', email: 'mark.jones@email.com', note: '',
        status: 'Approved',
        original_img: 'https://placehold.co/150x150/e8ffef/116329?text=Original',
        final_img: 'https://placehold.co/150x150/e8ffef/116329?text=Final',
    },
    {
        id: 122645, order_id: 57120, date: '2026-03-24 07:45',
        service: 'Child Passport', email: 'sarah.miller@email.com', note: '',
        status: 'On Hold',
        original_img: 'https://placehold.co/150x150/fff6d6/7a5a00?text=Original',
        final_img: '',
    },
    {
        id: 122612, order_id: 56980, date: '2026-03-23 17:10',
        service: 'New Passport', email: 'robert.wilson@email.com', note: 'Customer complaint',
        status: 'Denied',
        original_img: 'https://placehold.co/150x150/ffe8e8/a10f0f?text=Original',
        final_img: 'https://placehold.co/150x150/ffe8e8/a10f0f?text=Final',
    },
    {
        id: 122580, order_id: 56741, date: '2026-03-23 13:55',
        service: 'Passport Renewal', email: 'emily.brown@email.com', note: '',
        status: 'Processing',
        original_img: 'https://placehold.co/150x150/e7f1ff/0b4aa2?text=Original',
        final_img: '',
    },
]);

const totalRecords = 6344;
const currentPage = ref(1);
const perPage = 20;
const totalPages = computed(() => Math.ceil(totalRecords / perPage));
const showingText = computed(() => {
    const from = (currentPage.value - 1) * perPage + 1;
    const to   = Math.min(currentPage.value * perPage, totalRecords);
    return `Showing ${from}–${to} of ${totalRecords}`;
});

// ── Selection ─────────────────────────────────────────────────────────────────
const selected = ref([]);
const allChecked = computed({
    get:  () => rows.value.length > 0 && selected.value.length === rows.value.length,
    set: (val) => { selected.value = val ? rows.value.map(r => r.id) : []; },
});
const hasSelection = computed(() => selected.value.length > 0);

// ── Status badge helpers ──────────────────────────────────────────────────────
const statusStyles = {
    'Processing': { background: '#e7f1ff', border: '#8cb6ff', color: '#0b4aa2' },
    'Approved':   { background: '#e8ffef', border: '#8fe0a6', color: '#116329' },
    'Denied':     { background: '#ffe8e8', border: '#ff9b9b', color: '#a10f0f' },
    'On Hold':    { background: '#fff6d6', border: '#ffd36b', color: '#7a5a00' },
    'Fix Photo':  { background: '#f6f8fa', border: '#d0d7de', color: '#57606a' },
};
function badgeStyle(status) {
    const s = statusStyles[status] || statusStyles['Fix Photo'];
    return `display:inline-block;padding:4px 10px;border-radius:999px;border:1px solid ${s.border};background:${s.background};color:${s.color};font-size:12px;font-weight:700;line-height:1.2`;
}

// ── Deny Modal ────────────────────────────────────────────────────────────────
const showDenyModal = ref(false);
const denyTargetId = ref(null);
const denyReasons = ref([]);
const denyCustom = ref('');
const denyReasonOptions = [
    { label: 'Showing Teeth', value: 'Showing Teeth' },
    { label: 'Smile Too Big', value: 'Smile Too Big' },
    { label: 'No Glasses', value: 'No Glasses' },
    { label: 'No Hats / Headgear / Earbuds', value: 'No Hats / Headgear / Earbuds' },
    { label: 'Eyes Not Clearly Visible', value: 'Eyes Not Clearly Visible' },
    { label: 'Face the Camera', value: 'Face the Camera' },
    { label: 'Hair Obstructing Face', value: 'Hair Obstructing Face' },
    { label: 'Shadows / Too Dark', value: 'Shadows / Too Dark' },
    { label: 'Shadows on Face', value: 'Shadows on Face' },
    { label: 'Too Bright / Unclear', value: 'Too Bright / Unclear' },
    { label: 'Blurry', value: 'Blurry' },
    { label: 'Pixelated', value: 'Pixelated' },
    { label: 'Picture of a Picture', value: 'Picture of a Picture' },
    { label: 'Too Close / Picture Cut Off', value: 'Too Close / Picture Cut Off' },
    { label: 'Clothing Blending Into Background', value: 'Clothing Blending Into Background' },
    { label: 'Objects in the Photo', value: 'Objects in the Photo' },
    { label: 'Custom…', value: '__custom__' },
];
const showCustomDeny = computed(() => denyReasons.value.includes('__custom__'));
const canDeny = computed(() => denyReasons.value.length > 0);

function openDeny(id) {
    denyTargetId.value = id;
    denyReasons.value = [];
    denyCustom.value = '';
    showDenyModal.value = true;
}

// ── Compare Modal ─────────────────────────────────────────────────────────────
const showCompareModal = ref(false);
const compareOriginal = ref('');
const compareFinal = ref('');

function openCompare(row) {
    compareOriginal.value = row.original_img;
    compareFinal.value = row.final_img;
    showCompareModal.value = true;
}

// ── Upload Modal ──────────────────────────────────────────────────────────────
const showUploadModal = ref(false);
const uploadTargetId = ref(null);
const uploadFile = ref(null);
const canUpload = computed(() => !!uploadFile.value);

function openUpload(id) {
    uploadTargetId.value = id;
    uploadFile.value = null;
    showUploadModal.value = true;
}

// ── Edit Meta Modal ───────────────────────────────────────────────────────────
const showEditMetaModal = ref(false);
const editMetaForm = ref({ status: '', notes: '' });
const editMetaStatusOptions = [
    { label: '— Select —', value: '' },
    { label: 'Processing', value: 'Processing' },
    { label: 'Fix Photo', value: 'Fix Photo' },
    { label: 'On Hold', value: 'On Hold' },
    { label: 'Approved', value: 'Approved' },
    { label: 'Denied', value: 'Denied' },
];

function openEditMeta(row) {
    editMetaForm.value = { status: row.status, notes: '' };
    showEditMetaModal.value = true;
}

function resetFilters() {
    filters.value = { status: '', entry_id: '', order_id: '' };
}
</script>

<template>
    <div class="card">

        <!-- Header -->
        <div class="flex justify-between items-center gap-3 mb-4 flex-wrap">
            <h2 class="text-2xl font-semibold">AI Photos Review</h2>
            <div class="flex gap-2 items-center flex-wrap">
                <Select
                    v-model="promptSelect"
                    :options="promptOptions"
                    optionLabel="label"
                    optionValue="value"
                    style="min-width: 220px"
                />
                <Button
                    label="Edit (AI fix photos)"
                    icon="pi pi-sparkles"
                    :disabled="!hasSelection"
                    severity="secondary"
                    size="small"
                />
                <Button
                    label="Approve"
                    icon="pi pi-check"
                    :disabled="!hasSelection"
                    severity="success"
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
                    style="min-width: 160px"
                    @change="() => {}"
                />
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs text-surface-500">Entry ID</label>
                <InputText
                    v-model="filters.entry_id"
                    placeholder="e.g. 122751"
                    inputmode="numeric"
                    style="width: 150px"
                />
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs text-surface-500">Order ID</label>
                <InputText
                    v-model="filters.order_id"
                    placeholder="e.g. 58210"
                    inputmode="numeric"
                    style="width: 150px"
                />
            </div>
            <div class="flex gap-2 items-end">
                <Button label="Apply" icon="pi pi-search" size="small" />
                <Button label="Reset" icon="pi pi-times" text size="small" @click="resetFilters" />
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse" style="font-size:14px">
                <thead>
                    <tr class="border-b border-surface-200 bg-surface-50">
                        <th style="width:34px;padding:10px 15px;text-align:left">
                            <Checkbox v-model="allChecked" :binary="true" />
                        </th>
                        <th style="width:120px;padding:10px 15px;text-align:left;font-weight:600" class="text-surface-600">Order #</th>
                        <th style="width:170px;padding:10px 15px;text-align:left;font-weight:600" class="text-surface-600">Date Created</th>
                        <th style="padding:10px 15px;text-align:left;font-weight:600" class="text-surface-600">Service</th>
                        <th style="width:200px;padding:10px 15px;text-align:left;font-weight:600" class="text-surface-600">Status</th>
                        <th style="padding:10px 15px;text-align:left;font-weight:600" class="text-surface-600">Original image</th>
                        <th style="padding:10px 15px;text-align:left;font-weight:600" class="text-surface-600">Final image</th>
                        <th style="width:280px;padding:10px 15px;text-align:left;font-weight:600" class="text-surface-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(row, i) in rows"
                        :key="row.id"
                        :class="['border-b border-surface-100', i % 2 === 1 ? 'bg-surface-50/50' : '']"
                    >
                        <!-- Checkbox -->
                        <td style="padding:10px 15px">
                            <Checkbox v-model="selected" :value="row.id" />
                        </td>

                        <!-- Order # -->
                        <td style="padding:10px 15px">
                            <b>{{ row.order_id }}</b>
                            <div style="font-size:12px;color:#888;margin-top:2px">Entry #{{ row.id }}</div>
                        </td>

                        <!-- Date -->
                        <td style="padding:10px 15px">{{ row.date }}</td>

                        <!-- Service -->
                        <td style="padding:10px 15px">
                            <b>{{ row.service }}</b><br>
                            <span style="font-size:12px;color:#888">{{ row.email }}</span>
                            <div
                                v-if="row.note"
                                style="margin-top:6px;font-size:12px;line-height:1.35;padding:8px 10px;border:1px solid #eee;border-radius:8px;background:#fafafa;white-space:pre-line"
                            >{{ row.note }}</div>
                        </td>

                        <!-- Status -->
                        <td style="padding:10px 15px">
                            <span :style="badgeStyle(row.status)">{{ row.status }}</span>
                            <div style="margin-top:8px">
                                <Button
                                    label="Edit"
                                    size="small"
                                    severity="secondary"
                                    text
                                    style="font-size:12px;padding:4px 8px"
                                    @click="openEditMeta(row)"
                                />
                            </div>
                        </td>

                        <!-- Original image -->
                        <td style="padding:10px 15px">
                            <img
                                v-if="row.original_img"
                                :src="row.original_img"
                                alt="Original Image"
                                style="max-width:150px;max-height:150px;object-fit:contain;border:1px solid #ddd;border-radius:8px;display:block"
                            >
                        </td>

                        <!-- Final image -->
                        <td style="padding:10px 15px">
                            <div v-if="row.final_img">
                                <img
                                    :src="row.final_img"
                                    alt="Final Image"
                                    style="max-width:150px;max-height:150px;object-fit:contain;border:1px solid #ddd;border-radius:8px;display:block"
                                >
                                <div style="margin-top:8px;display:flex;gap:6px;flex-wrap:wrap">
                                    <Button label="Compare" size="small" severity="secondary" text style="font-size:12px;padding:4px 8px" @click="openCompare(row)" />
                                    <Button label="Upload" size="small" severity="secondary" text style="font-size:12px;padding:4px 8px" @click="openUpload(row.id)" />
                                </div>
                            </div>
                            <div v-else>
                                <Button label="Upload" size="small" severity="secondary" text style="font-size:12px;padding:4px 8px" @click="openUpload(row.id)" />
                            </div>
                        </td>

                        <!-- Actions -->
                        <td style="padding:10px 15px">
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
                                    label="Deny"
                                    icon="pi pi-times"
                                    size="small"
                                    text
                                    severity="danger"
                                    @click="openDeny(row.id)"
                                />
                                <Button
                                    label="Approve"
                                    icon="pi pi-check"
                                    size="small"
                                    text
                                    severity="success"
                                />
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer / Pagination -->
        <div class="flex justify-between items-center mt-4 pt-3 border-t border-surface-200 text-sm text-surface-500">
            <span>{{ showingText }}</span>
            <div class="flex items-center gap-3">
                <span style="font-size:12px;color:#444">Page {{ currentPage }} / {{ totalPages }}</span>
                <Button icon="pi pi-chevron-left" text rounded size="small" :disabled="currentPage === 1" @click="currentPage--" />
                <Button icon="pi pi-chevron-right" text rounded size="small" :disabled="currentPage === totalPages" @click="currentPage++" />
            </div>
        </div>
    </div>

    <!-- ── Deny Modal ─────────────────────────────────────────────────────── -->
    <Dialog v-model:visible="showDenyModal" header="Deny photos" :style="{ width: '480px' }" modal>
        <div class="flex flex-col gap-4 pt-2">
            <p class="text-surface-600">Select reasons.</p>
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium">Reasons</label>
                <MultiSelect
                    v-model="denyReasons"
                    :options="denyReasonOptions"
                    optionLabel="label"
                    optionValue="value"
                    placeholder="Select reasons…"
                    display="chip"
                    style="width:100%"
                />
            </div>
            <div v-if="showCustomDeny" class="flex flex-col gap-1">
                <label class="text-sm font-medium">Custom reason</label>
                <InputText v-model="denyCustom" placeholder="Type custom reason…" />
            </div>
        </div>
        <template #footer>
            <Button label="Cancel" text severity="secondary" @click="showDenyModal = false" />
            <Button label="Deny" severity="danger" icon="pi pi-times" :disabled="!canDeny" @click="showDenyModal = false" />
        </template>
    </Dialog>

    <!-- ── Compare Modal ──────────────────────────────────────────────────── -->
    <Dialog v-model:visible="showCompareModal" header="Compare images" :style="{ width: '90vw', maxWidth: '1200px' }" modal>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:10px">
            <div>
                <div style="font-size:12px;color:#888;margin-bottom:6px">Original</div>
                <img
                    :src="compareOriginal"
                    alt="Original"
                    style="width:100%;height:auto;max-height:calc(90vh - 160px);object-fit:contain;border:1px solid #ddd;border-radius:12px;background:#fafafa"
                >
            </div>
            <div>
                <div style="font-size:12px;color:#888;margin-bottom:6px">Final</div>
                <img
                    :src="compareFinal"
                    alt="Final"
                    style="width:100%;height:auto;max-height:calc(90vh - 160px);object-fit:contain;border:1px solid #ddd;border-radius:12px;background:#fafafa"
                >
            </div>
        </div>
        <template #footer>
            <Button label="Close" text severity="secondary" @click="showCompareModal = false" />
        </template>
    </Dialog>

    <!-- ── Upload Final Photo Modal ───────────────────────────────────────── -->
    <Dialog v-model:visible="showUploadModal" header="Upload final photo" :style="{ width: '520px' }" modal>
        <div class="flex flex-col gap-4 pt-2">
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium">File</label>
                <input
                    type="file"
                    accept="image/*"
                    style="width:100%;border:1px solid #d0d7de;border-radius:8px;padding:8px 10px;font-size:14px"
                    @change="uploadFile = $event.target.files[0]"
                >
                <span class="text-xs text-surface-400">Uploads and sets as <b>final</b> image for this entry.</span>
            </div>
        </div>
        <template #footer>
            <Button label="Cancel" text severity="secondary" @click="showUploadModal = false" />
            <Button label="Update" icon="pi pi-upload" :disabled="!canUpload" @click="showUploadModal = false" />
        </template>
    </Dialog>

    <!-- ── Edit Status & Notes Modal ──────────────────────────────────────── -->
    <Dialog v-model:visible="showEditMetaModal" header="Edit status & notes" :style="{ width: '560px' }" modal>
        <div class="flex flex-col gap-4 pt-2">
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium">Status</label>
                <Select
                    v-model="editMetaForm.status"
                    :options="editMetaStatusOptions"
                    optionLabel="label"
                    optionValue="value"
                    style="width:100%"
                />
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium">Notes</label>
                <Textarea v-model="editMetaForm.notes" rows="4" placeholder="Type notes…" />
            </div>
        </div>
        <template #footer>
            <Button label="Cancel" text severity="secondary" @click="showEditMetaModal = false" />
            <Button label="Update" icon="pi pi-check" @click="showEditMetaModal = false" />
        </template>
    </Dialog>
</template>
