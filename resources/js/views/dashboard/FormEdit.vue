<script setup>
import { ref, computed, onMounted, nextTick } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import Select from 'primevue/select';
import Tabs from 'primevue/tabs';
import TabList from 'primevue/tablist';
import Tab from 'primevue/tab';
import TabPanels from 'primevue/tabpanels';
import TabPanel from 'primevue/tabpanel';
import InputSwitch from 'primevue/inputswitch';
import ColorPicker from 'primevue/colorpicker';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';

const route = useRoute();
const router = useRouter();
const confirm = useConfirm();
const toast = useToast();

const formId = computed(() => route.params.id);
const loading = ref(true);
const saving = ref(false);
const errors = ref({});

const form = ref(null);
const generalForm = ref({});
const fields = ref([]);
const pages = ref([{ num: 1, title: 'Page 1' }]);
const statuses = ref([]);
const newStatus = ref({ code: '', value: '', description: '', color: '#2563eb' });
const savingStatusId = ref(null);

// Inline editing
const expandedFieldId = ref(null);
const savingFieldId = ref(null);

// Drag state
const dragSource = ref(null); // { type: 'sidebar', fieldType: '...' } or { type: 'field', field: {...} }
const dropTarget = ref(null); // { pageNum, afterFieldId }

// Sidebar field types
const fieldTypes = [
    { label: 'Text', value: 'text', icon: 'pi-align-left' },
    { label: 'Textarea', value: 'textarea', icon: 'pi-align-justify' },
    { label: 'Email', value: 'email', icon: 'pi-envelope' },
    { label: 'Number', value: 'number', icon: 'pi-hashtag' },
    { label: 'Phone', value: 'phone', icon: 'pi-phone' },
    { label: 'Date', value: 'date', icon: 'pi-calendar' },
    { label: 'Select', value: 'select', icon: 'pi-list' },
    { label: 'Checkbox', value: 'checkbox', icon: 'pi-check-square' },
    { label: 'Radio', value: 'radio', icon: 'pi-circle' },
    { label: 'File', value: 'file', icon: 'pi-upload' },
    { label: 'Hidden', value: 'hidden', icon: 'pi-eye-slash' },
    { label: 'Divider', value: 'divider', icon: 'pi-minus' },
    { label: 'End Divider', value: 'end_divider', icon: 'pi-minus' },
    { label: 'HTML', value: 'html', icon: 'pi-code' },
];

const statusOptions = [
    { label: 'Active', value: 'active' },
    { label: 'Draft', value: 'draft' },
    { label: 'Inactive', value: 'inactive' },
];

onMounted(() => { loadForm(); });

async function loadForm() {
    loading.value = true;
    try {
        const { data } = await window.axios.get(`/forms/${formId.value}`);
        form.value = data.form;
        generalForm.value = {
            name: data.form.name || '',
            form_key: data.form.form_key || '',
            description: data.form.description || '',
            status: data.form.status || '',
            logged_in: !!data.form.logged_in,
            editable: !!data.form.editable,
            is_template: !!data.form.is_template,
        };
        fields.value = data.form.fields || [];
        statuses.value = data.form.statuses || [];
        loadPageTitles(data.form.options);
        rebuildPages();
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Failed to load form', life: 5000 });
        router.push({ name: 'forms' });
    } finally {
        loading.value = false;
    }
}

function loadPageTitles(options) {
    try {
        const parsed = typeof options === 'string' ? JSON.parse(options) : options;
        if (parsed?.page_titles) {
            // Will be merged in rebuildPages
            pages._savedTitles = parsed.page_titles;
        }
    } catch { /* ignore */ }
}

function rebuildPages() {
    const maxPage = fields.value.reduce((max, f) => Math.max(max, f.page_num || 1), 1);
    const savedTitles = pages._savedTitles || {};
    pages.value = Array.from({ length: maxPage }, (_, i) => ({
        num: i + 1,
        title: savedTitles[i + 1] || pages.value.find(p => p.num === i + 1)?.title || `Page ${i + 1}`,
    }));
}

function fieldsForPage(pageNum) {
    return fields.value
        .filter(f => f.page_num === pageNum)
        .sort((a, b) => a.field_order - b.field_order);
}

// ── General tab ──
async function saveGeneral() {
    saving.value = true;
    errors.value = {};
    try {
        const { data } = await window.axios.put(`/forms/${formId.value}`, generalForm.value);
        form.value = { ...form.value, ...data.form };
        toast.add({ severity: 'success', summary: 'Form updated', life: 3000 });
    } catch (e) {
        if (e.response?.status === 422) {
            errors.value = e.response.data.errors || {};
        } else {
            toast.add({ severity: 'error', summary: e.response?.data?.message || 'Failed to save', life: 5000 });
        }
    } finally {
        saving.value = false;
    }
}

// ── Page management ──
function addPage() {
    const next = pages.value.length + 1;
    pages.value.push({ num: next, title: `Page ${next}` });
    savePageTitles();
}

function removePage(pageNum) {
    if (fieldsForPage(pageNum).length > 0) {
        toast.add({ severity: 'warn', summary: 'Remove all fields from this page first', life: 3000 });
        return;
    }
    pages.value = pages.value.filter(p => p.num !== pageNum);
    pages.value.forEach((p, i) => { p.num = i + 1; });
    fields.value.forEach(f => { if (f.page_num > pageNum) f.page_num--; });
    savePageTitles();
}

async function savePageTitles() {
    const titles = {};
    pages.value.forEach(p => { titles[p.num] = p.title; });
    const opts = form.value.options ? (typeof form.value.options === 'string' ? JSON.parse(form.value.options) : form.value.options) : {};
    opts.page_titles = titles;
    try {
        await window.axios.put(`/forms/${formId.value}`, { options: JSON.stringify(opts) });
    } catch { /* silent */ }
}

let pageTitleTimer = null;
function onPageTitleInput() {
    clearTimeout(pageTitleTimer);
    pageTitleTimer = setTimeout(() => savePageTitles(), 600);
}

// ── Sidebar drag → drop to create field ──
function onSidebarDragStart(e, fieldType) {
    dragSource.value = { type: 'sidebar', fieldType };
    e.dataTransfer.effectAllowed = 'copy';
    e.dataTransfer.setData('text/plain', fieldType);
}

// ── Existing field drag to reorder ──
function onFieldDragStart(e, field) {
    dragSource.value = { type: 'field', field };
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/plain', String(field.id));
}

function onDragOver(e, pageNum, afterFieldId) {
    e.preventDefault();
    e.dataTransfer.dropEffect = dragSource.value?.type === 'sidebar' ? 'copy' : 'move';
    dropTarget.value = { pageNum, afterFieldId };
}

function onDragLeave() {
    dropTarget.value = null;
}

function isDropTarget(pageNum, afterFieldId) {
    return dropTarget.value?.pageNum === pageNum && dropTarget.value?.afterFieldId === afterFieldId;
}

async function onDrop(e, pageNum, afterFieldId) {
    e.preventDefault();
    e.stopPropagation();
    dropTarget.value = null;

    if (!dragSource.value) return;

    if (dragSource.value.type === 'sidebar') {
        await addFieldFromSidebar(dragSource.value.fieldType, pageNum, afterFieldId);
    } else if (dragSource.value.type === 'field') {
        await reorderField(dragSource.value.field, pageNum, afterFieldId);
    }

    dragSource.value = null;
}

function onDropEnd() {
    dragSource.value = null;
    dropTarget.value = null;
}

async function addFieldFromSidebar(type, pageNum, afterFieldId) {
    const typeLabel = fieldTypes.find(t => t.value === type)?.label || type;
    const pageFields = fieldsForPage(pageNum);
    let order;
    if (afterFieldId === null) {
        // Dropped at top or empty page
        order = pageFields.length > 0 ? pageFields[0].field_order : 1;
        // Shift existing down
        pageFields.forEach(f => { f.field_order++; });
    } else {
        const afterField = pageFields.find(f => f.id === afterFieldId);
        order = afterField ? afterField.field_order + 1 : pageFields.length + 1;
        // Shift fields after this one
        pageFields.filter(f => f.field_order >= order).forEach(f => { f.field_order++; });
    }

    try {
        const { data } = await window.axios.post(`/forms/${formId.value}/fields`, {
            name: typeLabel,
            type,
            page_num: pageNum,
            field_order: order,
            required: 0,
        });
        fields.value.push(data.field);
        rebuildPages();
        // Auto-expand the new field for editing
        await nextTick();
        expandedFieldId.value = data.field.id;
        saveFieldOrder();
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Failed to add field', life: 5000 });
    }
}

async function reorderField(field, toPageNum, afterFieldId) {
    // Remove from old position
    field.page_num = toPageNum;
    const pageFields = fieldsForPage(toPageNum).filter(f => f.id !== field.id);

    let insertIdx;
    if (afterFieldId === null) {
        insertIdx = 0;
    } else {
        insertIdx = pageFields.findIndex(f => f.id === afterFieldId) + 1;
    }
    pageFields.splice(insertIdx, 0, field);

    pageFields.forEach((f, i) => {
        const ref = fields.value.find(ff => ff.id === f.id);
        if (ref) {
            ref.field_order = i + 1;
            ref.page_num = toPageNum;
        }
    });

    saveFieldOrder();
}

async function saveFieldOrder() {
    const payload = fields.value.map(f => ({
        id: f.id,
        field_order: f.field_order,
        page_num: f.page_num,
    }));
    try {
        await window.axios.post(`/forms/${formId.value}/fields/reorder`, { fields: payload });
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Failed to save order', life: 5000 });
    }
}

// ── Inline field editing ──
function toggleField(fieldId) {
    expandedFieldId.value = expandedFieldId.value === fieldId ? null : fieldId;
}

let fieldSaveTimer = null;
function onFieldInput(field) {
    clearTimeout(fieldSaveTimer);
    fieldSaveTimer = setTimeout(() => saveFieldInline(field), 600);
}

async function saveFieldInline(field) {
    savingFieldId.value = field.id;
    try {
        const { data } = await window.axios.put(`/form-fields/${field.id}`, {
            name: field.name,
            field_key: field.field_key,
            type: field.type,
            description: field.description || '',
            required: field.required || 0,
            options: field.options || '',
            page_num: field.page_num,
        });
        // Merge response back
        const idx = fields.value.findIndex(f => f.id === field.id);
        if (idx !== -1) {
            fields.value[idx] = { ...fields.value[idx], ...data.field };
        }
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Failed to save field', life: 3000 });
    } finally {
        savingFieldId.value = null;
    }
}

function confirmDeleteField(field) {
    confirm.require({
        message: `Delete field "${field.name}"?`,
        header: 'Delete Field',
        icon: 'pi pi-trash',
        rejectLabel: 'Cancel',
        acceptLabel: 'Delete',
        acceptClass: 'p-button-danger',
        accept: () => deleteField(field.id),
    });
}

async function deleteField(fieldId) {
    try {
        await window.axios.delete(`/form-fields/${fieldId}`);
        fields.value = fields.value.filter(f => f.id !== fieldId);
        if (expandedFieldId.value === fieldId) expandedFieldId.value = null;
        rebuildPages();
        toast.add({ severity: 'success', summary: 'Field deleted', life: 3000 });
    } catch (e) {
        toast.add({ severity: 'error', summary: e.response?.data?.message || 'Failed to delete', life: 5000 });
    }
}

// ── Statuses tab ──
async function addStatus() {
    if (!newStatus.value.code || !newStatus.value.value) {
        toast.add({ severity: 'warn', summary: 'Code and Value are required', life: 3000 });
        return;
    }
    try {
        const { data } = await window.axios.post(`/forms/${formId.value}/statuses`, {
            ...newStatus.value,
            color: newStatus.value.color?.startsWith('#') ? newStatus.value.color : `#${newStatus.value.color}`,
        });
        statuses.value.push(data.status);
        newStatus.value = { code: '', value: '', description: '', color: '#2563eb' };
        toast.add({ severity: 'success', summary: 'Status added', life: 3000 });
    } catch (e) {
        toast.add({ severity: 'error', summary: e.response?.data?.message || 'Failed to add status', life: 5000 });
    }
}

let statusSaveTimer = null;
function onStatusInput(status) {
    clearTimeout(statusSaveTimer);
    statusSaveTimer = setTimeout(() => saveStatus(status), 600);
}

async function saveStatus(status) {
    savingStatusId.value = status.id;
    try {
        const { data } = await window.axios.put(`/form-statuses/${status.id}`, {
            code: status.code,
            value: status.value,
            description: status.description,
            color: status.color?.startsWith('#') ? status.color : `#${status.color}`,
        });
        const idx = statuses.value.findIndex(s => s.id === status.id);
        if (idx !== -1) statuses.value[idx] = { ...statuses.value[idx], ...data.status };
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Failed to save status', life: 3000 });
    } finally {
        savingStatusId.value = null;
    }
}

function confirmDeleteStatus(status) {
    confirm.require({
        message: `Delete status "${status.value}"?`,
        header: 'Delete Status',
        icon: 'pi pi-trash',
        rejectLabel: 'Cancel',
        acceptLabel: 'Delete',
        acceptClass: 'p-button-danger',
        accept: () => deleteStatus(status.id),
    });
}

async function deleteStatus(statusId) {
    try {
        await window.axios.delete(`/form-statuses/${statusId}`);
        statuses.value = statuses.value.filter(s => s.id !== statusId);
        toast.add({ severity: 'success', summary: 'Status deleted', life: 3000 });
    } catch (e) {
        toast.add({ severity: 'error', summary: e.response?.data?.message || 'Failed to delete', life: 5000 });
    }
}
</script>

<template>
    <div class="card" v-if="!loading && form">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center gap-3">
                <Button icon="pi pi-arrow-left" text rounded @click="router.push({ name: 'forms' })" />
                <h2 class="text-2xl font-semibold">{{ form.name || 'Untitled Form' }}</h2>
            </div>
            <Button label="Save" icon="pi pi-check" :loading="saving" @click="saveGeneral" />
        </div>

        <Tabs value="general">
            <TabList>
                <Tab value="general">General</Tab>
                <Tab value="edit">Edit</Tab>
                <Tab value="statuses">Statuses</Tab>
            </TabList>
            <TabPanels>
                <!-- General Tab -->
                <TabPanel value="general">
                    <div class="flex flex-col gap-4 max-w-2xl pt-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Name</label>
                            <InputText v-model="generalForm.name" class="w-full" :invalid="!!errors.name" />
                            <small v-if="errors.name" class="text-red-500">{{ errors.name[0] }}</small>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Form Key</label>
                            <InputText v-model="generalForm.form_key" class="w-full" :invalid="!!errors.form_key" />
                            <small v-if="errors.form_key" class="text-red-500">{{ errors.form_key[0] }}</small>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Description</label>
                            <Textarea v-model="generalForm.description" class="w-full" rows="3" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Status</label>
                            <Select v-model="generalForm.status" :options="statusOptions" optionLabel="label" optionValue="value" placeholder="Select status" class="w-full" showClear />
                        </div>
                        <div class="flex gap-6">
                            <div class="flex items-center gap-2">
                                <InputSwitch v-model="generalForm.logged_in" />
                                <label class="text-sm">Requires Login</label>
                            </div>
                            <div class="flex items-center gap-2">
                                <InputSwitch v-model="generalForm.editable" />
                                <label class="text-sm">Editable</label>
                            </div>
                            <div class="flex items-center gap-2">
                                <InputSwitch v-model="generalForm.is_template" />
                                <label class="text-sm">Template</label>
                            </div>
                        </div>
                    </div>
                </TabPanel>

                <!-- Edit Tab (Form Builder) -->
                <TabPanel value="edit">
                    <div class="flex gap-4 pt-4" style="min-height: 500px;">
                        <!-- Left Sidebar: Field Types -->
                        <div class="w-52 flex-shrink-0">
                            <div class="sticky top-4">
                                <h3 class="text-xs font-semibold uppercase text-surface-500 mb-3 tracking-wide">Standard Fields</h3>
                                <div class="grid grid-cols-2 gap-1.5">
                                    <div
                                        v-for="ft in fieldTypes"
                                        :key="ft.value"
                                        class="flex flex-col items-center gap-1 p-2 rounded-lg border border-surface-200 dark:border-surface-700 cursor-grab hover:border-primary-400 hover:bg-primary-50 dark:hover:bg-primary-950 transition-colors text-center select-none"
                                        draggable="true"
                                        @dragstart="onSidebarDragStart($event, ft.value)"
                                        @dragend="onDropEnd"
                                    >
                                        <i :class="'pi ' + ft.icon" class="text-sm text-surface-500"></i>
                                        <span class="text-xs font-medium text-surface-700 dark:text-surface-300">{{ ft.label }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Builder Area -->
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-sm text-surface-500">Drag fields from the left into pages below</span>
                                <Button label="Add Page" icon="pi pi-plus" text size="small" @click="addPage" />
                            </div>

                            <div v-for="page in pages" :key="page.num" class="mb-6">
                                <!-- Page header -->
                                <div class="flex items-center gap-2 mb-2">
                                    <input
                                        v-model="page.title"
                                        class="text-lg font-medium bg-transparent border-0 border-b border-transparent hover:border-surface-300 focus:border-primary-400 focus:outline-none py-1 px-0 w-full transition-colors"
                                        @input="onPageTitleInput"
                                    />
                                    <Button
                                        v-if="pages.length > 1"
                                        icon="pi pi-times"
                                        text rounded size="small" severity="danger"
                                        @click="removePage(page.num)"
                                    />
                                </div>

                                <!-- Drop zone -->
                                <div
                                    class="border-2 border-dashed rounded-lg p-2 min-h-24 transition-colors"
                                    :class="isDropTarget(page.num, null) && fieldsForPage(page.num).length === 0
                                        ? 'border-primary-400 bg-primary-50 dark:bg-primary-950'
                                        : 'border-surface-200 dark:border-surface-700'"
                                    @dragover="onDragOver($event, page.num, null)"
                                    @dragleave="onDragLeave"
                                    @drop="onDrop($event, page.num, null)"
                                >
                                    <!-- Empty state -->
                                    <div v-if="fieldsForPage(page.num).length === 0" class="text-center text-surface-400 py-6 pointer-events-none">
                                        <i class="pi pi-arrow-down text-xl mb-2 block"></i>
                                        Drop fields here
                                    </div>

                                    <!-- Fields -->
                                    <template v-for="field in fieldsForPage(page.num)" :key="field.id">
                                        <!-- Drop indicator line above -->
                                        <div
                                            v-if="isDropTarget(page.num, field.id) && dragSource?.type === 'sidebar'"
                                            class="h-1 bg-primary-400 rounded-full mx-2 my-1"
                                        ></div>

                                        <!-- Field card -->
                                        <div
                                            class="group rounded-lg border mb-1.5 transition-all"
                                            :class="expandedFieldId === field.id
                                                ? 'border-primary-400 bg-surface-0 dark:bg-surface-900 shadow-sm'
                                                : 'border-surface-200 dark:border-surface-700 bg-surface-50 dark:bg-surface-800 hover:border-primary-300'"
                                            draggable="true"
                                            @dragstart="onFieldDragStart($event, field)"
                                            @dragover="onDragOver($event, page.num, field.id)"
                                            @dragleave="onDragLeave"
                                            @drop="onDrop($event, page.num, field.id)"
                                            @dragend="onDropEnd"
                                        >
                                            <!-- Collapsed: summary row -->
                                            <div
                                                class="flex items-center gap-2 px-3 py-2.5 cursor-pointer select-none"
                                                @click="toggleField(field.id)"
                                            >
                                                <i class="pi pi-bars text-surface-400 cursor-grab"></i>
                                                <span class="font-medium text-sm flex-1 truncate">{{ field.name }}</span>
                                                <span class="text-xs text-surface-400 font-mono">{{ field.field_key }}</span>
                                                <span class="text-xs text-surface-400">(ID {{ field.id }})</span>
                                                <span class="text-[10px] uppercase tracking-wider font-semibold px-1.5 py-0.5 rounded bg-surface-200 dark:bg-surface-700 text-surface-600 dark:text-surface-400">{{ field.type }}</span>
                                                <span v-if="field.required" class="text-red-500 text-xs">*</span>
                                                <i v-if="savingFieldId === field.id" class="pi pi-spin pi-spinner text-xs text-primary-500"></i>
                                                <Button icon="pi pi-trash" text rounded size="small" severity="danger" class="opacity-0 group-hover:opacity-100 transition-opacity" @click.stop="confirmDeleteField(field)" />
                                            </div>

                                            <!-- Expanded: inline edit -->
                                            <div v-if="expandedFieldId === field.id" class="px-3 pb-3 border-t border-surface-200 dark:border-surface-700">
                                                <div class="grid grid-cols-2 gap-3 pt-3">
                                                    <div>
                                                        <label class="block text-xs font-medium text-surface-500 mb-1">Title</label>
                                                        <InputText v-model="field.name" class="w-full" size="small" @input="onFieldInput(field)" />
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-surface-500 mb-1">Code</label>
                                                        <InputText v-model="field.field_key" class="w-full" size="small" @input="onFieldInput(field)" />
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-surface-500 mb-1">Type</label>
                                                        <Select
                                                            v-model="field.type"
                                                            :options="fieldTypes"
                                                            optionLabel="label"
                                                            optionValue="value"
                                                            class="w-full"
                                                            size="small"
                                                            @change="onFieldInput(field)"
                                                        />
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-surface-500 mb-1">Page</label>
                                                        <Select
                                                            v-model="field.page_num"
                                                            :options="pages.map(p => ({ label: p.title, value: p.num }))"
                                                            optionLabel="label"
                                                            optionValue="value"
                                                            class="w-full"
                                                            size="small"
                                                            @change="() => { onFieldInput(field); saveFieldOrder(); }"
                                                        />
                                                    </div>
                                                    <div class="col-span-2" v-if="['select','radio','checkbox'].includes(field.type)">
                                                        <label class="block text-xs font-medium text-surface-500 mb-1">Options (JSON array or comma-separated)</label>
                                                        <InputText v-model="field.options" class="w-full" size="small" @input="onFieldInput(field)" />
                                                    </div>
                                                    <div class="col-span-2">
                                                        <label class="block text-xs font-medium text-surface-500 mb-1">Description</label>
                                                        <InputText v-model="field.description" class="w-full" size="small" @input="onFieldInput(field)" />
                                                    </div>
                                                    <div class="col-span-2 flex items-center gap-2">
                                                        <InputSwitch
                                                            :modelValue="!!field.required"
                                                            @update:modelValue="v => { field.required = v ? 1 : 0; onFieldInput(field); }"
                                                        />
                                                        <label class="text-xs">Required</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </TabPanel>

                <!-- Statuses Tab -->
                <TabPanel value="statuses">
                    <div class="pt-4 max-w-4xl">
                        <!-- Add new status -->
                        <div class="flex gap-2 items-end mb-6">
                            <div>
                                <label class="block text-xs font-medium mb-1">Code</label>
                                <InputText v-model="newStatus.code" placeholder="e.g. on-hold" size="small" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium mb-1">Value</label>
                                <InputText v-model="newStatus.value" placeholder="e.g. On Hold" size="small" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium mb-1">Description</label>
                                <InputText v-model="newStatus.description" placeholder="Optional" size="small" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium mb-1">Color</label>
                                <ColorPicker v-model="newStatus.color" format="hex" />
                            </div>
                            <Button label="Add" icon="pi pi-plus" size="small" @click="addStatus" />
                        </div>

                        <!-- Status list -->
                        <div v-if="statuses.length === 0" class="text-surface-400 text-sm py-4">
                            No statuses defined for this form.
                        </div>
                        <div v-else class="flex flex-col gap-2">
                            <div
                                v-for="status in statuses"
                                :key="status.id"
                                class="flex items-center gap-3 px-3 py-2 rounded-lg border border-surface-200 dark:border-surface-700 group"
                            >
                                <div class="w-6 h-6 rounded-full flex-shrink-0 border border-surface-300" :style="{ backgroundColor: status.color || '#6b7280' }"></div>
                                <div class="flex-1 grid grid-cols-4 gap-2 items-center">
                                    <InputText v-model="status.code" size="small" @input="onStatusInput(status)" />
                                    <InputText v-model="status.value" size="small" @input="onStatusInput(status)" />
                                    <InputText v-model="status.description" size="small" placeholder="Description" @input="onStatusInput(status)" />
                                    <ColorPicker v-model="status.color" format="hex" @update:modelValue="onStatusInput(status)" />
                                </div>
                                <i v-if="savingStatusId === status.id" class="pi pi-spin pi-spinner text-xs text-primary-500"></i>
                                <Button icon="pi pi-trash" text rounded size="small" severity="danger" class="opacity-0 group-hover:opacity-100 transition-opacity" @click="confirmDeleteStatus(status)" />
                            </div>
                        </div>
                    </div>
                </TabPanel>
            </TabPanels>
        </Tabs>
    </div>

    <div v-else-if="loading" class="card">
        <div class="flex justify-center py-8">
            <i class="pi pi-spin pi-spinner text-2xl"></i>
        </div>
    </div>
</template>
