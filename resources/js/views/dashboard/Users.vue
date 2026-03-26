<script setup>
import { ref, onMounted } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Tag from 'primevue/tag';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Select from 'primevue/select';
import Password from 'primevue/password';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';

const confirm = useConfirm();
const toast = useToast();
const users = ref([]);
const roles = ref([]);
const loading = ref(true);
const dialogVisible = ref(false);
const saving = ref(false);
const editingUser = ref(null);
const errors = ref({});

const form = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role_id: null,
    user_status: 0,
});

onMounted(async () => {
    await Promise.all([loadUsers(), loadRoles()]);
});

async function loadUsers() {
    loading.value = true;
    try {
        const { data } = await window.axios.get('/users');
        users.value = data.users;
    } catch (e) {
        console.error('Failed to load users', e);
    } finally {
        loading.value = false;
    }
}

async function loadRoles() {
    try {
        const { data } = await window.axios.get('/user-roles');
        roles.value = data.roles;
    } catch (e) {
        console.error('Failed to load roles', e);
    }
}

function openAdd() {
    editingUser.value = null;
    errors.value = {};
    form.value = { name: '', email: '', password: '', password_confirmation: '', role_id: null, user_status: 0 };
    dialogVisible.value = true;
}

function openEdit(user) {
    editingUser.value = user;
    errors.value = {};
    form.value = {
        name: user.name || '',
        email: user.email || '',
        password: '',
        password_confirmation: '',
        role_id: user.role_id,
        user_status: user.user_status ?? 0,
    };
    dialogVisible.value = true;
}

async function save() {
    saving.value = true;
    errors.value = {};

    try {
        if (editingUser.value) {
            await window.axios.put(`/users/${editingUser.value.id}`, form.value);
            toast.add({ severity: 'success', summary: 'User updated', life: 3000 });
        } else {
            await window.axios.post('/users', form.value);
            toast.add({ severity: 'success', summary: 'User added', life: 3000 });
        }
        dialogVisible.value = false;
        await loadUsers();
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

function confirmDelete(user) {
    confirm.require({
        message: `Are you sure you want to delete "${user.name}"?`,
        header: 'Delete User',
        icon: 'pi pi-trash',
        rejectLabel: 'Cancel',
        acceptLabel: 'Delete',
        acceptClass: 'p-button-danger',
        accept: () => deleteUser(user.id),
    });
}

async function deleteUser(id) {
    try {
        await window.axios.delete(`/users/${id}`);
        toast.add({ severity: 'success', summary: 'User deleted', life: 3000 });
        await loadUsers();
    } catch (e) {
        toast.add({ severity: 'error', summary: e.response?.data?.message || 'Failed to delete', life: 5000 });
    }
}

function roleName(roleId) {
    const role = roles.value.find(r => r.id === roleId);
    return role ? role.name : null;
}

const statusOptions = [
    { label: 'Active', value: 0 },
    { label: 'Inactive', value: 1 },
];
</script>

<template>
    <div class="card">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold">Users</h2>
            <Button label="Add User" icon="pi pi-plus" @click="openAdd" />
        </div>

        <DataTable :value="users" :loading="loading" stripedRows paginator :rows="20" :rowsPerPageOptions="[10, 20, 50]">
            <Column field="id" header="#" sortable style="width: 60px" />
            <Column field="name" header="Name" sortable />
            <Column field="email" header="Email" sortable />
            <Column field="role_id" header="Role" sortable>
                <template #body="{ data }">
                    <Tag v-if="data.role" :value="data.role.name" :severity="data.role.slug === 'admin' ? 'danger' : 'info'" />
                    <span v-else class="text-surface-400">—</span>
                </template>
            </Column>
            <Column field="user_status" header="Status" sortable style="width: 100px">
                <template #body="{ data }">
                    <Tag :value="data.user_status === 0 ? 'Active' : 'Inactive'" :severity="data.user_status === 0 ? 'success' : 'warn'" />
                </template>
            </Column>
            <Column header="" style="width: 120px">
                <template #body="{ data }">
                    <Button icon="pi pi-pencil" text rounded @click="openEdit(data)" />
                    <Button v-if="data.id !== 1" icon="pi pi-trash" text rounded severity="danger" @click="confirmDelete(data)" />
                </template>
            </Column>
        </DataTable>

        <Dialog v-model:visible="dialogVisible" :header="editingUser ? 'Edit User' : 'Add User'" modal :style="{ width: '500px' }">
            <div class="flex flex-col gap-4 pt-2">
                <div>
                    <label class="block text-sm font-medium mb-1">Name</label>
                    <InputText v-model="form.name" class="w-full" :invalid="!!errors.name" />
                    <small v-if="errors.name" class="text-red-500">{{ errors.name[0] }}</small>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <InputText v-model="form.email" class="w-full" :disabled="!!editingUser" :invalid="!!errors.email" />
                    <small v-if="editingUser" class="text-surface-400">Email cannot be changed</small>
                    <small v-if="errors.email" class="text-red-500">{{ errors.email[0] }}</small>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Password{{ editingUser ? ' (leave blank to keep)' : '' }}</label>
                    <Password v-model="form.password" class="w-full" toggleMask :feedback="false" :invalid="!!errors.password" />
                    <small v-if="errors.password" class="text-red-500">{{ errors.password[0] }}</small>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Confirm Password</label>
                    <Password v-model="form.password_confirmation" class="w-full" toggleMask :feedback="false" />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Role</label>
                    <Select v-model="form.role_id" :options="roles" optionLabel="name" optionValue="id" placeholder="Select role" class="w-full" showClear />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Status</label>
                    <Select v-model="form.user_status" :options="statusOptions" optionLabel="label" optionValue="value" class="w-full" />
                </div>
            </div>

            <template #footer>
                <Button label="Cancel" text @click="dialogVisible = false" />
                <Button :label="editingUser ? 'Update' : 'Create'" :loading="saving" @click="save" />
            </template>
        </Dialog>
    </div>
</template>
