<script setup>
import { computed } from 'vue';

const props = defineProps({
    currentPage: { type: Number, required: true },
    perPage:     { type: Number, required: true },
    total:       { type: Number, required: true },
});

const from = computed(() => props.total === 0 ? 0 : (props.currentPage - 1) * props.perPage + 1);
const to   = computed(() => Math.min(props.currentPage * props.perPage, props.total));
</script>

<template>
    <span class="text-sm text-surface-500">
        Showing <strong class="text-surface-700">{{ from }}–{{ to }}</strong> of <strong class="text-surface-700">{{ total }}</strong>
    </span>
</template>
