<script setup lang="ts">
defineProps<{
    links: {
        url: string | null
        label: string
        active: boolean
    }[]
}>()

import { router } from '@inertiajs/vue3'
</script>

<template>
<div class="mt-4 flex gap-2">

    <button
        v-for="link in links"
        :key="link.label"
        v-html="link.label"
        :disabled="!link.url"
        @click="link.url && router.visit(link.url)"
        class="rounded border px-3 py-1"
        :class="link.active ? 'bg-black text-white' : ''"
    />

</div>
</template>
