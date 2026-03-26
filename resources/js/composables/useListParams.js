import { ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';

export function useListParams(defaults = {}) {
    const router = useRouter();
    const route = useRoute();

    const query = route.query;

    const currentPage = ref(parseInt(query.page) || defaults.page || 1);
    const perPage = ref(parseInt(query.per_page) || defaults.per_page || 20);
    const sortField = ref(query.sort_field || defaults.sort_field || null);
    const sortOrder = ref(
        query.sort_order === 'asc' ? 1
        : query.sort_order === 'desc' ? -1
        : defaults.sort_order || null
    );

    function syncUrl() {
        const params = {};

        if (currentPage.value > 1) params.page = currentPage.value;
        if (perPage.value !== 20) params.per_page = perPage.value;
        if (sortField.value) {
            params.sort_field = sortField.value;
            params.sort_order = sortOrder.value === 1 ? 'asc' : 'desc';
        }

        router.replace({ query: params });
    }

    function apiParams() {
        const params = {
            page: currentPage.value,
            per_page: perPage.value,
        };
        if (sortField.value) {
            params.sort_field = sortField.value;
            params.sort_order = sortOrder.value === 1 ? 'asc' : 'desc';
        }
        return params;
    }

    function onPage(event, loadFn) {
        currentPage.value = event.page + 1;
        perPage.value = event.rows;
        syncUrl();
        loadFn();
    }

    function onSort(event, loadFn) {
        sortField.value = event.sortField;
        sortOrder.value = event.sortOrder;
        currentPage.value = 1;
        syncUrl();
        loadFn();
    }

    return {
        currentPage,
        perPage,
        sortField,
        sortOrder,
        apiParams,
        syncUrl,
        onPage,
        onSort,
    };
}
