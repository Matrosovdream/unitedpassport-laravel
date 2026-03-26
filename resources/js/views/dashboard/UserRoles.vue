<script setup>
import { ref, onMounted } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Tag from 'primevue/tag';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import Checkbox from 'primevue/checkbox';
import ToggleSwitch from 'primevue/toggleswitch';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';

const confirm = useConfirm();
const toast = useToast();
const roles = ref([]);
const availableRights = ref([]);
const loading = ref(true);
const dialogVisible = ref(false);
const saving = ref(false);
const editingRole = ref(null);
const errors = ref({});

const form = ref({
    name: '',
    slug: '',
    description: '',
    is_active: true,
    rights: [],
});

onMounted(async () => {
    await loadRoles();
});

async function loadRoles() {
    loading.value = true;
    try {
        const { data } = await window.axios.get('/user-roles');
        roles.value = data.roles;
        availableRights.value = data.available_rights || [];
    } catch (e) {
        console.error('Failed to load roles', e);
    } finally {
        loading.value = false;
    }
}

function formatRights(rights) {
    if (!rights || rights.length === 0) return [];
    return rights.map(r => r.role_code);
}

function rightsByGroup(rights) {
    const groups = {};
    rights.forEach(code => {
        const parts = code.split('_');
        const action = parts.pop();
        const entity = parts.join('_');
        if (!groups[entity]) groups[entity] = [];
        groups[entity].push({ code, action });
    });
    return groups;
}

function openAdd() {
    editingRole.value = null;
    errors.value = {};
    form.value = { name: '', slug: '', description: '', is_active: true, rights: [] };
    dialogVisible.value = true;
}

function openEdit(role) {
    if (!role.is_editable) return;
    editingRole.value = role;
    errors.value = {};
    form.value = {
        name: role.name || '',
        slug: role.slug || '',
        description: role.description || '',
        is_active: role.is_active ?? true,
        rights: formatRights(role.rights),
    };
    dialogVisible.value = true;
}

async function save() {
    saving.value = true;
    errors.value = {};

    try {
        if (editingRole.value) {
            await window.axios.put(`/user-roles/${editingRole.value.id}`, form.value);
            toast.add({ severity: 'success', summary: 'Role updated', life: 3000 });
        } else {
            await window.axios.post('/user-roles', form.value);
            toast.add({ severity: 'success', summary: 'Role added', life: 3000 });
        }
        dialogVisible.value = false;
        await loadRoles();
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

function confirmDelete(role) {
    confirm.require({
        message: `Are you sure you want to delete "${role.name}"?`,
        header: 'Delete Role',
        icon: 'pi pi-trash',
        rejectLabel: 'Cancel',
        acceptLabel: 'Delete',
        acceptClass: 'p-button-danger',
        accept: () => deleteRole(role.id),
    });
}

async function deleteRole(id) {
    try {
        await window.axios.delete(`/user-roles/${id}`);
        toast.add({ severity: 'success', summary: 'Role deleted', life: 3000 });
        await loadRoles();
    } catch (e) {
        toast.add({ severity: 'error', summary: e.response?.data?.message || 'Failed to delete', life: 5000 });
    }
}
</script>

<template>
    <div class="card">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold">User Roles</h2>
            <Button label="Add Role" icon="pi pi-plus" @click="openAdd" />
        </div>

        <DataTable :value="roles" :loading="loading" stripedRows>
            <Column field="id" header="#" sortable style="width: 60px" />
            <Column field="name" header="Name" sortable />
            <Column field="slug" header="Slug" sortable />
            <Column field="description" header="Description" />
            <Column header="Rights">
                <template #body="{ data }">
                    <div class="flex flex-wrap gap-1">
                        <Tag v-for="code in formatRights(data.rights)" :key="code" :value="code" severity="secondary" class="text-xs" />
                        <span v-if="!data.rights || data.rights.length === 0" class="text-surface-400">None</span>
                    </div>
                </template>
            </Column>
            <Column field="is_active" header="Active" sortable style="width: 80px">
                <template #body="{ data }">
                    <Tag :value="data.is_active ? 'Yes' : 'No'" :severity="data.is_active ? 'success' : 'warn'" />
                </template>
            </Column>
            <Column header="" style="width: 120px">
                <template #body="{ data }">
                    <template v-if="data.is_editable">
                        <Button icon="pi pi-pencil" text rounded @click="openEdit(data)" />
                        <Button icon="pi pi-trash" text rounded severity="danger" @click="confirmDelete(data)" />
                    </template>
                    <i v-else class="pi pi-lock text-surface-400" v-tooltip="'Not editable'" />
                </template>
            </Column>
        </DataTable>

        <Dialog v-model:visible="dialogVisible" :header="editingRole ? 'Edit Role' : 'Add Role'" modal :style="{ width: '600px' }">
            <div class="flex flex-col gap-4 pt-2">
                <div>
                    <label class="block text-sm font-medium mb-1">Name</label>
                    <InputText v-model="form.name" class="w-full" :invalid="!!errors.name" />
                    <small v-if="errors.name" class="text-red-500">{{ errors.name[0] }}</small>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Slug</label>
                    <InputText v-model="form.slug" class="w-full" :invalid="!!errors.slug" />
                    <small v-if="errors.slug" class="text-red-500">{{ errors.slug[0] }}</small>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Description</label>
                    <Textarea v-model="form.description" class="w-full" rows="2" />
                </div>

                <div class="flex items-center gap-2">
                    <ToggleSwitch v-model="form.is_active" />
                    <label class="text-sm font-medium">Active</label>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Rights</label>
                    <div class="flex flex-col gap-3">
                        <div v-for="(actions, entity) in rightsByGroup(availableRights)" :key="entity">
                            <div class="text-sm font-semibold mb-1 capitalize">{{ entity }}</div>
                            <div class="flex flex-wrap gap-3">
                                <div v-for="item in actions" :key="item.code" class="flex items-center gap-1">
                                    <Checkbox v-model="form.rights" :inputId="item.code" :value="item.code" />
                                    <label :for="item.code" class="text-sm capitalize">{{ item.action }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <template #footer>
                <Button label="Cancel" text @click="dialogVisible = false" />
                <Button :label="editingRole ? 'Update' : 'Create'" :loading="saving" @click="save" />
            </template>
        </Dialog>
    </div>
</template>
