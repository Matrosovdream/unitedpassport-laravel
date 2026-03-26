<script setup>
import { ref } from 'vue';
import AppMenuItem from './AppMenuItem.vue';

const model = ref([
    {
        label: 'Home',
        items: [
            { label: 'Dashboard', icon: 'pi pi-fw pi-home', to: '/dashboard' }
        ]
    },
    {
        label: 'Forms',
        items: [
            { label: 'Forms', icon: 'pi pi-fw pi-file-edit', to: '/dashboard/forms' },
            { label: 'Entries', icon: 'pi pi-fw pi-list', to: '/dashboard/entries' },
            { label: 'AI Photos', icon: 'pi pi-fw pi-camera', to: '/dashboard/ai-photos' }
        ]
    },
    {
        label: 'Payment',
        items: [
            { label: 'Mass Refund', icon: 'pi pi-fw pi-undo', to: '/dashboard/mass-refund' }
        ]
    },
    {
        label: 'Labels',
        items: [
            { label: 'Shipping Labels', icon: 'pi pi-fw pi-tag', to: '/dashboard/labels' }
        ]
    },
    {
        label: 'Management',
        items: [
            { label: 'Settings', icon: 'pi pi-fw pi-cog', to: '/dashboard/settings' },
            { label: 'Users', icon: 'pi pi-fw pi-users', to: '/dashboard/users' },
            { label: 'User Roles', icon: 'pi pi-fw pi-shield', to: '/dashboard/user-roles' }
        ]
    },
    {
        label: 'System',
        path: '/system',
        items: [
            { label: 'Migrations', icon: 'pi pi-fw pi-database', to: '/dashboard/migrations' }
        ]
    }
]);
</script>

<template>
    <ul class="layout-menu">
        <template v-for="(item, i) in model" :key="item">
            <app-menu-item v-if="!item.separator" :item="item" :index="i"></app-menu-item>
            <li v-if="item.separator" class="menu-separator"></li>
        </template>
    </ul>
</template>
