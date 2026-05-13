<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import { route } from 'ziggy-js';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Permissions',
                href: '/permissions',
            },
        ],
    },
});
</script>

<template>

    <Head title="Permissions" />
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <h1>Permission Model</h1>
    </div>
</template>
