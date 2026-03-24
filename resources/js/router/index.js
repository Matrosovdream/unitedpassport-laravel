import { createRouter, createWebHistory } from 'vue-router';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import Dashboard from '../views/Dashboard.vue';

const routes = [
    {
        path: '/',
        redirect: '/login',
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: { guest: true },
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: { guest: true },
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: { auth: true },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    const user = await getUser();

    if (to.meta.auth && !user) {
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
        const { data } = await window.axios.get('/api/user');
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
