import { createRouter, createWebHistory } from 'vue-router';
import PublicLayout from '../layouts/PublicLayout.vue';
import AppLayout from '../layouts/dashboard/AppLayout.vue';

const routes = [
    {
        path: '/',
        component: PublicLayout,
        children: [
            {
                path: '',
                name: 'home',
                component: () => import('../views/public/Home.vue'),
            },
            {
                path: 'login',
                name: 'login',
                component: () => import('../views/public/Login.vue'),
                meta: { guest: true },
            },
            {
                path: 'register',
                name: 'register',
                component: () => import('../views/public/Register.vue'),
                meta: { guest: true },
            },
        ],
    },
    {
        path: '/dashboard',
        component: AppLayout,
        meta: { auth: true },
        children: [
            {
                path: '',
                name: 'dashboard',
                component: () => import('../views/dashboard/Home.vue'),
            },
            {
                path: 'users',
                name: 'users',
                component: () => import('../views/dashboard/Users.vue'),
            },
            {
                path: 'user-roles',
                name: 'user-roles',
                component: () => import('../views/dashboard/UserRoles.vue'),
            },
            {
                path: 'migrations',
                name: 'migrations',
                component: () => import('../views/dashboard/Migrations.vue'),
            },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, _from, next) => {
    const user = await getUser();

    if (to.matched.some((r) => r.meta.auth) && !user) {
        return next({ name: 'login' });
    }

    if (to.meta.guest && user) {
        return next({ name: 'dashboard' });
    }

    next();
});

let cachedUser = undefined;

async function getUser() {
    if (cachedUser !== undefined) return cachedUser;

    try {
        const { data } = await window.axios.get('/user');
        cachedUser = data.user;
    } catch {
        cachedUser = null;
    }

    return cachedUser;
}

export function clearUserCache() {
    cachedUser = undefined;
}

export default router;
